<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coach;

/*
|--------------------------------------------------------------------------
| Coach Routes — prefix: /coach, middleware: auth + role:coach
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [Coach\DashboardController::class, 'index'])->name('dashboard');

// ── Attendance Verification ───────────────────────────────────────────────────
Route::prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/', [Coach\AttendanceVerificationController::class, 'index'])->name('index');
    Route::post('/{verification}/submit', [Coach\AttendanceVerificationController::class, 'submit'])->name('submit');
    Route::post('/{verification}/resubmit', [Coach\AttendanceVerificationController::class, 'resubmit'])->name('resubmit');
});

// ── Teaching History ──────────────────────────────────────────────────────────
Route::get('/history', [Coach\TeachingHistoryController::class, 'index'])->name('history.index');

// ── Commission / Payroll ──────────────────────────────────────────────────────
Route::get('/commission', [Coach\CommissionController::class, 'index'])->name('commission.index');

// ── Roster ────────────────────────────────────────────────────────────────────
Route::get('/roster/{schedule}/{date}', [Coach\RosterController::class, 'show'])->name('roster.show');
