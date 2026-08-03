@extends('layouts.coach')
@section('title', 'Komisi Mengajar — Gintung Master Fitness')
@section('page-title', 'Komisi Mengajar')
@section('page-subtitle', 'Ringkasan komisi sesi mengajar dan riwayat pencairan payroll Anda')

@section('content')

{{-- Stat Cards Bulan Ini --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Sesi Disetujui Bulan Ini</p>
        <p class="text-3xl font-extrabold text-slate-900 font-display">{{ $thisMonthApprovedCount }} Sesi</p>
        <p class="text-xs text-emerald-600 font-medium mt-2 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Telah diverifikasi admin</span>
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Rate per Sesi</p>
        <p class="text-3xl font-extrabold text-slate-900 font-display">Rp {{ number_format($coach->rate_per_session, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 font-medium mt-2">Tarif dasar pengajaran per kelas</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Estimasi Komisi Bulan Ini</p>
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-slate-900">
                Rp {{ number_format($estimatedCommissionThisMonth, 0, ',', '.') }}
            </p>
        </div>
        <p class="text-xs text-emerald-600 font-semibold mt-3 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>{{ $payrollThisMonth?->status === 'paid' ? 'Sudah Dibayarkan Admin' : 'Siap Dicairkan (Pending Payroll)' }}</span>
        </p>
    </div>
</div>

{{-- 1. Rincian Sesi Mengajar Disetujui Bulan Ini --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-900 font-display text-base">Rincian Sesi Mengajar Disetujui</h3>
            <p class="text-xs text-slate-500">Daftar presensi kelas yang sudah terverifikasi dan menghasilkan komisi</p>
        </div>
        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-bold text-xs rounded-full">
            {{ $approvedSessions->count() }} Sesi Terverifikasi
        </span>
    </div>

    @if($approvedSessions->isEmpty())
    <div class="p-8 text-center text-slate-400 text-xs font-medium">
        Belum ada sesi mengajar yang disetujui admin untuk periode ini.
    </div>
    @else
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold"><tr>
                <th class="px-6 py-4">Nama Kelas</th>
                <th class="px-6 py-4">Tanggal Sesi</th>
                <th class="px-6 py-4">Nominal Komisi</th>
                <th class="px-6 py-4 text-right">Status Verification</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($approvedSessions as $s)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-900 font-display">{{ $s->schedule?->classType?->name ?? 'Kelas Mengajar' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $s->session_date->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 font-bold text-emerald-600">
                        Rp {{ number_format($s->commission_amount > 0 ? $s->commission_amount : $coach->rate_per_session, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Disetujui Admin
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- 2. Riwayat Pencairan Payroll --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="font-bold text-slate-900 font-display text-base">Riwayat Pencairan Payroll Admin</h3>
        <p class="text-xs text-slate-500">Catatan komisi bulanan yang telah resmi dicairkan oleh pihak manajemen</p>
    </div>
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold"><tr>
                <th class="px-6 py-4">Bulan & Tahun</th>
                <th class="px-6 py-4">Total Sesi</th>
                <th class="px-6 py-4">Rate / Sesi</th>
                <th class="px-6 py-4">Total Pembayaran</th>
                <th class="px-6 py-4 text-right">Status Payroll</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($payrolls as $p)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-900 font-display">{{ \Carbon\Carbon::create()->month($p->month)->translatedFormat('F') }} {{ $p->year }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $p->total_sessions }} Sesi</td>
                    <td class="px-6 py-4 text-slate-400">Rp {{ number_format($coach->rate_per_session, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-bold text-slate-900 font-display">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($p->status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Lunas Dibayar
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Proses Payroll
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">Belum ada riwayat pencairan payroll bulanan dari admin.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
