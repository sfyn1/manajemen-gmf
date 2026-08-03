<?php

namespace App\Console\Commands;

use App\Models\AttendanceVerification;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateVerifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmf:generate-verifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate attendance verification records for today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai generate verifikasi kehadiran...');

        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));

        // Ambil semua jadwal yang aktif hari ini
        $schedules = ClassSchedule::active()->where('day_of_week', $dayOfWeek)->get();
        $count = 0;

        foreach ($schedules as $schedule) {
            $exists = AttendanceVerification::where('coach_id', $schedule->coach_id)
                ->where('class_schedule_id', $schedule->id)
                ->where('session_date', $today)
                ->exists();

            if (!$exists) {
                AttendanceVerification::create([
                    'coach_id' => $schedule->coach_id,
                    'class_schedule_id' => $schedule->id,
                    'session_date' => $today,
                    'status' => AttendanceVerification::STATUS_PENDING,
                ]);
                $count++;
            }
        }

        // Cari yang kemarin masih pending dan auto-fail-kan
        $yesterday = Carbon::yesterday();
        $expired = AttendanceVerification::where('session_date', '<=', $yesterday)
            ->where('status', AttendanceVerification::STATUS_PENDING)
            ->whereNull('photo_path')
            ->update([
                'status' => AttendanceVerification::STATUS_AUTO_FAILED,
                'coach_notes' => 'Tidak submit bukti kehadiran tepat waktu.',
            ]);

        $this->info("Berhasil membuat {$count} record verifikasi baru untuk hari ini.");
        $this->info("Menandai {$expired} record kemarin sebagai auto-failed.");
    }
}
