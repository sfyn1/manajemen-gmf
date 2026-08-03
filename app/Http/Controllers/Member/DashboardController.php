<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use App\Models\Visit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        if (! $member) {
            abort(403, 'Data member tidak ditemukan.');
        }

        $member->load(['package']);

        // QR Code generate
        $qrCode = null;
        if ($member->status === \App\Models\Member::STATUS_ACTIVE && $member->qr_token) {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate(route('qr.verify', $member->qr_token));
        }

        $daysLeft        = $member->membership_end_date ? now()->diffInDays($member->membership_end_date, false) : null;
        $totalVisits     = $member->visits()->count();
        $monthVisits     = $member->visits()->whereMonth('visited_at', now()->month)->count();
        $activeBookings  = $member->bookings()->upcoming()->count();
        $upcomingBookings = $member->bookings()->upcoming()
            ->with(['schedule.classType', 'schedule.coach'])
            ->orderBy('booking_date')
            ->limit(3)
            ->get();
        $recentVisits    = $member->visits()->latest('visited_at')->limit(5)->get();

        $packages        = \App\Models\MembershipPackage::active()->get();
        $pendingRenewal  = $member->latestPendingRenewal;

        return view('member.dashboard', compact(
            'member', 'qrCode', 'daysLeft', 'totalVisits', 'monthVisits',
            'activeBookings', 'upcomingBookings', 'recentVisits', 'packages', 'pendingRenewal'
        ));
    }
}

