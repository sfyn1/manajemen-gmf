<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipRenewal;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    public function store(Request $request, MidtransService $midtransService)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        // Cek jika ada pengajuan pending
        $hasPending = MembershipRenewal::where('member_id', $member->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return redirect()->back()->with('error', 'Pengajuan perpanjangan membership Anda sebelumnya masih dalam proses verifikasi admin.');
        }

        $validated = $request->validate([
            'membership_package_id' => ['required', 'exists:membership_packages,id'],
            'payment_method'        => ['required', 'in:midtrans,qris,cash'],
            'payment_amount'        => ['required', 'numeric', 'min:0'],
            'payment_proof'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $package   = MembershipPackage::findOrFail($validated['membership_package_id']);
        $isOnline  = in_array($validated['payment_method'], ['midtrans', 'qris']);
        $method    = $isOnline ? 'qris' : 'cash';
        $orderId   = 'GMF-RNW-' . date('Ymd') . '-' . rand(1000, 9999);
        $snapToken = null;

        if ($isOnline) {
            try {
                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => (int) $package->price,
                    ],
                    'customer_details' => [
                        'first_name' => $member->full_name,
                        'email'      => $member->email,
                        'phone'      => $member->phone ?? '08123456789',
                    ],
                    'item_details' => [
                        [
                            'id'       => 'RNW-PKG-' . $package->id,
                            'price'    => (int) $package->price,
                            'quantity' => 1,
                            'name'     => substr('Perpanjangan Paket ' . $package->name, 0, 50),
                        ]
                    ]
                ];
                $snapToken = $midtransService->createSnapToken($params);
            } catch (\Throwable $e) {
                \Log::error('Midtrans Renewal Snap Token Exception: ' . $e->getMessage());
                $snapToken = null;
            }
        }

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('renewals', 'public');
        } elseif ($isOnline) {
            $proofPath = 'renewals/midtrans_sandbox_auto.png';
        }

        $renewal = MembershipRenewal::create([
            'member_id'             => $member->id,
            'membership_package_id' => $validated['membership_package_id'],
            'payment_method'        => $method,
            'payment_proof_path'    => $proofPath,
            'payment_amount'        => $validated['payment_amount'],
            'status'                => 'pending',
            'order_id'              => $orderId,
            'snap_token'            => $snapToken,
            'payment_type'          => $isOnline ? 'midtrans_snap' : 'cash',
            'payment_status'        => 'pending',
        ]);

        if ($snapToken) {
            session(['rnw_snap_token' => $snapToken]);
            return redirect()->back()->with('success', 'Silakan selesaikan pembayaran perpanjangan Anda via Midtrans Snap.');
        }

        return redirect()->back()->with('success', 'Pengajuan perpanjangan membership berhasil dikirim! Menunggu persetujuan admin.');
    }

    public function cancel(MembershipRenewal $renewal)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        if ($renewal->member_id !== $member->id) {
            abort(403);
        }

        if ($renewal->status === 'pending') {
            $renewal->delete();
            return redirect()->back()->with('success', 'Pengajuan perpanjangan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pengajuan ini tidak dapat dibatalkan.');
    }

    public function confirmSuccess(Request $request)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $orderId = $request->input('order_id');
        $renewal = MembershipRenewal::where('member_id', $member->id)
            ->where(function ($q) use ($orderId) {
                if ($orderId) {
                    $q->where('order_id', $orderId);
                } else {
                    $q->where('status', 'pending');
                }
            })
            ->latest()
            ->first();

        if ($renewal && $renewal->status === 'pending') {
            $renewal->update([
                'payment_status' => 'settlement',
                'status'         => 'approved',
                'processed_at'   => now(),
            ]);

            $package = $renewal->package;
            $durationDays = $package ? ($package->duration_days ?? 30) : 30;

            if ($member->status === Member::STATUS_ACTIVE && $member->membership_end_date && $member->membership_end_date->gte(now()->startOfDay())) {
                $newStartDate = $member->membership_start_date ?? now()->toDateString();
                $newEndDate   = $member->membership_end_date->addDays($durationDays)->toDateString();
            } else {
                $newStartDate = now()->toDateString();
                $newEndDate   = now()->addDays($durationDays)->toDateString();
            }

            $member->update([
                'membership_package_id' => $package ? $package->id : $member->membership_package_id,
                'status'                => Member::STATUS_ACTIVE,
                'membership_start_date'  => $newStartDate,
                'membership_end_date'    => $newEndDate,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Perpanjangan membership berhasil dikonfirmasi lunas.'
            ]);
        }

        return response()->json(['status' => 'ignored']);
    }
}
