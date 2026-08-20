<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberApprovedMail;
use App\Mail\MemberRejectedMail;
use App\Models\AttendanceVerification;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\MembershipRenewal;

class ApprovalCenterController extends Controller
{
    public function index()
    {
        AttendanceVerification::syncFromBookings();

        $pendingMembers        = Member::pending()
            ->whereNotNull('order_id')
            ->where('payment_status', 'settlement')
            ->with(['package', 'latestDocument'])
            ->latest()
            ->get();
        $pendingRenewals       = MembershipRenewal::pending()
            ->where(function ($q) {
                $q->where('payment_method', 'cash')
                  ->orWhere('payment_status', 'settlement');
            })
            ->with(['member', 'package'])
            ->latest()
            ->get();
        $pendingVerifications  = AttendanceVerification::pending()
            ->whereNotNull('submitted_at')
            ->with(['coach.user', 'schedule.classType'])
            ->latest('submitted_at')
            ->get();

        $historyRenewals       = MembershipRenewal::whereIn('status', ['approved', 'rejected'])
            ->with(['member', 'package', 'processor'])
            ->latest('processed_at')
            ->take(50)
            ->get();

        $historyMembers        = Member::whereIn('status', ['active', 'rejected'])
            ->with(['package', 'latestDocument'])
            ->latest('updated_at')
            ->take(50)
            ->get();

        return view('admin.approval.index', compact(
            'pendingMembers',
            'pendingRenewals',
            'pendingVerifications',
            'historyRenewals',
            'historyMembers'
        ));
    }

    public function approveMember(Member $member)
    {
        $package = $member->package;
        $start   = now()->toDateString();
        $end     = now()->addDays($package->duration_days)->toDateString();

        // Buat atau update User account
        $tempPassword = 'gmf' . rand(100000, 999999);
        $user = User::where('email', $member->email)->first();

        if (!$user) {
            $user = User::create([
                'email'              => $member->email,
                'name'               => $member->full_name,
                'password'           => Hash::make($tempPassword),
                'role'               => 'member',
                'profile_photo_path' => $member->profile_photo_path,
            ]);
        } else {
            $tempPassword = null; // User sudah punya akun sebelumnya
            if ($member->profile_photo_path) {
                $user->update(['profile_photo_path' => $member->profile_photo_path]);
            }
        }

        // Update Member
        $member->update([
            'user_id'                => $user->id,
            'status'                 => Member::STATUS_ACTIVE,
            'membership_start_date'  => $start,
            'membership_end_date'    => $end,
            'qr_token'               => Str::random(48),
        ]);

        // Kirim email persetujuan
        if ($member->email) {
            Mail::to($member->email)->send(new MemberApprovedMail($member, $tempPassword));
        }

        return redirect()->route('admin.approval.index')
            ->with('success', "Member {$member->full_name} berhasil disetujui. Email notifikasi dikirim.");
    }

    public function rejectMember(Member $member, Request $request, \App\Services\MidtransService $midtransService)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $refundMsg = '';
        if ($member->order_id) {
            $amount = $member->latestDocument ? ($member->latestDocument->payment_amount ?? 0) : ($member->package ? $member->package->price : 0);
            if ($amount > 0) {
                $midtransService->refundTransaction($member->order_id, $amount, $request->rejection_reason);

                $member->update([
                    'payment_status' => 'refunded',
                    'refund_status'  => 'refunded',
                    'refund_amount'  => $amount,
                    'refund_reason'  => $request->rejection_reason,
                    'refunded_at'    => now(),
                ]);

                $refundMsg = " & Refund otomatis sebesar Rp " . number_format($amount, 0, ',', '.') . " berhasil diproses via Midtrans API";
            }
        }

        $member->update([
            'status'           => Member::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        if ($member->email) {
            Mail::to($member->email)->queue(new MemberRejectedMail($member, $request->rejection_reason));
        }

        return redirect()->route('admin.approval.index')
            ->with('success', "Pendaftaran {$member->full_name} ditolak{$refundMsg}. Member dapat mendaftar ulang.");
    }

    public function approveVerification(AttendanceVerification $verification)
    {
        $rate = $verification->coach?->rate_per_session ?: ($verification->schedule?->session_fee ?? 100000);

        $verification->update([
            'status'            => AttendanceVerification::STATUS_APPROVED,
            'commission_amount' => $rate,
            'reviewed_at'       => now(),
            'reviewed_by'       => auth()->id(),
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', 'Verifikasi kehadiran coach disetujui.');
    }

    public function rejectVerification(AttendanceVerification $verification, Request $request)
    {
        $request->validate(['rejection_notes' => ['required', 'string']]);

        $verification->update([
            'status'           => AttendanceVerification::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_notes,
            'reviewed_at'      => now(),
            'reviewed_by'      => auth()->id(),
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', 'Verifikasi kehadiran ditolak. Coach dapat resubmit.');
    }

    public function approveRenewal(MembershipRenewal $renewal)
    {
        $renewal->update([
            'status'       => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        $member  = $renewal->member;
        $package = $renewal->package;

        $durationDays = $package->duration_days;

        if ($member->status === Member::STATUS_ACTIVE && $member->membership_end_date && $member->membership_end_date->gte(now()->startOfDay())) {
            $newStartDate = $member->membership_start_date ?? now()->toDateString();
            $newEndDate   = $member->membership_end_date->addDays($durationDays)->toDateString();
        } else {
            $newStartDate = now()->toDateString();
            $newEndDate   = now()->addDays($durationDays)->toDateString();
        }

        $member->update([
            'membership_package_id' => $package->id,
            'status'                => Member::STATUS_ACTIVE,
            'membership_start_date'  => $newStartDate,
            'membership_end_date'    => $newEndDate,
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', "Perpanjangan membership {$member->full_name} berhasil disetujui hingga " . date('d M Y', strtotime($newEndDate)) . ".");
    }

    public function rejectRenewal(MembershipRenewal $renewal, Request $request, \App\Services\MidtransService $midtransService)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
            'refund_notes'     => ['nullable', 'string', 'max:500'],
        ]);

        $refundMsg = '';
        if ($renewal->order_id && $renewal->payment_amount > 0) {
            $amount = $renewal->payment_amount;
            $midtransService->refundTransaction($renewal->order_id, $amount, $request->rejection_reason);

            $renewal->update([
                'payment_status' => 'refunded',
                'refund_amount'  => $amount,
                'refund_reason'  => $request->rejection_reason,
                'refunded_at'    => now(),
            ]);

            $refundMsg = " & Refund otomatis sebesar Rp " . number_format($amount, 0, ',', '.') . " berhasil diproses via Midtrans API";
        }

        $renewal->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'refund_notes'     => $request->refund_notes,
            'processed_by'     => auth()->id(),
            'processed_at'     => now(),
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', "Pengajuan perpanjangan membership {$renewal->member->full_name} ditolak{$refundMsg}.");
    }
}
