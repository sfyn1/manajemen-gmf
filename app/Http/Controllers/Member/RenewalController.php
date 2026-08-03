<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipRenewal;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    public function store(Request $request)
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
            'payment_method'        => ['required', 'in:qris,cash'],
            'payment_amount'        => ['required', 'numeric', 'min:0'],
            'payment_proof'         => ['nullable', 'required_if:payment_method,qris', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('renewals', 'public');
        }

        MembershipRenewal::create([
            'member_id'             => $member->id,
            'membership_package_id' => $validated['membership_package_id'],
            'payment_method'        => $validated['payment_method'],
            'payment_proof_path'   => $proofPath,
            'payment_amount'        => $validated['payment_amount'],
            'status'                => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan perpanjangan membership berhasil dikirim! Menunggu persetujuan admin.');
    }
}
