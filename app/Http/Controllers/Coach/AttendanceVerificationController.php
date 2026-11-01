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
                ->where('payment_status', 'settlement')
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
                ->where('payment_status', 'settlement')
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

        // Status ditolak dapat diunggah ulang selama belum melewati deadline SOP.
        abort_unless(in_array($verification->status, [AttendanceVerification::STATUS_PENDING, AttendanceVerification::STATUS_REJECTED], true), 403);

        // Validasi SOP: Upload bukti maksimal 1 jam setelah kelas selesai
        if ($verification->isPastDeadline()) {
            $verification->update([
                'status'           => AttendanceVerification::STATUS_AUTO_FAILED,
                'rejection_reason' => 'Batas waktu verifikasi kehadiran telah berakhir (Maksimal 1 jam setelah kelas selesai). Status otomatis dibatalkan.',
            ]);

            return redirect()->route('coach.attendance.index')->with('error', 'Gagal: Batas waktu unggah bukti telah berakhir (Maksimal 1 jam setelah kelas selesai). Presensi sesi ini otomatis gagal.');
        }

        $request->validate([
            'photo_proof' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        // Camera capture dikirim sebagai JPEG/PNG; dokumen dan format non-image ditolak.
        $imageInfo = @getimagesize($request->file('photo_proof')->getRealPath());
        abort_unless($imageInfo !== false, 422, 'Bukti harus berupa gambar kamera yang valid.');

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

        return redirect()->route('coach.attendance.index')->with('success', 'Verifikasi kehadiran berhasil disubmit tepat waktu. Menunggu review admin.');
    }

    public function resubmit(AttendanceVerification $verification, Request $request)
    {
        abort_unless($verification->status === AttendanceVerification::STATUS_REJECTED, 403);
        return $this->submit($verification, $request);
    }
}
