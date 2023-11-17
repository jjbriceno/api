<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicSheetController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;


Route::group(['middleware' => ['guest']], function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});


Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::group(['prefix' => 'authors'], function () {
        Route::post('/store', [AuthorController::class, 'store'])->name('authors.store');

        Route::post('/edit', [AuthorController::class, 'edit'])->name('authors.edit');

        Route::post('/destroy/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy');
    });

    Route::group(['prefix' => 'music-sheets'], function () {
        Route::post('/store', [MusicSheetController::class, 'store'])->name('music-sheets.store');

        Route::post('/edit', [MusicSheetController::class, 'edit'])->name('music-sheets.edit');

        Route::post('/destroy/{id}', [MusicSheetController::class, 'destroy'])->name('music-sheets.destroy');

        Route::post('/update', [MusicSheetController::class, 'update'])->name('music-sheets.update');
    });

    Route::group(['prefix' => 'sheet-file'], function () {
        Route::post('/store', [MusicSheetFileController::class, 'store'])->name('music-sheet-file.store');

        Route::post('/update', [MusicSheetFileController::class, 'update'])->name('music-sheet-file.update');

        Route::post('/destroy/{id}', [MusicSheetFileController::class, 'destroy'])->name('music-sheet-file.destroy');
    });

    Route::group(['prefix' => 'genders'], function () {
        Route::post('/store', [GenderController::class, 'store'])->name('genders.store');

        Route::post('/edit', [GenderController::class, 'edit'])->name('genders.edit');
    });

    Route::group(['prefix' => 'borrowers'], function () {
        Route::post('/store', [BorrowersController::class, 'store'])->name('borrowers.store');

        Route::post('/edit', [BorrowersController::class, 'edit'])->name('borrowers.edit');

        Route::post('/destroy/{id}', [BorrowersController::class, 'destroy'])->name('borrowers.destroy');
    });


    Route::group(['prefix' => 'loan'], function () {
        Route::post('/store', [LoansController::class, 'store'])->name('loans.store');

        Route::post('/destroy/{id}', [LoansController::class, 'destroy'])->name('loans.destroy');

        Route::post('/return', [LoansController::class, 'returnLoan'])->name('loans.return');
    });
});
