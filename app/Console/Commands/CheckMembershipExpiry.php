<?php

namespace App\Console\Commands;

use App\Mail\MemberApprovedMail;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckMembershipExpiry extends Command
{
    protected $signature   = 'gmf:check-expiry';
    protected $description = 'Check membership expiry and send reminder emails (H-7, H-1), mark expired';

    public function handle(): void
    {
        $this->markExpired();
        $this->sendReminders();
    }

    private function markExpired(): void
    {
        $count = Member::active()
            ->whereNotNull('membership_end_date')
            ->where('membership_end_date', '<', now()->toDateString())
            ->update(['status' => Member::STATUS_EXPIRED]);

        $this->info("Marked {$count} memberships as expired.");
    }

    private function sendReminders(): void
    {
        // H-7 reminder
        $expiringSoon = Member::active()
            ->whereDate('membership_end_date', now()->addDays(7)->toDateString())
            ->get();

        foreach ($expiringSoon as $member) {
            if ($member->email) {
                \Illuminate\Support\Facades\Mail::to($member->email)
                    ->queue(new \App\Mail\MembershipReminderMail($member, 7));
            }
        }

        // H-1 reminder
        $expiringTomorrow = Member::active()
            ->whereDate('membership_end_date', now()->addDay()->toDateString())
            ->get();

        foreach ($expiringTomorrow as $member) {
            if ($member->email) {
                \Illuminate\Support\Facades\Mail::to($member->email)
                    ->queue(new \App\Mail\MembershipReminderMail($member, 1));
            }
        }

        $this->info('Sent expiry reminders.');
    }
}
