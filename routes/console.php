<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\ClassBooking;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    ClassBooking::markCompletedSessions();
})->hourly()->name('mark-completed-class-bookings')->withoutOverlapping()->description('Mark completed class bookings as finished');

// ── Scheduled Tasks ───────────────────────────────────────────────────────
Schedule::command('gmf:generate-verifications')
    ->hourly()
    ->withoutOverlapping()
    ->description('Generate attendance verification records and expire overdue attendance uploads');

Schedule::command('gmf:check-expiry')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->description('Check and notify expiring memberships');

