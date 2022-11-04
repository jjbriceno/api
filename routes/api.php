<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\CabinetsController;
use App\Http\Controllers\DrawersController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\MusicSheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // Get the currently authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /**
     * Routes that allow authors management
     */

    Route::get('/authors', [AuthorController::class, 'index'])->name('authors');


    /**
     * Routes that allow music sheets management
     */

    Route::get('/music-sheets', [MusicSheetController::class, 'index'])->name('music-sheets');

    Route::post('/music-sheets/store', [MusicSheetController::class, 'store'])->name('music-sheets.store');

    Route::post('/music-sheets/edit', [MusicSheetController::class, 'edit'])->name('music-sheets.edit');

    Route::post('/music-sheets/destroy/{id}', [MusicSheetController::class, 'destroy'])->name('music-sheets.destroy');

    /**
     * Routes that allow gender management
     */

    Route::get('/genders', [GenderController::class, 'index'])->name('genders');

    /**
     * Routes that allow drawers management
     */

    Route::get('/drawers', [DrawersController::class, 'index'])->name('drawers');

    /**
     * Routes that allow cabinets management
     */

    Route::get('/cabinets', [CabinetsController::class, 'index'])->name('cabinets');

    /**
     * Routes that allow borrowers management
     */

    Route::post('/borrowers/store', [BorrowersController::class, 'store'])->name('borrowers.store');

    Route::get('/borrowers', [BorrowersController::class, 'index'])->name('borrowers');

    /**
     * Routes that allow loans management
     */

    Route::post('/loan/store', [LoansController::class, 'store'])->name('loans.store');
});
