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

    /*
     * Routes that allow users management
     */
    Route::get('/user', function (Request $request) {
        return auth()->user();
    });


    /** 
     * Routes that allow authors search 
     */
    Route::get('/authors/search', [AuthorController::class, 'search'])->name('authors.search');
    /**
     * Routes that allow authors management
     */
    Route::resource('/authors', AuthorController::class,  ['except' => ['create', 'edit']]);
    // Route::get('/authors', [AuthorController::class, 'index'])->name('authors');

    // Route::post('/authors/store', [AuthorController::class, 'store'])->name('authors.store');

    // Route::post('/authors/edit', [AuthorController::class, 'edit'])->name('authors.edit');

    // Route::post('/authors/destroy/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy');


    /**
     * Routes that allow music sheets management
     */
    Route::resource('/music-sheets', MusicSheetController::class, ['except' => ['create', 'edit']]);
    // Route::get('/music-sheets', [MusicSheetController::class, 'index'])->name('music-sheets');

    // Route::post('/music-sheets/store', [MusicSheetController::class, 'store'])->name('music-sheets.store');

    // Route::post('/music-sheets/edit', [MusicSheetController::class, 'edit'])->name('music-sheets.edit');

    // Route::post('/music-sheets/destroy/{id}', [MusicSheetController::class, 'destroy'])->name('music-sheets.destroy');

    // Route::post('/music-sheets/update', [MusicSheetController::class, 'update'])->name('music-sheets.update');

    /**
     * Routes that allow music sheets search
     */
    Route::get('/music-sheets-search/search', [MusicSheetController::class, 'search'])->name('music-sheets.search');

    /**
     * Routes that allow music sheet file management
     */
    Route::post('/sheet-file/store', [MusicSheetFileController::class, 'store'])->name('music-sheet-file.store');

    Route::post('/sheet-file/update', [MusicSheetFileController::class, 'update'])->name('music-sheet-file.update');

    Route::delete('/sheet-file/destroy/{id}', [MusicSheetFileController::class, 'destroy'])->name('music-sheet-file.destroy');

    Route::get('/sheet-file/download/{id}', [MusicSheetFileController::class, 'download'])->name('music-sheet-file.download');

    /** 
     * Routes that allow authors search 
     */
    Route::get('/genders/search', [GenderController::class, 'search'])->name('genders.search');
    /**
     * Routes that allow gender management
     */
    Route::resource('/genders', GenderController::class, ['except' => ['create', 'edit']]);
    // Route::get('/genders', [GenderController::class, 'index'])->name('genders');

    // Route::post('/genders/store', [GenderController::class, 'store'])->name('genders.store');

    // Route::post('/genders/edit', [GenderController::class, 'edit'])->name('genders.edit');

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
    Route::resource('/borrowers', BorrowersController::class, ['except' => ['create', 'edit']]);
    // Route::get('/borrowers', [BorrowersController::class, 'index'])->name('borrowers');

    // Route::post('/borrowers/store', [BorrowersController::class, 'store'])->name('borrowers.store');

    Route::post('/borrowers/edit', [BorrowersController::class, 'edit'])->name('borrowers.edit');

    // Route::post('/borrowers/destroy/{id}', [BorrowersController::class, 'destroy'])->name('borrowers.destroy');


    /**
     * Routes that allow loans management
     */
    Route::resource('/loans', LoansController::class, ['except' => ['create', 'edit']]);
    // Route::get('/loan', [LoansController::class, 'index'])->name('loans');

    // Route::post('/loan/store', [LoansController::class, 'store'])->name('loans.store');

    // Route::post('/loan/destroy/{id}', [LoansController::class, 'destroy'])->name('loans.destroy');

    Route::post('/loan/return', [LoansController::class, 'returnLoan'])->name('loans.return');
});
