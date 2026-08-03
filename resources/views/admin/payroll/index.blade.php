@extends('layouts.admin')
@section('title', 'Penggajian Pelatih — Gintung Master Fitness')
@section('page-title', 'Penggajian Pelatih (Payroll)')
@section('page-subtitle', 'Kalkulasi otomatis komisi sesi mengajar dan pemrosesan honor')
@section('content')

{{-- Generate Form --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 mb-6">
    <h3 class="font-bold text-slate-900 font-display text-base mb-4">Proses Generate Payroll</h3>
    <form method="POST" action="{{ route('admin.payroll.generate') }}" class="flex flex-wrap gap-4 items-end">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Pilihan Bulan</label>
            <select name="month" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:border-[#f05a2a]">
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month((int)$m)->format('F') }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Tahun</label>
            <select name="year" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:border-[#f05a2a]">
                @foreach(range(now()->year - 1, now()->year + 1) as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Kalkulasi Payroll Baru</span>
        </button>
    </form>
</div>

{{-- Summary Cards --}}
@if($payrolls->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 text-center">
        <p class="text-3xl font-extrabold text-slate-900 font-display">{{ $summary['total_coaches'] }}</p>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">Jumlah Pelatih</p>
    </div>
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 text-center">
        <p class="text-3xl font-extrabold text-slate-900 font-display">{{ $summary['total_sessions'] }}</p>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">Total Sesi Terverifikasi</p>
    </div>
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 text-center">
        <p class="text-2xl font-extrabold text-[#f05a2a] font-display">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</p>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">Total Pengeluaran Payroll</p>
    </div>
</div>
@endif

{{-- Payroll Table --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-slate-900 font-display text-base">Rekap Payroll {{ \Carbon\Carbon::create()->month((int)$month)->format('F') }} {{ $year }}</h3>
            <p class="text-xs text-slate-500">Rincian komisi mengajar tiap pelatih</p>
        </div>
        <a href="{{ route('admin.payroll.history') }}" class="text-xs font-bold text-[#f05a2a] hover:underline">Lihat Riwayat Lunas →</a>
    </div>
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                <tr>
                    <th class="px-6 py-4">Pelatih</th>
                    <th class="px-6 py-4">Sesi Disetujui</th>
                    <th class="px-6 py-4">Rate / Sesi</th>
                    <th class="px-6 py-4">Total Komisi</th>
                    <th class="px-6 py-4">Status Pembayaran</th>
                    <th class="px-6 py-4">Tanggal Cair</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($payrolls as $p)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-900 font-display">{{ $p->coach->user->name }}</td>
                    <td class="px-6 py-4 text-slate-600 font-semibold">{{ $p->total_sessions }} Sesi</td>
                    <td class="px-6 py-4 text-slate-600">Rp {{ number_format($p->coach->rate_per_session, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-extrabold text-slate-900 font-display">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $p->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                            {{ $p->status === 'paid' ? 'Lunas / Paid' : 'Pending Pembayaran' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-400">{{ $p->paid_at ? $p->paid_at->format('d M Y') : '—' }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($p->status === 'pending')
                        <form method="POST" action="{{ route('admin.payroll.mark-paid', $p) }}" onsubmit="return confirm('Tandai payroll pelatih ini sebagai lunas?')">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition-all shadow-sm">Tandai Lunas</button>
                        </form>
                        @else
                        <span class="text-xs text-slate-400 font-semibold">Tercatat Lunas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                        Belum ada data payroll untuk bulan ini. Klik tombol "Kalkulasi Payroll Baru".
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
