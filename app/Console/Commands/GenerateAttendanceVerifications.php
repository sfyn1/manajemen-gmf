<?php

namespace App\Console\Commands;

use App\Models\AttendanceVerification;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAttendanceVerifications extends Command
{
    protected $signature   = 'gmf:generate-verifications';
    protected $description = 'Generate attendance verification records for all class schedules today';

    public function handle(): void
    {
        $today     = now();
        $dayMap    = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];
        $todayDay  = strtolower($today->englishDayOfWeek);

        $schedules = ClassSchedule::active()
            ->where('day_of_week', $todayDay)
            ->with('coach')
            ->get();

        $count = 0;
        foreach ($schedules as $schedule) {
            $verification = AttendanceVerification::firstOrCreate(
                ['class_schedule_id' => $schedule->id, 'session_date' => $today->toDateString()],
                ['coach_id' => $schedule->coach_id, 'status' => 'pending']
            );

            if ($verification->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->info("Generated {$count} attendance verification records for {$today->toDateString()}.");
    }
}
