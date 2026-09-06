@extends('layouts.owner')

@section('title', 'Executive Dashboard — Gintung Master Fitness')
@section('page-title', 'Executive Console')
@section('page-subtitle', 'Ringkasan performa finansial & operasional Gintung Master Fitness')

@section('content')

{{-- Bento-Box Stat Cards Grid (Stitch AI Layout) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Card 1: Total Accumulative Revenue --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#ff5722]/10 rounded-full blur-2xl group-hover:bg-[#ff5722]/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Akumulasi Revenue</span>
            <div class="w-10 h-10 rounded-xl bg-[#ff5722]/15 text-[#ff5722] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px] filled">payments</span>
            </div>
        </div>
        <div class="relative z-10">
            <div class="flex items-baseline gap-1.5 whitespace-nowrap overflow-hidden">
                <span class="text-xs font-bold text-[#ff5722] font-display shrink-0">Rp</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-display tracking-tight truncate">
                    {{ number_format($totalRevenue, 0, ',', '.') }}
                </span>
            </div>
            <p class="text-[11px] text-slate-500 font-semibold mt-2">Membership + Kasir + Sesi</p>
        </div>
    </div>

    {{-- Card 2: Member Aktif --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-sky-500/10 rounded-full blur-2xl group-hover:bg-sky-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Member Aktif</span>
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">groups</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $totalMembers }}</p>
            <p class="text-[11px] text-slate-500 mt-2 font-medium">Terdaftar di sistem SIM GMF</p>
        </div>
    </div>

    {{-- Card 3: Payroll Dibayar Bulan Ini --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Payroll Coach Terbayar</span>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
            </div>
        </div>
        <div class="relative z-10">
            <div class="flex items-baseline gap-1.5 whitespace-nowrap overflow-hidden">
                <span class="text-xs font-bold text-purple-600 font-display shrink-0">Rp</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-display tracking-tight truncate">
                    {{ number_format($totalPayrollPaid, 0, ',', '.') }}
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2 font-medium">Realisasi pembayaran honor</p>
        </div>
    </div>

    {{-- Card 4: Penjualan Produk Bulan Ini --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk Kasir Terjual</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $productSalesCount }}</p>
            <p class="text-[11px] text-slate-500 mt-2 font-medium">Transaksi merchandise / katering</p>
        </div>
    </div>
</div>

{{-- Dynamic Growth Chart Card (Chart.js Kinetic Implementation) --}}
<div class="bento-card rounded-2xl p-6 sm:p-7 mb-8 relative overflow-hidden group">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
            <div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Grafik Pertumbuhan Member Baru</h3>
                <p class="text-xs text-slate-500 font-medium">Statistik registrasi member 6 bulan terakhir</p>
            </div>
        </div>
        <a href="{{ route('owner.reports.index') }}" class="text-xs font-bold text-[#ff5722] hover:underline flex items-center gap-1">
            <span>Laporan Lengkap</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>
    
    <div class="relative h-64 w-full">
        <canvas id="memberGrowthChart"></canvas>
    </div>
</div>

{{-- Lower Double Columns --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Recent Sales --}}
    <div class="bento-card rounded-2xl overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Penjualan Produk Terbaru</h3>
                    <p class="text-xs text-slate-500">Transaksi kasir produk suplemen / merchandise</p>
                </div>
                <span class="material-symbols-outlined text-slate-400">storefront</span>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($recentSales as $s)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/70 transition-colors">
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
    <div class="bento-card rounded-2xl overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Ringkasan Payroll Coach</h3>
                    <p class="text-xs text-slate-500">Status akumulasi komisi & honor pelatih</p>
                </div>
                <span class="material-symbols-outlined text-slate-400">badge</span>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($payrollSummary as $p)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/70 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-slate-900 font-display">{{ $p->coach->user->name }}</p>
                        <p class="text-slate-400 font-medium mt-0.5">{{ $p->total_sessions }} Sesi Mengajar</p>
                    </div>
                    <div class="text-right">
                        <p class="font-extrabold text-sm text-slate-900 font-display">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</p>
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $p->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('memberGrowthChart');
    if (!ctx) return;

    const labels = {!! json_encode(array_column($monthlyData, 'label')) !!};
    const dataValues = {!! json_encode(array_column($monthlyData, 'count')) !!};

    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0, '#ff6f4d');
    gradient.addColorStop(1, '#ff5722');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Member Baru',
                data: dataValues,
                backgroundColor: gradient,
                hoverBackgroundColor: '#e13b12',
                borderRadius: 10,
                borderSkipped: false,
                barThickness: 38,
                maxBarThickness: 48,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f1418',
                    titleFont: { family: 'Montserrat', size: 12, weight: '700' },
                    bodyFont: { family: 'Inter', size: 12, weight: '500' },
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function (context) {
                            return ' ' + context.parsed.y + ' Member Baru';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: { family: 'Montserrat', size: 11, weight: '700' }
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...dataValues, 5),
                    grid: {
                        color: 'rgba(226, 232, 240, 0.7)',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        color: '#94a3b8',
                        font: { family: 'Inter', size: 11, weight: '600' },
                        padding: 8
                    }
                }
            }
        }
    });
});
</script>
@endpush

