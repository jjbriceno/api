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
use App\Http\Controllers\MusicSheetController;
use App\Http\Controllers\MusicSheetFileController;
use App\Http\Requests\NewPassword\NewPasswordResquest;

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

    /**
     * Route that allow profile picture update
     */
    Route::post('/profiles/update-picture', [ProfileController::class, 'updateUserProfilePicture'])->name('profiles.update-picture');

    /**
     * Route that allow password update
     */
    Route::put('/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');

    /**
     * Routes that allow profile management
     */
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
     * Route that deletes one user
     */
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    /**
     * Route that gets all users
     */
    Route::get('/get-all-users', [UserController::class, 'getUsers'])->name('users.get-all');

    /**
     * Route that gets users by search query
     */
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

    /**
     * Route that changes user role (Admin or User)
     */
    Route::post('/users/change-user-role', [UserController::class, 'changeUserRole'])->name('users.change-user-role');


    /**
     * Route that gets users with active loans management
     */
    Route::get('/users-with-active-loans', [UserController::class, 'getUsersWithActiveLoans'])->name('users.usersWithActiveLoans');
   
    /** 
     * Routes that allow borrowers search 
     */
    Route::get('/users-with-active-loans/search', [UserController::class, 'searchUsersWithActiveLoans'])->name('users.searchUsersWithActiveLoans');
    
    /**
     * Routes that allow loans management
     */
    Route::get('/loans', [LoansController::class, 'index'])->name('index.get');

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

    Route::post('/loan/update-cart-item', [LoanCartController::class, 'updateCartItem'])->name('loans.updateCartItem');

    Route::delete('/loan/delete-cart-item/{id}', [LoanCartController::class, 'deleteCartItem'])->name('loans.deleteCartItem');

    Route::post('/loan/validate-music-sheet-quantity', [LoanCartController::class, 'validateMusicSheetQuantity'])->name('loans.validateMusicSheetQuantity');

    Route::get('loan/get-available-quantity-for-music-sheet/{id}', [LoanCartController::class, 'getAvailableQuantityForMusicSheet'])->name('loans.getAvailableQuantityForMusicSheet');

    Route::post('/loan/delete-cart-items', [LoanCartController::class, 'deleteCartItems'])->name('loans.deleteCartItems');

    Route::post('/loan/validate-add-to-cart', [LoanCartController::class, 'addToCart'])->name('loans.addToCart');

    Route::post('/loan/return', [LoansController::class, 'returnLoan'])->name('loans.return');

    Route::post('/loan/get-loan-for-status', [LoansController::class, 'getLoans'])->name('loans.getLoansForStatus');

    Route::post('/loan/change-status-loan', [LoansController::class, 'changeStatusLoan'])->name('loans.changeStatusLoan');

    Route::resource('/loans', LoansController::class, ['except' => ['create', 'edit']]);
});
