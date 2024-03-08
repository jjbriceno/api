<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\DrawersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CabinetsController;
use App\Http\Controllers\LoanCartController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\MusicSheetController;
use App\Http\Controllers\MusicSheetFileController;

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
    /** Route that allows to get all authors */
    Route::get('/all-authors', [AuthorController::class, 'getAuthors'])->name('all-authors');
    /**
     * Routes that allow authors management
     */
    Route::resource('/authors', AuthorController::class,  ['except' => ['create', 'edit']]);

    Route::resource('profiles', ProfileController::class, ['except' => ['create', 'store', 'destroy', 'edit']]);

    /**
     * Routes that allow music sheets management
     */
    Route::resource('/music-sheets', MusicSheetController::class, ['except' => ['create', 'edit']]);

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

    Route::get('/all-genders', [GenderController::class, 'getGenders'])->name('all-genders');

    /**
     * Routes that allow drawers management
     */
    Route::get('/drawers', [DrawersController::class, 'index'])->name('drawers');

    /**
     * Routes that allow cabinets management
     */
    Route::get('/cabinets', [CabinetsController::class, 'index'])->name('cabinets');

    /**
     * Route that gets all users
     */
    Route::get('/users', [UserController::class, 'index'])->name('users.get');

    /**
     * Route that gets users with active loans management
     */
    Route::get('/users-with-active-loans', [UserController::class, 'getUsersWithActiveLoans'])->name('usersWithActiveLoans.get');

    /**
     * Routes that allow loans management
     */
    Route::get('/get-borrowers', [LoansController::class, 'index'])->name('index.get');

    /** 
     * Routes that allow borrowers search 
     */
    Route::get('/borrowers/search', [LoansController::class, 'search'])->name('borrowers.search');

    /** Route that allow get loans for a borrower */
    Route::get('/get-borrower-loans/{id}', [LoansController::class, 'getBorrowerLoans'])->name('borrowersLoans.get');

    /**
     * Routes that allow borrowers management
     */
    // Route::resource('/borrowers', BorrowersController::class, ['except' => ['create', 'edit']]);

    /**
     * Routes that allow loans management
     */
    Route::get('/loan/get-music-sheets/{loanId}', [LoansController::class, 'getLoanMusicSheets'])->name('loans.getMusicSheets');

    Route::post('/loan/restore-cart-items', [LoanCartController::class, 'restoreCartItems'])->name('loans.restoreCartItems');

    Route::post('/loan/delete-cart-items', [LoanCartController::class, 'deleteCartItems'])->name('loans.deleteCartItems');

    Route::post('/loan/validate-add-to-cart', [LoanCartController::class, 'addToCart'])->name('loans.addToCart');

    Route::post('/loan/return', [LoansController::class, 'returnLoan'])->name('loans.return');

    Route::resource('/loans', LoansController::class, ['except' => ['create', 'edit']]);
});
