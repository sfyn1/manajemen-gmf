<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\AttendanceVerification;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\ClassBooking;

class AttendanceVerificationController extends Controller
{
    public function index()
    {
        // 1. Sync & buat otomatis tugas verifikasi kehadiran untuk kelas yang sudah lewat / hari ini
        AttendanceVerification::syncFromBookings();

        $coach = Coach::where('user_id', auth()->id())->firstOrFail();

        $todo   = AttendanceVerification::where('coach_id', $coach->id)
            ->whereIn('status', [AttendanceVerification::STATUS_PENDING, AttendanceVerification::STATUS_REJECTED])
            ->where(fn($q) => $q->whereNull('submitted_at')->orWhere('status', 'rejected'))
            ->with(['schedule.classType'])
            ->orderByDesc('session_date')
            ->get();

        // Attach daftar member booking ke tiap tugas todo
        $todo->each(function ($item) {
            $item->bookings = ClassBooking::where('class_schedule_id', $item->class_schedule_id)
                ->where('booking_date', $item->session_date->format('Y-m-d'))
                ->whereIn('status', [ClassBooking::STATUS_BOOKED, ClassBooking::STATUS_ATTENDED])
                ->with('member')
                ->get();
        });

        $review = AttendanceVerification::where('coach_id', $coach->id)
            ->where('status', AttendanceVerification::STATUS_PENDING)
            ->whereNotNull('submitted_at')
            ->with(['schedule.classType'])
            ->orderByDesc('submitted_at')
            ->get();

        $review->each(function ($item) {
            $item->bookings = ClassBooking::where('class_schedule_id', $item->class_schedule_id)
                ->where('booking_date', $item->session_date->format('Y-m-d'))
                ->whereIn('status', [ClassBooking::STATUS_BOOKED, ClassBooking::STATUS_ATTENDED])
                ->with('member')
                ->get();
        });

        $done = AttendanceVerification::where('coach_id', $coach->id)
            ->whereIn('status', [AttendanceVerification::STATUS_APPROVED, AttendanceVerification::STATUS_AUTO_FAILED])
            ->with(['schedule.classType'])
            ->orderByDesc('session_date')
            ->limit(30)
            ->get();

        return view('coach.attendance.index', compact('coach', 'todo', 'review', 'done'));
    }

    public function submit(AttendanceVerification $verification, Request $request)
    {
        abort_unless($verification->coach_id === Coach::where('user_id', auth()->id())->value('id'), 403);

        $request->validate([
            'photo_proof' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        if ($verification->photo_path) {
            Storage::disk('public')->delete($verification->photo_path);
        }

        $path = $request->file('photo_proof')->store('verification-photos', 'public');

        $verification->update([
            'status'       => AttendanceVerification::STATUS_PENDING,
            'photo_path'   => $path,
            'submitted_at' => now(),
            'coach_notes'  => $request->notes,
        ]);

        return redirect()->route('coach.attendance.index')->with('success', 'Verifikasi kehadiran berhasil disubmit. Menunggu review admin.');
    }

    public function resubmit(AttendanceVerification $verification, Request $request)
    {
        abort_unless($verification->status === AttendanceVerification::STATUS_REJECTED, 403);
        return $this->submit($verification, $request);
    }
}
