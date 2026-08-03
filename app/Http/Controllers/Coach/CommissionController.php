<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\AttendanceVerification;
use App\Models\Coach;
use App\Models\Payroll;

class CommissionController extends Controller
{
    public function index()
    {
        $coach = Coach::where('user_id', auth()->id())->firstOrFail();

        // 1. Sesi yang sudah disetujui admin untuk bulan ini
        $thisMonthApproved = AttendanceVerification::where('coach_id', $coach->id)
            ->approved()
            ->whereMonth('session_date', now()->month)
            ->whereYear('session_date', now()->year)
            ->with(['schedule.classType'])
            ->get();

        $thisMonthApprovedCount = $thisMonthApproved->count();

        // 2. Estimasi komisi bulan ini
        $estimatedCommissionThisMonth = $thisMonthApproved->sum(function ($item) use ($coach) {
            return $item->commission_amount > 0 ? $item->commission_amount : ($coach->rate_per_session ?? 100000);
        });

        // 3. Status payroll bulan ini jika sudah dicairkan admin
        $payrollThisMonth = Payroll::where('coach_id', $coach->id)
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        // 4. Riwayat payroll resmi dari admin
        $payrolls = Payroll::where('coach_id', $coach->id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        // 5. Semua rincian sesi mengajar yang sudah disetujui
        $approvedSessions = AttendanceVerification::where('coach_id', $coach->id)
            ->approved()
            ->with(['schedule.classType'])
            ->orderByDesc('session_date')
            ->get();

        return view('coach.commission.index', compact(
            'coach',
            'payrolls',
            'thisMonthApprovedCount',
            'estimatedCommissionThisMonth',
            'payrollThisMonth',
            'approvedSessions'
        ));
    }
}
