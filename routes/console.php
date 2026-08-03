<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled Tasks ───────────────────────────────────────────────────────
Schedule::command('gmf:generate-verifications')
    ->dailyAt('00:01')
    ->withoutOverlapping()
    ->description('Generate attendance verification records for today');

Schedule::command('gmf:check-expiry')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->description('Check and notify expiring memberships');

