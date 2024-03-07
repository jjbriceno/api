<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MusicSheet;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Events\Loan\NewLoanRegisterEvent;
use App\Http\Resources\MusicSheetResource;
use App\Http\Requests\Loan\AddToCartRequest;
use Illuminate\Http\Request;

class LoanCartController extends Controller
{
    public function addToCart(AddToCartRequest $request): JsonResponse
    {
        DB::beginTransaction();

        $validated = $request->validated();

        try {
            $musicSheet = MusicSheet::sharedLock()->find($validated["musicSheetId"]);

            if (!$musicSheet) {
                return response()->json(
                    ['message' => 'La paritura no ha sido encontrada o no existe en nuestros registros.'],
                    Response::HTTP_NOT_FOUND
                );
            }

            $cart = $request->session()->get('cart', []);

            if (isset($cart[$validated["musicSheetId"]])) {
                $cart[$validated["musicSheetId"]]["cuantity"] = $validated["cuantity"];
            } else {
                $cart[$validated["musicSheetId"]] = [
                    'id' => $validated["musicSheetId"],
                    'title' => $musicSheet->title,
                    'author' => $musicSheet->author->full_name,
                    'cuantity' => $validated["cuantity"],
                ];
            }
            event(new NewLoanRegisterEvent($musicSheet, $validated["cuantity"]));

            $request->session()->put('cart', $cart);

            $totalCartMusicSheets = collect($request->session()->get('cart', $cart))->sum('cuantity');

            DB::commit();

            return response()->json([
                'music_sheet' => new MusicSheetResource(MusicSheet::find($validated["musicSheetId"])),
                'total' => $totalCartMusicSheets,
                'cart' => $request->session()->get('cart'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function restoreCartItems(Request $request)
    {
        DB::beginTransaction();

        try {
            $cart = $request->session()->get('cart');

            if (!empty($cart)) {
                $musicSheetsIds = array_keys($cart);
                $musicSheets = MusicSheet::sharedLock()->whereIn('id', $musicSheetsIds)->get();
                $musicSheets->each(function ($musicSheet) use ($cart) {
                    $musicSheet->available += $cart[$musicSheet->id]['cuantity'];
                    $musicSheet->save();
                });
                $request->session()->forget('cart');

                DB::commit();

                return response()->json(['message' => 'success'], Response::HTTP_OK);
            }

            return response()->json(['message' => 'No hay elementos en el carrito'], Response::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function deleteCartItems(Request $request)
    {
        $request->session()->forget('cart');

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
