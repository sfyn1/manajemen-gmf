<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceVerification;
use App\Models\Coach;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $coaches = Coach::with('user')->where('is_active', true)->get();
        $month   = (int) request('month', now()->month);
        $year    = (int) request('year', now()->year);

        $payrolls = Payroll::with('coach.user')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $summary = [
            'total_coaches'  => $payrolls->count(),
            'total_sessions' => $payrolls->sum('total_sessions'),
            'total_amount'   => $payrolls->sum('total_amount'),
        ];

        return view('admin.payroll.index', compact('coaches', 'payrolls', 'summary', 'month', 'year'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year'  => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        $coaches  = Coach::where('is_active', true)->get();
        $created  = 0;
        $skipped  = 0;

        foreach ($coaches as $coach) {
            $sessions = AttendanceVerification::approved()
                ->where('coach_id', $coach->id)
                ->whereMonth('session_date', $data['month'])
                ->whereYear('session_date', $data['year'])
                ->count();

            $total = $sessions * $coach->rate_per_session;

            $payroll = Payroll::firstOrCreate(
                ['coach_id' => $coach->id, 'month' => $data['month'], 'year' => $data['year']],
                [
                    'total_sessions'   => $sessions,
                    'rate_per_session' => $coach->rate_per_session,
                    'total_amount'     => $total,
                    'status'           => 'pending',
                ]
            );

            if ($payroll->wasRecentlyCreated) {
                $created++;
            } else {
                $payroll->update([
                    'total_sessions'   => $sessions,
                    'rate_per_session' => $coach->rate_per_session,
                    'total_amount'     => $total,
                ]);
                $skipped++;
            }
        }

        return redirect()->route('admin.payroll.index', ['month' => $data['month'], 'year' => $data['year']])
            ->with('success', "Payroll generated: {$created} baru dibuat, {$skipped} diperbarui.");
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update([
            'status'  => 'paid',
            'paid_at' => now(),
            'paid_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Payroll {$payroll->coach->user->name} ditandai lunas.");
    }

    public function history()
    {
        $payrolls = Payroll::with('coach.user')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(20);

        return view('admin.payroll.history', compact('payrolls'));
    }
}
