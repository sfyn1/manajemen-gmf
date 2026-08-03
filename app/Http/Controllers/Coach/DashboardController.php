<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\AttendanceVerification;
use App\Models\Coach;
use App\Models\ClassSchedule;

use App\Models\ClassBooking;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Sync & buat otomatis tugas verifikasi kehadiran untuk kelas yang sudah lewat / hari ini
        AttendanceVerification::syncFromBookings();

        $coach = Coach::where('user_id', auth()->id())->with('user')->firstOrFail();

        $pendingVerifications = AttendanceVerification::where('coach_id', $coach->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->with(['schedule.classType'])
            ->get();

        $thisMonthApproved = AttendanceVerification::where('coach_id', $coach->id)
            ->approved()
            ->whereMonth('session_date', now()->month)
            ->whereYear('session_date', now()->year)
            ->get();

        $payrollThisMonth = $coach->payrolls()
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        if ($payrollThisMonth) {
            $totalEarningsMonthAmount = $payrollThisMonth->total_amount;
            $payrollStatus = $payrollThisMonth->status;
        } else {
            $totalEarningsMonthAmount = $thisMonthApproved->sum(function($item) use ($coach) {
                return $item->commission_amount > 0 ? $item->commission_amount : ($coach->rate_per_session ?? 100000);
            });
            $payrollStatus = 'pending';
        }

        // Jadwal aktif coach ini (dengan hitungan peserta terdaftar)
        $today = now();

        $upcomingSchedules = ClassSchedule::active()
            ->where('coach_id', $coach->id)
            ->with('classType')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) use ($today) {
                $nextDate = $schedule->calculateNextDate($today);

                $schedule->next_date = $nextDate;
                $schedule->next_date_label = \Carbon\Carbon::parse($nextDate)->translatedFormat('l, d M Y');
                $schedule->bookings = ClassBooking::where('class_schedule_id', $schedule->id)
                    ->where('booking_date', $nextDate)
                    ->whereIn('status', ['booked', 'attended'])
                    ->with('member')
                    ->get();

                return $schedule;
            });

        return view('coach.dashboard', compact(
            'coach',
            'pendingVerifications',
            'thisMonthApproved',
            'totalEarningsMonthAmount',
            'payrollStatus',
            'upcomingSchedules'
        ));
    }
}
