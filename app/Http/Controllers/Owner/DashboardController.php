<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipDocument;
use App\Models\Payroll;
use App\Models\ProductSale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenueMembershipInitial  = MembershipDocument::sum('payment_amount');
        $totalRevenueMembershipRenewals = \App\Models\MembershipRenewal::approved()->sum('payment_amount');
        $totalRevenueProduct            = ProductSale::sum('total_price');
        $totalRevenueClassBookings      = \App\Models\ClassBooking::whereIn('status', [\App\Models\ClassBooking::STATUS_BOOKED, \App\Models\ClassBooking::STATUS_ATTENDED])->sum('payment_amount');

        $totalRefundedInitial  = Member::where('payment_status', 'refunded')->sum('refund_amount');
        $totalRefundedRenewals = \App\Models\MembershipRenewal::where('payment_status', 'refunded')->sum('refund_amount');
        $totalRefundedAmount   = $totalRefundedInitial + $totalRefundedRenewals;

        $totalRevenue = max(0, ($totalRevenueMembershipInitial + $totalRevenueMembershipRenewals + $totalRevenueProduct + $totalRevenueClassBookings) - $totalRefundedAmount);
        $totalMembers           = Member::active()->count();
        $totalPayrollPaid       = Payroll::where('status', 'paid')->whereMonth('updated_at', now()->month)->sum('total_amount');
        $productSalesCount      = ProductSale::whereMonth('created_at', now()->month)->count();

        // 6 bulan terakhir
        $monthlyData = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return [
                'label' => $date->format('M'),
                'count' => Member::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
            ];
        })->toArray();

        $recentSales    = ProductSale::with('product')->latest()->limit(5)->get();
        $payrollSummary = Payroll::with('coach.user')
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get();

        return view('owner.dashboard', compact(
            'totalRevenue', 'totalMembers', 'totalPayrollPaid',
            'productSalesCount', 'monthlyData', 'recentSales', 'payrollSummary',
        ));
    }
}
