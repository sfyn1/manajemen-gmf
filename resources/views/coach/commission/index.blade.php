@extends('layouts.coach')

@section('title', 'Komisi Mengajar — Gintung Master Fitness')
@section('page-title', 'Komisi Mengajar')
@section('page-subtitle', 'Ringkasan komisi sesi mengajar dan riwayat pencairan payroll Anda')

@section('content')

{{-- Stat Cards Bulan Ini --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Sesi Disetujui</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px] filled">task_alt</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $thisMonthApprovedCount }} Sesi</p>
            <p class="text-xs text-emerald-600 font-semibold mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                <span>Telah diverifikasi admin</span>
            </p>
        </div>
    </div>

    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Rate per Sesi</span>
            <div class="w-10 h-10 rounded-xl bg-[#ff5722]/15 text-[#ff5722] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px] filled">payments</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">Rp {{ number_format($coach->rate_per_session, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 font-medium mt-2">Tarif pengajaran per kelas</p>
        </div>
    </div>

    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Estimasi Komisi</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-slate-900 tracking-tight">
                Rp {{ number_format($estimatedCommissionThisMonth, 0, ',', '.') }}
            </p>
            <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $payrollThisMonth?->status === 'paid' ? 'Sudah Dibayarkan Admin' : 'Siap Dicairkan (Pending)' }}</span>
            </p>
        </div>
    </div>
</div>

{{-- 1. Rincian Sesi Mengajar Disetujui Bulan Ini --}}
<div class="bento-card rounded-2xl overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Rincian Sesi Mengajar Disetujui</h3>
                <p class="text-xs text-slate-500">Daftar presensi kelas yang sudah terverifikasi dan menghasilkan komisi</p>
            </div>
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
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                <tr>
                    <th class="px-6 py-4">Nama Kelas</th>
                    <th class="px-6 py-4">Tanggal Sesi</th>
                    <th class="px-6 py-4">Nominal Komisi</th>
                    <th class="px-6 py-4 text-right">Status Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($approvedSessions as $s)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 font-extrabold text-slate-900 font-display">{{ $s->schedule?->classType?->name ?? 'Kelas Mengajar' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $s->session_date->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 font-bold text-emerald-600 font-display">
                        Rp {{ number_format($s->commission_amount > 0 ? $s->commission_amount : $coach->rate_per_session, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
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
<div class="bento-card rounded-2xl overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
            <div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Riwayat Pencairan Payroll Admin</h3>
                <p class="text-xs text-slate-500">Catatan komisi bulanan yang telah resmi dicairkan oleh pihak manajemen</p>
            </div>
        </div>
        <span class="material-symbols-outlined text-slate-400">account_balance</span>
    </div>
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                <tr>
                    <th class="px-6 py-4">Bulan & Tahun</th>
                    <th class="px-6 py-4">Total Sesi</th>
                    <th class="px-6 py-4">Rate / Sesi</th>
                    <th class="px-6 py-4">Total Pembayaran</th>
                    <th class="px-6 py-4 text-right">Status Payroll</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($payrolls as $p)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 font-extrabold text-slate-900 font-display">{{ \Carbon\Carbon::create()->month($p->month)->translatedFormat('F') }} {{ $p->year }}</td>
                    <td class="px-6 py-4 text-slate-600 font-semibold">{{ $p->total_sessions }} Sesi</td>
                    <td class="px-6 py-4 text-slate-400 font-mono">Rp {{ number_format($coach->rate_per_session, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-extrabold text-slate-900 font-display">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($p->status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Lunas Dibayar
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Proses Payroll
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-medium">Belum ada riwayat pencairan payroll bulanan dari admin.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

