<?php

namespace App\Console\Commands;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmf:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update expiring memberships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai pengecekan expiry membership...');

        $today = Carbon::today();

        // Cari yang sudah expired (tanggal berakhirnya kemarin)
        $expiredCount = Member::where('status', Member::STATUS_ACTIVE)
            ->whereNotNull('membership_end_date')
            ->whereDate('membership_end_date', '<', $today)
            ->update([
                'status' => Member::STATUS_EXPIRED,
            ]);

        $this->info("Menandai {$expiredCount} membership menjadi expired.");
    }
}
