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
     * Route that allows to get all authors
     */
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

    /**
     * Route to update music sheet file
     */
    Route::post('/sheet-file/update', [MusicSheetFileController::class, 'update'])->name('music-sheet-file.update');

    /**
     * Route to destroy music sheet file
     */
    Route::delete('/sheet-file/destroy/{id}', [MusicSheetFileController::class, 'destroy'])->name('music-sheet-file.destroy');

    /**
     * Route to download music sheet file
     */
    Route::get('/sheet-file/download/{id}', [MusicSheetFileController::class, 'download'])->name('music-sheet-file.download');

    /**
     * Routes that allow authors search
     */
    Route::get('/genders/search', [GenderController::class, 'search'])->name('genders.search');
    
    /**
     * Routes that allow gender management
     */
    Route::resource('/genders', GenderController::class, ['except' => ['create', 'edit']]);

    /**
     * Route that allows to get all genders
     */
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
     * Route that creates a new user
     */
    Route::post('/users/create-new-user', [UserController::class, 'createNewUser'])->name('users.create.new');

    /**
     * Route that updates one user profile
     */
    Route::put('/users/update-profile/{id}', [ProfileController::class, 'updateUserProfile'])->name('users.update-profile');

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
    Route::post('/users/change-user-role/{id}', [UserController::class, 'changeUserRole'])->name('users.change-user-role');

    /**
     * Route that gets users with active loans management
     */
    Route::get('/users-with-active-loans', [UserController::class, 'getUsersWithActiveLoans'])->name('users.usersWithActiveLoans');

    /**
     * Routes that allow borrowers search
     */
    Route::get('/users-with-active-loans/search', [UserController::class, 'searchUsersWithActiveLoans'])->name('users.searchUsersWithActiveLoans');

    /** 
     * Route that allow get loans for a borrower 
     */
    Route::get('/get-borrower-loans/{id}', [LoansController::class, 'getBorrowerLoans'])->name('borrowersLoans.get');

    /**
     * Routes that allow loans management
     */
    Route::get('/loan/get-music-sheets/{loanId}', [LoansController::class, 'getLoanMusicSheets'])->name('loans.getMusicSheets');

    /**
     * Route that allows to restore cart items
     */
    Route::post('/loan/restore-cart-items', [LoanCartController::class, 'restoreCartItems'])->name('loans.restoreCartItems');

    /**
     * Route that allows to update cart item
     */
    Route::post('/loan/update-cart-item', [LoanCartController::class, 'updateCartItem'])->name('loans.updateCartItem');

    /**
     * Route that allows to delete cart item
     */
    Route::delete('/loan/delete-cart-item/{id}', [LoanCartController::class, 'deleteCartItem'])->name('loans.deleteCartItem');

    /**
     * Route that allows to get available quantity for music sheet
     */
    Route::get('loan/get-available-quantity-for-music-sheet/{id}', [LoanCartController::class, 'getAvailableQuantityForMusicSheet'])->name('loans.getAvailableQuantityForMusicSheet');

    /**
     * Route that allows to delete cart items
     */
    Route::post('/loan/delete-cart-items', [LoanCartController::class, 'deleteCartItems'])->name('loans.deleteCartItems');

    /**
     * Route that allows to add to cart
     */
    Route::post('/loan/validate-add-to-cart', [LoanCartController::class, 'addToCart'])->name('loans.addToCart');

    /**
     * Route that allows to return loan
     */
    Route::post('/loan/return', [LoansController::class, 'returnLoan'])->name('loans.return');

    /**
     * Route that allows to get loans for status
     */
    Route::post('/loan/get-loan-for-status', [LoansController::class, 'getLoans'])->name('loans.getLoansForStatus');

    /**
     * Route that allows to change loan status
     */
    Route::post('/loan/change-status-loan', [LoansController::class, 'changeLoanStatus'])->name('loans.changeLoanStatus');

    /**
     * Routes that allow loans management
     */
    Route::resource('/loans', LoansController::class, ['except' => ['create', 'edit']]);
});
