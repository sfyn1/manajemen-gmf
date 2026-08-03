<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\RegistrationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Landing page publik + include semua route files per-role.
*/

// ── Halaman Publik ────────────────────────────────────────────────────────────
Route::get('/', function () {
    $packages = \App\Models\MembershipPackage::active()->get();
    $contents = \App\Models\LandingPageContent::active()->get()->groupBy('section');
    $classTypes = \App\Models\ClassType::active()->with(['schedules' => function ($query) {
        $query->active()->with(['coach.user', 'bookings']);
    }])->get();

    return view('welcome', compact('packages', 'contents', 'classTypes'));
})->name('home');

// ── QR Verify (publik, untuk scan oleh perangkat admin) ──────────────────────
Route::get('/qr/verify/{token}', [\App\Http\Controllers\Admin\ScanQrController::class, 'verify'])
    ->name('qr.verify');

// ── Self-registration Member (publik, sebelum login) ─────────────────────────
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/', [RegistrationController::class, 'showStep1'])->name('step1');
    Route::post('/step1', [RegistrationController::class, 'postStep1'])->name('step1.post');
    Route::get('/step2', [RegistrationController::class, 'showStep2'])->name('step2');
    Route::post('/step2', [RegistrationController::class, 'postStep2'])->name('step2.post');
    Route::get('/step3', [RegistrationController::class, 'showStep3'])->name('step3');
    Route::post('/step3', [RegistrationController::class, 'postStep3'])->name('step3.post');
    Route::get('/step4', [RegistrationController::class, 'showStep4'])->name('step4');
    Route::post('/step4', [RegistrationController::class, 'postStep4'])->name('step4.post');
    Route::get('/success', [RegistrationController::class, 'success'])->name('success');
});

// ── Auth Routes ───────────────────────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Admin Routes ──────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        require __DIR__ . '/admin.php';
    });

// ── Coach Routes ──────────────────────────────────────────────────────────────
Route::prefix('coach')
    ->name('coach.')
    ->middleware(['auth', 'role:coach'])
    ->group(function () {
        require __DIR__ . '/coach.php';
    });

// ── Member Routes ─────────────────────────────────────────────────────────────
Route::prefix('member')
    ->name('member.')
    ->middleware(['auth', 'role:member', 'member.active'])
    ->group(function () {
        require __DIR__ . '/member.php';
    });

// ── Owner Routes ──────────────────────────────────────────────────────────────
Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {
        require __DIR__ . '/owner.php';
    });
