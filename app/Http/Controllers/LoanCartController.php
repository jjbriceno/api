<?php

namespace App\Http\Controllers;

use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Events\Loan\NewLoanRegisterEvent;
use App\Http\Resources\MusicSheetResource;
use App\Http\Requests\Loan\AddToCartRequest;
use App\Http\Requests\Loan\updateCartItemRequest;

class LoanCartController extends Controller
{
    /**
     * Add item to cart
     */
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

    /**
     * Return all items in the cart
     */
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
   
    public function getAvailableQuantityForMusicSheet($id)
    {
        $musicSheet = MusicSheet::lockForUpdate()->find($id);

        return response()->json(['available' => $musicSheet->available], Response::HTTP_OK);
    }

    /**
     * Delete an item from the cart
     */
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

    /**
     * Update an item in the cart
     */
    public function updateCartItem(updateCartItemRequest $request)
    {
        $cart = $request->session()->get('cart', []);

        $itemToEdit = $cart[$request->id];

        $musicSheet = MusicSheet::find($itemToEdit['id']);

        $difference = $musicSheet->available + $itemToEdit['quantity'] - $request->quantity;

        $musicSheet->update(['available' => $difference]);

        $cart[$request->id]['quantity'] = $request->quantity;

        $request->session()->put('cart', $cart);

        $totalCartMusicSheets = collect($request->session()->get('cart', $cart))->sum('quantity');

        return response()->json([
                'music_sheet' => new MusicSheetResource(MusicSheet::find($request->id)),
                'total' => $totalCartMusicSheets,
                'cart' => $request->session()->get('cart'),
            ]);
    }

    /**
     * Delete all items from cart
     */
    public function deleteCartItems(Request $request)
    {
        $request->session()->forget('cart');

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
