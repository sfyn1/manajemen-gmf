<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\AttendanceVerification;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use Carbon\Carbon;

class RosterController extends Controller
{
    public function show(ClassSchedule $schedule, string $date)
    {
        $coach = auth()->user()->coach;

        if ($schedule->coach_id !== $coach->id) {
            abort(403, 'Bukan jadwal Anda.');
        }

        $parsedDate = Carbon::parse($date);

        // Ambil booking untuk jadwal+tanggal ini
        $bookings = ClassBooking::where('class_schedule_id', $schedule->id)
            ->where('booking_date', $parsedDate->toDateString())
            ->with('member')
            ->get();

        // Ambil atau buat attendance verification record
        $verification = AttendanceVerification::firstOrCreate([
            'class_schedule_id' => $schedule->id,
            'session_date'      => $parsedDate->toDateString(),
        ], [
            'coach_id'          => $coach->id,
            'status'            => 'pending',
        ]);

        $schedule->load('classType');

        return view('coach.roster.show', compact('schedule', 'parsedDate', 'bookings', 'verification'));
    }
}
