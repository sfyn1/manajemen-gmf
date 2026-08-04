<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes — Shared untuk semua 4 aktor
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showForm'])
        ->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->name('login.post');

    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password/send-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])
        ->name('password.send-otp');
    Route::get('/forgot-password/verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyForm'])
        ->name('password.verify-otp');
    Route::post('/forgot-password/verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])
        ->name('password.verify-otp.post');
    Route::get('/forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])
        ->name('password.reset.post');

    // ── Registrasi Staff via Invite Token ──────────────────────────────────────
    Route::get('/register/staff', [\App\Http\Controllers\Auth\StaffRegistrationController::class, 'showForm'])
        ->name('register.staff');
    Route::post('/register/staff', [\App\Http\Controllers\Auth\StaffRegistrationController::class, 'submitForm'])
        ->name('register.staff.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])
        ->name('logout');
});
