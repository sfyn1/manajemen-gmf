@extends('layouts.owner')

@section('title', 'Dashboard Owner — Gintung Master Fitness')
@section('page-title', 'Executive Dashboard')
@section('page-subtitle', 'Ringkasan performa finansial & operasional Gintung Master Fitness')

@section('content')

{{-- Stat Cards Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <div class="flex items-center justify-between gap-2 mb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Accumulative Revenue</p>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1.5 whitespace-nowrap overflow-hidden">
                <span class="text-sm font-bold text-slate-400 font-display shrink-0">Rp</span>
                <span class="text-xl sm:text-2xl xl:text-3xl font-extrabold text-slate-900 font-display tracking-tight truncate">
                    {{ number_format($totalRevenue, 0, ',', '.') }}
                </span>
            </div>
        </div>
        <p class="text-xs text-[#f05a2a] font-semibold mt-3">Membership + Perpanjangan + Kasir + Kelas</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <div class="flex items-center justify-between gap-2 mb-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Member Aktif</p>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 text-sky-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $totalMembers }}</p>
        </div>
        <p class="text-xs text-slate-500 mt-3 font-medium">Terdaftar di sistem SIM GMF</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <div class="flex items-center justify-between gap-2 mb-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Payroll Dibayar Bulan Ini</p>
                <div class="w-10 h-10 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1.5 whitespace-nowrap overflow-hidden">
                <span class="text-sm font-bold text-slate-400 font-display shrink-0">Rp</span>
                <span class="text-xl sm:text-2xl xl:text-3xl font-extrabold text-slate-900 font-display tracking-tight truncate">
                    {{ number_format($totalPayrollPaid, 0, ',', '.') }}
                </span>
            </div>
        </div>
        <p class="text-xs text-slate-500 mt-3 font-medium">Realisasi pembayaran gaji coach</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <div class="flex items-center justify-between gap-2 mb-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Penjualan Produk Bulan Ini</p>
                <div class="w-10 h-10 rounded-2xl bg-[#f05a2a]/10 text-[#f05a2a] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $productSalesCount }}</p>
        </div>
        <p class="text-xs text-slate-500 mt-3 font-medium">Transaksi merchandise / produk</p>
    </div>
</div>

{{-- Dynamic Growth Chart Card --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 mb-8"
    data-monthly="{{ json_encode($monthlyData) }}">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
        <div>
            <h3 class="font-bold text-slate-900 font-display text-base">Grafik Pertumbuhan Member Baru</h3>
            <p class="text-xs text-slate-500">Statistik pendaftaran member 6 bulan terakhir</p>
        </div>
        <a href="{{ route('owner.reports.index') }}" class="text-xs font-bold text-[#f05a2a] hover:underline">Lihat Laporan Lengkap →</a>
    </div>
    
    <div class="h-44 flex items-end gap-3 sm:gap-6 pt-4">
        @foreach($monthlyData as $d)
        @php $maxVal = max(array_column($monthlyData, 'count')) ?: 1; $pct = ($d['count'] / $maxVal) * 100; @endphp
        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
            <span class="text-xs font-bold text-slate-700 font-display">{{ $d['count'] }}</span>
            <div class="w-full rounded-t-xl bg-[#f05a2a] hover:bg-[#ff6f4d] transition-all" style="height: {{ max(6, $pct) }}%; min-height: 8px;"></div>
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-tight mt-1">{{ $d['label'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Lower Double Columns --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Recent Sales --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 font-display text-base">Penjualan Produk Terbaru</h3>
                <p class="text-xs text-slate-500">Transaksi kasir produk katering / suplemen / merchandise</p>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($recentSales as $s)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-slate-900 font-display">{{ $s->product?->name }}</p>
                        <p class="text-slate-400 font-medium mt-0.5">{{ $s->created_at->diffForHumans() }} • Qty: {{ $s->qty }} pcs</p>
                    </div>
                    <p class="font-extrabold text-slate-900 text-sm font-display">Rp {{ number_format($s->total_price, 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs font-medium">Belum ada transaksi penjualan produk</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Payroll Summary --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 font-display text-base">Ringkasan Payroll Coach Bulan Ini</h3>
                <p class="text-xs text-slate-500">Status akumulasi komisi & Honor pelatih</p>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($payrollSummary as $p)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-slate-900 font-display">{{ $p->coach->user->name }}</p>
                        <p class="text-slate-400 font-medium mt-0.5">{{ $p->total_sessions }} Sesi Mengajar</p>
                    </div>
                    <div class="text-right">
                        <p class="font-extrabold text-sm text-slate-900 font-display">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</p>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $p->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $p->status === 'paid' ? 'Lunas / Paid' : 'Belum Dibayar' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs font-medium">Belum ada data payroll bulan ini</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
