<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Daftar semua member dengan filter status & pencarian nama/NIK.
     */
    public function index(Request $request)
    {
        // Auto sync expired members
        Member::where('status', Member::STATUS_ACTIVE)
            ->whereNotNull('membership_end_date')
            ->where('membership_end_date', '<', now()->startOfDay())
            ->update(['status' => Member::STATUS_EXPIRED]);

        $query = Member::with('package')
            ->orderByDesc('created_at');

        // Filter by status
        $status = $request->input('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search by name or NIK
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all'                  => Member::count(),
            'active'               => Member::where('status', 'active')->count(),
            'pending_verification' => Member::where('status', 'pending_verification')->count(),
            'rejected'             => Member::where('status', 'rejected')->count(),
            'expired'              => Member::where('status', 'expired')->count(),
        ];

        return view('admin.membership.index', compact('members', 'status', 'statusCounts'));
    }

    /**
     * Detail satu member beserta dokumen & riwayat kunjungan.
     */
    public function show(Member $member)
    {
        $member->load(['package', 'documents', 'user']);
        $visits = $member->visits()
            ->orderByDesc('visited_at')
            ->take(10)
            ->get();

        return view('admin.membership.show', compact('member', 'visits'));
    }

    /**
     * Form edit data member.
     */
    public function edit(Member $member)
    {
        $member->load(['package', 'user']);
        $packages = MembershipPackage::active()->orderBy('name')->get();

        return view('admin.membership.edit', compact('member', 'packages'));
    }

    /**
     * Simpan perubahan data member (paket, tanggal, status).
     */
    public function update(Member $member, Request $request)
    {
        $validated = $request->validate([
            'membership_package_id' => 'required|exists:membership_packages,id',
            'membership_start_date' => 'nullable|date',
            'membership_end_date'   => 'nullable|date|after_or_equal:membership_start_date',
            'status'                => 'required|in:pending_verification,active,rejected,expired',
            'rejection_reason'      => 'nullable|string|max:500',
        ]);

        $member->update($validated);

        return redirect()
            ->route('admin.membership.show', $member)
            ->with('success', 'Data member berhasil diperbarui.');
    }
}
