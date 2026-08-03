<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner;

/*
|--------------------------------------------------------------------------
| Owner Routes — prefix: /owner, middleware: auth + role:owner
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [Owner\DashboardController::class, 'index'])->name('dashboard');

// ── Reports ───────────────────────────────────────────────────────────────────
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [Owner\ReportController::class, 'index'])->name('index');
    Route::get('/download', [Owner\ReportController::class, 'download'])->name('download');
});

// ── Staff Account Management ──────────────────────────────────────────────────
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [Owner\StaffAccountController::class, 'index'])->name('index');
    Route::get('/create', [Owner\StaffAccountController::class, 'create'])->name('create');
    Route::post('/', [Owner\StaffAccountController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [Owner\StaffAccountController::class, 'edit'])->name('edit');
    Route::put('/{user}', [Owner\StaffAccountController::class, 'update'])->name('update');
    Route::delete('/{user}', [Owner\StaffAccountController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/toggle-active', [Owner\StaffAccountController::class, 'toggleActive'])->name('toggle-active');
});
