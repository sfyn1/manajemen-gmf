<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Routes — prefix: /admin, middleware: auth + role:admin
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

// ── Approval Center ──────────────────────────────────────────────────────────
Route::prefix('approval')->name('approval.')->group(function () {
    Route::get('/', [Admin\ApprovalCenterController::class, 'index'])->name('index');
    Route::post('/members/{member}/approve', [Admin\ApprovalCenterController::class, 'approveMember'])->name('member.approve');
    Route::post('/members/{member}/reject', [Admin\ApprovalCenterController::class, 'rejectMember'])->name('member.reject');
    Route::post('/renewals/{renewal}/approve', [Admin\ApprovalCenterController::class, 'approveRenewal'])->name('renewal.approve');
    Route::post('/renewals/{renewal}/reject', [Admin\ApprovalCenterController::class, 'rejectRenewal'])->name('renewal.reject');
    Route::post('/verifications/{verification}/approve', [Admin\ApprovalCenterController::class, 'approveVerification'])->name('verification.approve');
    Route::post('/verifications/{verification}/reject', [Admin\ApprovalCenterController::class, 'rejectVerification'])->name('verification.reject');
});

// ── Membership Management ─────────────────────────────────────────────────────
Route::prefix('membership')->name('membership.')->group(function () {
    Route::get('/', [Admin\MembershipController::class, 'index'])->name('index');
    Route::get('/{member}', [Admin\MembershipController::class, 'show'])->name('show');
    Route::get('/{member}/edit', [Admin\MembershipController::class, 'edit'])->name('edit');
    Route::put('/{member}', [Admin\MembershipController::class, 'update'])->name('update');
});

// ── QR Scan ───────────────────────────────────────────────────────────────────
Route::prefix('scan-qr')->name('scan-qr.')->group(function () {
    Route::get('/', [Admin\ScanQrController::class, 'index'])->name('index');
    Route::post('/scan', [Admin\ScanQrController::class, 'scan'])->name('scan');
    Route::get('/history', [Admin\ScanQrController::class, 'history'])->name('history');
});

// ── Coach Management ──────────────────────────────────────────────────────────
Route::prefix('coaches')->name('coaches.')->group(function () {
    Route::get('/', [Admin\CoachManagementController::class, 'index'])->name('index');
    Route::get('/create', [Admin\CoachManagementController::class, 'create'])->name('create');
    Route::post('/', [Admin\CoachManagementController::class, 'store'])->name('store');
    Route::get('/{coach}/edit', [Admin\CoachManagementController::class, 'edit'])->name('edit');
    Route::put('/{coach}', [Admin\CoachManagementController::class, 'update'])->name('update');
    Route::delete('/{coach}', [Admin\CoachManagementController::class, 'destroy'])->name('destroy');
});

// ── Class Schedules ───────────────────────────────────────────────────────────
Route::prefix('schedules')->name('schedules.')->group(function () {
    Route::get('/', [Admin\ClassScheduleController::class, 'index'])->name('index');
    Route::get('/create', [Admin\ClassScheduleController::class, 'create'])->name('create');
    Route::post('/', [Admin\ClassScheduleController::class, 'store'])->name('store');
    Route::get('/{schedule}/edit', [Admin\ClassScheduleController::class, 'edit'])->name('edit');
    Route::put('/{schedule}', [Admin\ClassScheduleController::class, 'update'])->name('update');
    Route::delete('/{schedule}', [Admin\ClassScheduleController::class, 'destroy'])->name('destroy');
    Route::post('/types', [Admin\ClassScheduleController::class, 'storeType'])->name('store-type');
    Route::delete('/types/{classType}', [Admin\ClassScheduleController::class, 'destroyType'])->name('destroy-type');
});

// ── Class Types ───────────────────────────────────────────────────────────────
Route::prefix('class-types')->name('class-types.')->group(function () {
    Route::get('/', [Admin\ClassScheduleController::class, 'indexTypes'])->name('index');
    Route::post('/', [Admin\ClassScheduleController::class, 'storeType'])->name('store');
    Route::put('/{classType}', [Admin\ClassScheduleController::class, 'updateType'])->name('update');
    Route::delete('/{classType}', [Admin\ClassScheduleController::class, 'destroyType'])->name('destroy');
});

// ── Membership Packages ───────────────────────────────────────────────────────
Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('/', [Admin\PackageController::class, 'index'])->name('index');
    Route::post('/', [Admin\PackageController::class, 'store'])->name('store');
    Route::put('/{package}', [Admin\PackageController::class, 'update'])->name('update');
    Route::delete('/{package}', [Admin\PackageController::class, 'destroy'])->name('destroy');
});

// ── Product Sales ─────────────────────────────────────────────────────────────
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [Admin\ProductSaleController::class, 'index'])->name('index');
    Route::get('/create', [Admin\ProductSaleController::class, 'create'])->name('create');
    Route::post('/', [Admin\ProductSaleController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [Admin\ProductSaleController::class, 'edit'])->name('edit');
    Route::put('/{product}', [Admin\ProductSaleController::class, 'update'])->name('update');
    Route::delete('/{product}', [Admin\ProductSaleController::class, 'destroy'])->name('destroy');
    Route::post('/sell', [Admin\ProductSaleController::class, 'sell'])->name('sell');
    Route::get('/history', [Admin\ProductSaleController::class, 'history'])->name('history');
});

// ── Payroll ───────────────────────────────────────────────────────────────────
Route::prefix('payroll')->name('payroll.')->group(function () {
    Route::get('/', [Admin\PayrollController::class, 'index'])->name('index');
    Route::post('/generate', [Admin\PayrollController::class, 'generate'])->name('generate');
    Route::post('/{payroll}/mark-paid', [Admin\PayrollController::class, 'markPaid'])->name('mark-paid');
    Route::get('/history', [Admin\PayrollController::class, 'history'])->name('history');
});

// ── Landing Page CMS ──────────────────────────────────────────────────────────
Route::prefix('landing-content')->name('landing-content.')->group(function () {
    Route::get('/', [Admin\LandingPageContentController::class, 'index'])->name('index');
    Route::post('/', [Admin\LandingPageContentController::class, 'store'])->name('store');
    Route::put('/{content}', [Admin\LandingPageContentController::class, 'update'])->name('update');
    Route::delete('/{content}', [Admin\LandingPageContentController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [Admin\LandingPageContentController::class, 'reorder'])->name('reorder');
});
