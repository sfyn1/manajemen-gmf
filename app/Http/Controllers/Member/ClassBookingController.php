<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use App\Services\MidtransService;
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

        $pendingBooking = $member->bookings()
            ->where('payment_status', 'pending')
            ->where('status', ClassBooking::STATUS_BOOKED)
            ->latest()
            ->first();

        return view('member.booking.index', compact('schedules', 'myBookings', 'member', 'pendingBooking'));
    }

    public function book(Request $request, ClassSchedule $schedule, MidtransService $midtransService)
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
            ->where('payment_status', 'settlement')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah booking & membayar kelas ini untuk tanggal tersebut.');
        }

        // Cek kapasitas
        $booked = ClassBooking::where('class_schedule_id', $schedule->id)
            ->where('booking_date', $bookingDate)
            ->where('status', ClassBooking::STATUS_BOOKED)
            ->whereIn('payment_status', ['pending', 'settlement'])
            ->count();

        if ($booked >= $schedule->max_capacity) {
            return back()->with('error', 'Maaf, kuota kelas sudah penuh untuk tanggal tersebut.');
        }

        $orderId   = 'GMF-CBK-' . date('Ymd') . '-' . rand(1000, 9999);
        $amount    = (int) $schedule->session_fee;
        $snapToken = null;

        if ($amount > 0) {
            try {
                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => $amount,
                    ],
                    'customer_details' => [
                        'first_name' => $member->full_name,
                        'email'      => $member->email,
                        'phone'      => $member->phone ?? '08123456789',
                    ],
                    'item_details' => [
                        [
                            'id'       => 'CBK-' . $schedule->id,
                            'price'    => $amount,
                            'quantity' => 1,
                            'name'     => substr('Booking Kelas ' . $schedule->classType->name, 0, 50),
                        ]
                    ]
                ];
                $snapToken = $midtransService->createSnapToken($params);
            } catch (\Throwable $e) {
                \Log::error('Midtrans Class Booking Exception: ' . $e->getMessage());
                $snapToken = null;
            }
        }

        $booking = ClassBooking::updateOrCreate(
            [
                'member_id'        => $member->id,
                'class_schedule_id'=> $schedule->id,
                'booking_date'     => $bookingDate,
            ],
            [
                'status'              => ClassBooking::STATUS_BOOKED,
                'payment_amount'      => $schedule->session_fee,
                'order_id'            => $orderId,
                'snap_token'          => $snapToken,
                'payment_type'        => 'midtrans_snap',
                'payment_status'      => $amount > 0 ? 'pending' : 'settlement',
                'payment_confirmed'   => $amount > 0 ? false : true,
                'cancelled_at'        => null,
                'cancellation_reason' => null,
            ]
        );

        if ($snapToken) {
            session(['cbk_snap_token' => $snapToken]);
            return back()->with('success', "Silakan selesaikan pembayaran booking kelas {$schedule->classType->name} via Midtrans Snap.");
        }

        return back()->with('success', "Berhasil booking kelas {$schedule->classType->name}!");
    }

    public function confirmSuccess(Request $request)
    {
        $user = auth()->user();
        $member = $user?->member;

        if (! $member) {
            return response()->json(['status' => 'error', 'message' => 'Member not found'], 404);
        }

        $orderId = $request->input('order_id');
        $booking = ClassBooking::where('member_id', $member->id)
            ->where(function ($q) use ($orderId) {
                if ($orderId) {
                    $q->where('order_id', $orderId);
                } else {
                    $q->where('payment_status', 'pending');
                }
            })
            ->latest()
            ->first();

        if ($booking) {
            $booking->update([
                'payment_status'    => 'settlement',
                'payment_confirmed' => true,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Pembayaran booking kelas berhasil dikonfirmasi lunas.'
            ]);
        }

        return response()->json(['status' => 'ignored']);
    }

    public function cancel(ClassBooking $booking, MidtransService $midtransService)
    {
        $member = auth()->user()->member;

        if ($booking->member_id !== $member->id) {
            abort(403);
        }

        if (! $booking->isCancellable()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan (sudah lewat atau bukan status booked).');
        }

        $refundMsg = '';
        if ($booking->order_id && $booking->payment_status === 'settlement' && $booking->payment_amount > 0) {
            $refundResult = $midtransService->refundTransaction($booking->order_id, $booking->payment_amount, 'Dibatalkan oleh member');
            if ($refundResult['success']) {
                $refundMsg = ' Uang pendaftaran kelas sebesar Rp ' . number_format($booking->payment_amount, 0, ',', '.') . ' telah dikembalikan otomatis via Midtrans Refund.';
            }
        }

        $booking->update([
            'status'              => ClassBooking::STATUS_CANCELLED,
            'payment_status'      => $booking->payment_status === 'settlement' ? 'refunded' : 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => 'Dibatalkan oleh member',
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.' . $refundMsg);
    }
}
