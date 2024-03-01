<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['throttle:6,1'])
    ->name('verification.send');

Route::group(['middleware' => ['guest']], function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

    // Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    // Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware(['throttle:6,1'])
    //     ->name('verification.send');
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
});
