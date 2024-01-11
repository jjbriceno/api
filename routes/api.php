<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\CabinetsController;
use App\Http\Controllers\DrawersController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\MusicSheetController;
use App\Http\Controllers\MusicSheetFileController;
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
        return auth()->user();
    });

    /**
     * Routes that allow music sheets search
     */
    Route::get('/music-sheets/search', [MusicSheetController::class, 'search'])->name('music-sheets.search');

    /**
     * Routes that allow authors management
     */

    Route::get('/authors', [AuthorController::class, 'index'])->name('authors');

    // Route::post('/authors/store', [AuthorController::class, 'store'])->name('authors.store');

    // Route::post('/authors/edit', [AuthorController::class, 'edit'])->name('authors.edit');

    // Route::post('/authors/destroy/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy');


    /**
     * Routes that allow music sheets management
     */

    Route::get('/music-sheets', [MusicSheetController::class, 'index'])->name('music-sheets');

    // Route::post('/music-sheets/store', [MusicSheetController::class, 'store'])->name('music-sheets.store');

    // Route::post('/music-sheets/edit', [MusicSheetController::class, 'edit'])->name('music-sheets.edit');

    // Route::post('/music-sheets/destroy/{id}', [MusicSheetController::class, 'destroy'])->name('music-sheets.destroy');

    // Route::post('/music-sheets/update', [MusicSheetController::class, 'update'])->name('music-sheets.update');

    /**
     * Routes that allow music sheet file management
     */

    // Route::post('/sheet-file/store', [MusicSheetFileController::class, 'store'])->name('music-sheet-file.store');

    // Route::post('/sheet-file/update', [MusicSheetFileController::class, 'update'])->name('music-sheet-file.update');

    // Route::post('/sheet-file/destroy/{id}', [MusicSheetFileController::class, 'destroy'])->name('music-sheet-file.destroy');

    Route::get('/sheet-file/download/{id}', [MusicSheetFileController::class, 'download'])->name('music-sheet-file.download');

    /**
     * Routes that allow gender management
     */

    Route::get('/genders', [GenderController::class, 'index'])->name('genders');

    // Route::post('/genders/store', [GenderController::class, 'store'])->name('genders.store');

    // Route::post('/genders/edit', [GenderController::class, 'edit'])->name('genders.edit');

    /**
     * Routes that allow drawers management
     */

    Route::get('/drawers', [DrawersController::class, 'index'])->name('drawers');

    /**
     * Routes that allow cabinets management
     */

<<<<<<< Updated upstream
    Route::get('/cabinets', [CabinetsController::class, 'index'])->name('cabinets');
=======
    /** 
     * Routes that allow Borrower search 
     */
    Route::get('/borrowers/search', [BorrowersController::class, 'search'])->name('Borrower.search');
>>>>>>> Stashed changes

    /**
     * Routes that allow Borrower management
     */
<<<<<<< Updated upstream

    Route::get('/borrowers', [BorrowersController::class, 'index'])->name('borrowers');
=======
    Route::resource('/borrowers', BorrowersController::class, ['except' => ['create', 'edit']]);
    // Route::get('/Borrower', [BorrowersController::class, 'index'])->name('Borrower');
>>>>>>> Stashed changes

    // Route::post('/Borrower/store', [BorrowersController::class, 'store'])->name('Borrower.store');

    // Route::post('/Borrower/edit', [BorrowersController::class, 'edit'])->name('Borrower.edit');

    // Route::post('/Borrower/destroy/{id}', [BorrowersController::class, 'destroy'])->name('Borrower.destroy');


    /**
     * Routes that allow loans management
     */

    Route::get('/loan', [LoansController::class, 'index'])->name('loans');

    // Route::post('/loan/store', [LoansController::class, 'store'])->name('loans.store');

    // Route::post('/loan/destroy/{id}', [LoansController::class, 'destroy'])->name('loans.destroy');

    // Route::post('/loan/return', [LoansController::class, 'returnLoan'])->name('loans.return');

});
