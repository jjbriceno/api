<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Events\Loan\NewLoanRegisterEvent;
use App\Http\Resources\MusicSheetResource;
use App\Http\Requests\Loan\AddToCartRequest;
use App\Http\Requests\Loan\ValidateMusicSheetQuantityRequest;

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
                $cart[$validated["musicSheetId"]]["quantity"] += $validated["quantity"];
            } else {
                $cart[$validated["musicSheetId"]] = [
                    'id' => $validated["musicSheetId"],
                    'title' => $musicSheet->title,
                    'author' => $musicSheet->author->full_name,
                    'quantity' => $validated["quantity"],
                ];
            }
            event(new NewLoanRegisterEvent($musicSheet, $validated["quantity"]));

            $request->session()->put('cart', $cart);

            $totalCartMusicSheets = collect($request->session()->get('cart', $cart))->sum('quantity');

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
                    $musicSheet->available += $cart[$musicSheet->id]['quantity'];
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

    public function validateMusicSheetQuantity(Request $request)
    {
        dd($request->all());
        $musicSheet = MusicSheet::lockForUpdate()->find($request->id);

        $available = $musicSheet->available;

        $quantity = $musicSheet->quantity;

        $currentQuantity = $request->quantity;

        if ($quantity < $currentQuantity - $available) {
            return response()->json([
                'errors' =>
                ['quantity' => ['No hay suficientes partituras disponibles. Disponibles: ' . $available]],
                'available' => $available
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            return response()->json(['message' => 'success', 'available' => $available], Response::HTTP_OK);
        }
        // $request->validate([
        //     'id'        => ['required', 'exists:music_sheets,id'],
        //     'quantity'  => ['required', 'numeric', 'gt:0', 'lte:' . $available]
        // ], [
        //     'id.exists' => 'La paritura no ha sido encontrada o no existe en nuestros registros.',
        //     'quantity.gt' => 'La cantidad debe ser igual o mayor a 1.',
        //     'quantity.lte' => $available > 0 ? 'La cantidad no puede ser mayor que la cantidad disponible. Disponibles: ' . $available : 'No hay suficientes partituras disponibles.',
        // ]);

        $cart = $request->session()->get('cart', []);

        $musicSheet = MusicSheet::find($request->id);

        dd($musicSheet);
    }

    public function getAvailableQuantityForMusicSheet($id)
    {
        $musicSheet = MusicSheet::lockForUpdate()->find($id);

        return response()->json(['available' => $musicSheet->available], Response::HTTP_OK);
    }

    public function deleteCartItem(Request $request, $id)
    {
        $cart = $request->session()->get('cart', []);

        $loanMusicSheet = $cart[$id];

        $musicSheet = MusicSheet::find($loanMusicSheet['id']);

        $musicSheet->update(['available' => $musicSheet->available + $loanMusicSheet['quantity']]);

        unset($cart[$id]);

        $request->session()->put('cart', $cart);

        $cart = $request->session()->get('cart');

        $totalCartMusicSheets = collect($cart)->sum('quantity');

        return response()->json(['cart' => $cart, 'total' => $totalCartMusicSheets, 'music_sheet' => $musicSheet], Response::HTTP_OK);
    }

    public function deleteCartItems(Request $request)
    {
        $request->session()->forget('cart');

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
