<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassBookingController extends Controller
{
    public function index()
    {
        $member   = auth()->user()->member;
        $dayMap   = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];

        $today = now();

        // Jadwal yang tersedia (next 7 hari)
        $schedules = ClassSchedule::active()
            ->with(['classType', 'coach'])
            ->get()
            ->map(function ($schedule) use ($today) {
                $nextDate = $schedule->calculateNextDate($today);
                $schedule->next_date = $nextDate;
                $schedule->next_date_label = Carbon::parse($nextDate)->translatedFormat('l, d M Y');
                return $schedule;
            })
            ->sortBy('next_date');

        // Booking aktif member
        $myBookings = $member->bookings()
            ->upcoming()
            ->with(['schedule.classType', 'schedule.coach'])
            ->orderBy('booking_date')
            ->get();

        return view('member.booking.index', compact('schedules', 'myBookings', 'member'));
    }

    public function book(Request $request, ClassSchedule $schedule)
    {
        $member = auth()->user()->member;

        // Hanya member bulanan yang bisa booking
        if (! $member->isMonthlyMember()) {
            return back()->with('error', 'Paket Harian tidak dapat booking kelas grup.');
        }

        $bookingDate = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
        ])['booking_date'];

        // Cek duplikasi booking
        $exists = ClassBooking::where('member_id', $member->id)
            ->where('class_schedule_id', $schedule->id)
            ->where('booking_date', $bookingDate)
            ->whereIn('status', [ClassBooking::STATUS_BOOKED, ClassBooking::STATUS_ATTENDED])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah booking kelas ini untuk tanggal tersebut.');
        }

        // Cek kapasitas
        $booked = ClassBooking::where('class_schedule_id', $schedule->id)
            ->where('booking_date', $bookingDate)
            ->where('status', ClassBooking::STATUS_BOOKED)
            ->count();

        if ($booked >= $schedule->max_capacity) {
            return back()->with('error', 'Maaf, kuota kelas sudah penuh untuk tanggal tersebut.');
        }

        ClassBooking::updateOrCreate(
            [
                'member_id'        => $member->id,
                'class_schedule_id'=> $schedule->id,
                'booking_date'     => $bookingDate,
            ],
            [
                'status'              => ClassBooking::STATUS_BOOKED,
                'payment_amount'      => $schedule->session_fee,
                'cancelled_at'        => null,
                'cancellation_reason' => null,
            ]
        );

        return back()->with('success', "Berhasil booking kelas {$schedule->classType->name}! Bayar di tempat saat hadir.");
    }

    public function cancel(ClassBooking $booking)
    {
        $member = auth()->user()->member;

        if ($booking->member_id !== $member->id) {
            abort(403);
        }

        if (! $booking->isCancellable()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan (sudah lewat atau bukan status booked).');
        }

        $booking->update([
            'status'              => ClassBooking::STATUS_CANCELLED,
            'cancelled_at'        => now(),
            'cancellation_reason' => 'Dibatalkan oleh member',
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
