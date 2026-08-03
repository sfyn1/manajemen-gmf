<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use App\Models\Member;
use App\Models\MembershipDocument;
use App\Models\Payroll;
use App\Models\ProductSale;
use App\Models\Visit;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ── Member stats ─────────────────────────────────────────────────────
        $total_members   = Member::active()->count();
        $pending_members = Member::pending()->count();

        // ── Revenue bulan ini ─────────────────────────────────────────────────
        // Akumulasi: Pendaftaran Awal + Perpanjangan Approved + Penjualan Produk + Booking Kelas
        $revenue_products = ProductSale::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_price');

        $revenue_membership_initial = MembershipDocument::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('payment_amount');

        $revenue_membership_renewals = \App\Models\MembershipRenewal::approved()
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('payment_amount');

        $revenue_class_bookings = ClassBooking::whereIn('status', [ClassBooking::STATUS_BOOKED, ClassBooking::STATUS_ATTENDED])
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('payment_amount');

        $total_revenue_month = $revenue_products + $revenue_membership_initial + $revenue_membership_renewals + $revenue_class_bookings;

        // ── Kunjungan ─────────────────────────────────────────────────────────
        $total_visits_today = Visit::whereDate('visited_at', $now->toDateString())->count();

        $total_visits_week = Visit::whereBetween('visited_at', [
            $now->startOfWeek(Carbon::MONDAY)->toDateTimeString(),
            $now->copy()->endOfWeek(Carbon::SUNDAY)->toDateTimeString(),
        ])->count();

        // ── Booking kelas hari ini ─────────────────────────────────────────────
        // Cari jadwal yang hari_of_week-nya sesuai hari ini, lalu hitung booking
        $todayDayName = strtolower($now->englishDayOfWeek); // e.g. "wednesday"

        $bookings_today = ClassBooking::whereHas('schedule', function ($q) use ($todayDayName) {
            $q->where('day_of_week', $todayDayName);
        })
            ->whereDate('booking_date', $now->toDateString())
            ->count();

        // ── Member terbaru ────────────────────────────────────────────────────
        $recent_members = Member::with(['package', 'latestDocument'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Pending approvals ─────────────────────────────────────────────────
        $pending_approvals_count = $pending_members;

        // ── Payroll pending bulan ini ─────────────────────────────────────────
        $payroll_pending = Payroll::where('status', 'pending')
            ->where('year', $now->year)
            ->where('month', $now->month)
            ->count();

        // ── Jadwal kelas hari ini ─────────────────────────────────────────────
        $schedules_today = ClassSchedule::with(['classType', 'coach'])
            ->active()
            ->forDay($todayDayName)
            ->orderBy('start_time')
            ->get();

        return view('admin.dashboard', compact(
            'total_members',
            'pending_members',
            'total_revenue_month',
            'total_visits_today',
            'total_visits_week',
            'bookings_today',
            'recent_members',
            'pending_approvals_count',
            'payroll_pending',
            'schedules_today',
        ));
    }
}
