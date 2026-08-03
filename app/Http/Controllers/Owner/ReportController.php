<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payroll;
use App\Models\ProductSale;
use App\Models\Visit;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom   = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo     = $request->date_to   ?? now()->format('Y-m-d');
        $reportType = $request->report_type ?? 'member';

        $data = $this->getData($reportType, $dateFrom, $dateTo);

        return view('owner.reports.index', compact('data', 'dateFrom', 'dateTo', 'reportType'));
    }

    public function download(Request $request)
    {
        $dateFrom   = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo     = $request->date_to   ?? now()->format('Y-m-d');
        $reportType = $request->report_type ?? 'member';

        $data = $this->getData($reportType, $dateFrom, $dateTo);
        $title = ucfirst($reportType) . ' — ' . $dateFrom . ' s.d. ' . $dateTo;

        return response()->streamDownload(function () use ($data, $title) {
            echo "Laporan {$title}\n\n";
            if ($data->isNotEmpty()) {
                echo implode(',', array_keys($data->first())) . "\n";
                foreach ($data as $row) {
                    echo implode(',', array_values($row)) . "\n";
                }
            }
        }, "laporan-{$reportType}-{$dateFrom}.csv", ['Content-Type' => 'text/csv']);
    }

    private function getData(string $type, string $from, string $to)
    {
        return match ($type) {
            'member' => Member::whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->with('package')
                ->get()
                ->map(fn($m) => [
                    'nama'      => $m->full_name,
                    'nik'       => $m->nik,
                    'paket'     => $m->package?->name,
                    'status'    => $m->status,
                    'tgl_daftar'=> $m->created_at->format('Y-m-d'),
                ]),
            'produk' => ProductSale::whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->with('product')
                ->get()
                ->map(fn($s) => [
                    'produk'    => $s->product?->name,
                    'qty'       => $s->qty,
                    'total'     => $s->total_price,
                    'metode'    => $s->payment_method,
                    'tanggal'   => $s->created_at->format('Y-m-d'),
                ]),
            'kunjungan' => Visit::whereBetween('visited_at', [$from, $to . ' 23:59:59'])
                ->with('member')
                ->get()
                ->map(fn($v) => [
                    'member'    => $v->member?->full_name,
                    'tanggal'   => $v->visited_at->format('Y-m-d'),
                    'jam'       => $v->visited_at->format('H:i'),
                ]),
            'payroll' => Payroll::with('coach.user')
                ->whereBetween('updated_at', [$from, $to . ' 23:59:59'])
                ->get()
                ->map(fn($p) => [
                    'coach'     => $p->coach->user->name,
                    'bulan'     => $p->month . '/' . $p->year,
                    'sesi'      => $p->total_sessions,
                    'total'     => $p->total_amount,
                    'status'    => $p->status,
                ]),
            default => collect(),
        };
    }
}
