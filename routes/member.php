<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member as MemberController;

/*
|--------------------------------------------------------------------------
| Member Routes — prefix: /member, middleware: auth + role:member
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [MemberController\DashboardController::class, 'index'])->name('dashboard');

// ── Class Booking ─────────────────────────────────────────────────────────────
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [MemberController\ClassBookingController::class, 'index'])->name('index');
    Route::post('/confirm-success', [MemberController\ClassBookingController::class, 'confirmSuccess'])->name('confirm-success');
    Route::post('/{schedule}', [MemberController\ClassBookingController::class, 'book'])->name('book');
    Route::delete('/{booking}/cancel', [MemberController\ClassBookingController::class, 'cancel'])->name('cancel');
});

// ── Class History ─────────────────────────────────────────────────────────────
Route::get('/class-history', [MemberController\ClassHistoryController::class, 'index'])->name('class-history.index');

// ── Invoice ───────────────────────────────────────────────────────────────────
Route::get('/invoice', [MemberController\InvoiceController::class, 'index'])->name('invoice.index');

// ── Renewal Membership ────────────────────────────────────────────────────────
Route::post('/renewal', [MemberController\RenewalController::class, 'store'])->name('renewal.store');
Route::delete('/renewal/{renewal}/cancel', [MemberController\RenewalController::class, 'cancel'])->name('renewal.cancel');
Route::post('/renewal/confirm-success', [MemberController\RenewalController::class, 'confirmSuccess'])->name('renewal.confirm-success');

// ── Profile ───────────────────────────────────────────────────────────────────
Route::get('/profile', [MemberController\ProfileController::class, 'index'])->name('profile.index');
