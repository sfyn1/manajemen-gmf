<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipRenewal;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, MidtransService $midtransService)
    {
        $payload = $request->all();
        \Log::info('Midtrans Webhook Notification Received:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (! $orderId || ! $transactionStatus) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Tentukan status pembayaran akhir
        $paymentStatus = match ($transactionStatus) {
            'capture' => ($fraudStatus === 'accept') ? 'settlement' : 'challenge',
            'settlement' => 'settlement',
            'pending' => 'pending',
            'deny' => 'deny',
            'expire' => 'expire',
            'cancel' => 'cancel',
            'refund', 'partial_refund' => 'refunded',
            default => $transactionStatus,
        };

        // 1. Cek di Tabel Members (Registrasi Baru)
        $member = Member::where('order_id', $orderId)->first();
        if ($member) {
            $member->update([
                'payment_status' => $paymentStatus,
                'payment_type'   => $paymentType ?? $member->payment_type,
            ]);

            if ($paymentStatus === 'refunded') {
                $member->update([
                    'refund_status' => 'refunded',
                    'refunded_at'   => Carbon::now(),
                ]);
            }

            return response()->json(['status' => 'success', 'model' => 'member']);
        }

        // 2. Cek di Tabel MembershipRenewals (Perpanjangan)
        $renewal = MembershipRenewal::where('order_id', $orderId)->first();
        if ($renewal) {
            $renewal->update([
                'payment_status' => $paymentStatus,
                'payment_type'   => $paymentType ?? $renewal->payment_type,
            ]);

            if ($paymentStatus === 'settlement' && $renewal->status === 'pending') {
                // Perpanjang otomatis tanggal kadaluarsa membership jika approved
                $renewal->update(['status' => 'approved', 'processed_at' => Carbon::now()]);
                
                $memberObj = $renewal->member;
                if ($memberObj) {
                    $package = $renewal->package;
                    $durationDays = $package ? ($package->duration_days ?? 30) : 30;
                    $currentEnd = ($memberObj->membership_end_date && $memberObj->membership_end_date->isFuture()) 
                        ? $memberObj->membership_end_date 
                        : Carbon::now();
                    
                    $memberObj->update([
                        'membership_package_id' => $package ? $package->id : $memberObj->membership_package_id,
                        'status'                => Member::STATUS_ACTIVE,
                        'membership_end_date'   => $currentEnd->copy()->addDays($durationDays)->toDateString(),
                    ]);
                }
            } elseif ($paymentStatus === 'refunded') {
                $renewal->update([
                    'status'      => 'rejected',
                    'refunded_at' => Carbon::now(),
                ]);
            }

            return response()->json(['status' => 'success', 'model' => 'renewal']);
        }

        // 3. Cek di Tabel ClassBookings (Booking Kelas)
        $booking = \App\Models\ClassBooking::where('order_id', $orderId)->first();
        if ($booking) {
            $booking->update([
                'payment_status'    => $paymentStatus,
                'payment_type'      => $paymentType ?? $booking->payment_type,
                'payment_confirmed' => $paymentStatus === 'settlement',
            ]);

            if ($paymentStatus === 'refunded' || $paymentStatus === 'cancel' || $paymentStatus === 'expire') {
                $booking->update([
                    'status'       => \App\Models\ClassBooking::STATUS_CANCELLED,
                    'cancelled_at' => Carbon::now(),
                ]);
            }

            return response()->json(['status' => 'success', 'model' => 'class_booking']);
        }

        return response()->json(['message' => 'Order ID not found'], 444);
    }
}
