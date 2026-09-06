@extends('layouts.admin')

@section('title', 'Console Dashboard — Gintung Master Fitness')
@section('page-title', 'Performance Console')
@section('page-subtitle', 'Ikhtisar aktivitas dan performa operasional fitness center')

@section('content')

{{-- Bento-Box Stat Cards Grid (Stitch AI Layout) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Total Members Card --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#ff5722]/10 rounded-full blur-2xl group-hover:bg-[#ff5722]/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Member</span>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-[#ff5722]">
                <span class="material-symbols-outlined text-[22px]">groups</span>
            </div>
        </div>
        <div class="relative z-10 flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $total_members }}</span>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[14px]">trending_up</span> Aktif
            </span>
        </div>
        <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#ff5722] w-[85%] rounded-full"></div>
        </div>
    </div>

    {{-- Pending Approval Card --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group cursor-pointer hover:border-[#ff5722]/50"
         onclick="location='{{ route('admin.approval.index') }}'">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/25 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pending Approval</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <span class="material-symbols-outlined text-[22px]">pending_actions</span>
            </div>
        </div>
        <div class="relative z-10 flex items-baseline justify-between">
            <span class="text-3xl font-extrabold font-display tracking-tight {{ $pending_members > 0 ? 'text-[#ff5722]' : 'text-slate-900' }}">{{ $pending_members }}</span>
            @if($pending_members > 0)
            <span class="text-xs font-bold text-[#ff5722] bg-[#ff5722]/10 px-2 py-0.5 rounded-full border border-[#ff5722]/20">
                Butuh Review
            </span>
            @else
            <span class="text-xs font-medium text-slate-400">Clear</span>
            @endif
        </div>
        <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-500 {{ $pending_members > 0 ? 'w-[60%]' : 'w-0' }} rounded-full"></div>
        </div>
    </div>

    {{-- Kunjungan Hari Ini Card --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Kunjungan Hari Ini</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <span class="material-symbols-outlined text-[22px]">timer</span>
            </div>
        </div>
        <div class="relative z-10 flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $total_visits_today }}</span>
            <span class="text-xs font-semibold text-slate-500">Check-in</span>
        </div>
        <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 w-[70%] rounded-full"></div>
        </div>
    </div>

    {{-- Kunjungan Minggu Ini Card --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Kunjungan Minggu Ini</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <span class="material-symbols-outlined text-[22px]">bar_chart</span>
            </div>
        </div>
        <div class="relative z-10 flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $total_visits_week }}</span>
            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200/60">
                Mingguan
            </span>
        </div>
        <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-indigo-500 w-[75%] rounded-full"></div>
        </div>
    </div>
</div>

{{-- Revenue Highlight Banner (Stitch Bento Highlight) --}}
<div class="mb-8 bento-card rounded-2xl p-6 sm:p-7 relative overflow-hidden group">
    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#ff5722]/10 rounded-full blur-3xl group-hover:bg-[#ff5722]/20 transition-colors duration-500"></div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5 relative z-10">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#ff5722]/15 text-[#ff5722] flex items-center justify-center shrink-0 shadow-inner">
                <span class="material-symbols-outlined text-[28px] filled">payments</span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Estimasi Pendapatan Bulan Ini</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-sm font-extrabold text-[#ff5722] font-display">Rp</span>
                    <span class="text-2xl sm:text-3xl xl:text-4xl font-extrabold text-slate-900 font-display tracking-tight">
                        {{ number_format($total_revenue_month, 0, ',', '.') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Akumulasi transaksi membership & penjualan kasir</p>
            </div>
        </div>
        <a href="{{ route('admin.membership.index') }}" class="bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-bold px-6 py-3 rounded-xl inline-flex items-center gap-2 shrink-0 shadow-lg shadow-[#ff5722]/25 transition-all">
            <span>Kelola Membership</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>
</div>

{{-- Main Grid Section --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Member Terbaru Table --}}
    <div class="lg:col-span-2 bento-card rounded-2xl overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
                    <div>
                        <h3 class="font-extrabold text-slate-900 font-display text-base">Pendaftaran Member Terbaru</h3>
                        <p class="text-xs text-slate-500">Daftar calon member dan status verifikasi</p>
                    </div>
                </div>
                <a href="{{ route('admin.membership.index') }}" class="text-xs font-bold text-[#ff5722] hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Member</th>
                            <th class="px-6 py-3.5 font-bold">Paket</th>
                            <th class="px-6 py-3.5 font-bold">Status</th>
                            <th class="px-6 py-3.5 font-bold">Tanggal</th>
                            <th class="px-6 py-3.5 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($recent_members as $member)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-[#0f1418] text-[#ff5722] flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-900 text-sm">{{ $member->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-semibold">{{ $member->package?->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeStyle = match($member->status) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending_verification' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badgeStyle }}">
                                    {{ $member->status === 'pending_verification' ? 'Pending' : ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-medium">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.membership.show', $member) }}" class="text-[#ff5722] hover:underline font-bold text-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada member terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Side Attention & Live Schedule Card --}}
    <div class="bento-card rounded-2xl p-6 space-y-5">
        <h3 class="font-extrabold text-slate-900 font-display text-base border-b border-slate-100 pb-3 flex items-center justify-between">
            <span>Perlu Perhatian</span>
            <span class="material-symbols-outlined text-amber-500 text-[20px]">notification_important</span>
        </h3>

        <a href="{{ route('admin.approval.index') }}" class="flex items-center justify-between p-4 rounded-xl bg-amber-50/80 border border-amber-200/60 hover:bg-amber-100/90 transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-200 text-amber-900 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">{{ $pending_approvals_count }} Permohonan Pending</p>
                    <p class="text-[11px] text-slate-500">Pendaftaran butuh verifikasi</p>
                </div>
            </div>
            <span class="material-symbols-outlined text-slate-400 group-hover:text-amber-700 transition-colors">chevron_right</span>
        </a>

        @if($payroll_pending > 0)
        <a href="{{ route('admin.payroll.index') }}" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0f1418] text-white flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">{{ $payroll_pending }} Payroll Belum Dibayar</p>
                    <p class="text-[11px] text-slate-500">Gaji / komisi coach bulan ini</p>
                </div>
            </div>
            <span class="material-symbols-outlined text-slate-400 group-hover:text-slate-900 transition-colors">chevron_right</span>
        </a>
        @endif

        {{-- Jadwal Hari Ini --}}
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2.5">
            <p class="text-xs font-bold text-slate-900 font-display flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ff5722] text-[18px]">calendar_today</span>
                <span>Jadwal Kelas Hari Ini</span>
            </p>
            @forelse($schedules_today as $s)
            <div class="text-xs text-slate-600 flex justify-between py-1.5 border-b border-slate-200/60 last:border-0 font-medium">
                <span class="font-semibold text-slate-800">{{ $s->classType->name }}</span>
                <span class="font-bold text-[#ff5722]">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB</span>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic py-1">Tidak ada agenda kelas hari ini</p>
            @endforelse
        </div>

        {{-- Total Bookings --}}
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 flex justify-between items-center">
            <div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Booking Kelas Hari Ini</p>
                <p class="text-2xl font-extrabold text-slate-900 font-display">{{ $bookings_today }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#ff5722]/15 text-[#ff5722] flex items-center justify-center font-bold text-xs">
                Sesi
            </div>
        </div>
    </div>
</div>

@endsection

