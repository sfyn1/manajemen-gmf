@extends('layouts.admin')

@section('title', 'Dashboard Admin — Gintung Master Fitness')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ikhtisar aktivitas dan performa operasional fitness center')

@section('content')

{{-- Stat Cards Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    @php
        $stats = [
            [
                'label' => 'Member Aktif',
                'value' => $total_members,
                'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                'bg'    => 'bg-sky-500/10 text-sky-600',
            ],
            [
                'label' => 'Pending Approval',
                'value' => $pending_members,
                'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg'    => 'bg-[#f05a2a]/10 text-[#f05a2a]',
                'link'  => route('admin.approval.index')
            ],
            [
                'label' => 'Kunjungan Hari Ini',
                'value' => $total_visits_today,
                'icon'  => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
                'bg'    => 'bg-emerald-500/10 text-emerald-600',
            ],
            [
                'label' => 'Kunjungan Minggu Ini',
                'value' => $total_visits_week,
                'icon'  => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'bg'    => 'bg-purple-500/10 text-purple-600',
            ],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all group
        {{ isset($stat['link']) ? 'cursor-pointer hover:border-[#f05a2a]/40' : '' }}"
        @if(isset($stat['link'])) onclick="location='{{ $stat['link'] }}'" @endif>
        <div>
            <div class="flex items-center justify-between gap-2 mb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</p>
                <div class="w-10 h-10 rounded-2xl {{ $stat['bg'] }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-display tracking-tight">{{ $stat['value'] }}</p>
        </div>
        @if($stat['label'] === 'Pending Approval' && $pending_members > 0)
        <p class="text-xs text-[#f05a2a] font-semibold mt-3 flex items-center gap-1">
            <span>Tinjau pendaftaran</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </p>
        @endif
    </div>
    @endforeach
</div>

{{-- Revenue Highlight Card --}}
<div class="mb-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 hover:shadow-md transition-all">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Estimasi Pendapatan Bulan Ini</p>
            <div class="flex items-baseline gap-1.5 whitespace-nowrap overflow-hidden">
                <span class="text-sm font-bold text-slate-400 font-display shrink-0">Rp</span>
                <span class="text-xl sm:text-2xl xl:text-3xl font-extrabold text-slate-900 font-display tracking-tight truncate">
                    {{ number_format($total_revenue_month, 0, ',', '.') }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Akumulasi transaksi membership & penjualan produk kasir</p>
        </div>
        <a href="{{ route('admin.membership.index') }}" class="px-5 py-2.5 bg-[#f05a2a] hover:bg-[#ff6f4d] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 shrink-0">
            Kelola Membership
        </a>
    </div>
</div>

{{-- Main Grid Section --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Member Terbaru Table --}}
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 font-display text-base">Pendaftaran Member Terbaru</h3>
                    <p class="text-xs text-slate-500">Daftar calon member dan verifikasi status</p>
                </div>
                <a href="{{ route('admin.membership.index') }}" class="text-xs font-bold text-[#f05a2a] hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-slate-900 text-[#f05a2a] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800 text-sm">{{ $member->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $member->package?->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeStyle = match($member->status) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending_verification' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeStyle }}">
                                    {{ $member->status === 'pending_verification' ? 'Verifikasi Pending' : ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.membership.show', $member) }}" class="text-[#f05a2a] hover:underline font-semibold text-xs">
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

    {{-- Side Attention Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        <h3 class="font-bold text-slate-900 font-display text-base border-b border-slate-100 pb-3">Perlu Perhatian</h3>

        <a href="{{ route('admin.approval.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-amber-50/70 border border-amber-100 hover:bg-amber-100/80 transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">{{ $pending_approvals_count }} Permohonan Pending</p>
                    <p class="text-[11px] text-slate-500">Pendaftaran butuh verifikasi</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        @if($payroll_pending > 0)
        <a href="{{ route('admin.payroll.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">{{ $payroll_pending }} Payroll Belum Dibayar</p>
                    <p class="text-[11px] text-slate-500">Gaji/komisi coach bulan ini</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-800 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        {{-- Jadwal Hari Ini --}}
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
            <p class="text-xs font-bold text-slate-800 font-display flex items-center gap-2">
                <svg class="w-4 h-4 text-[#f05a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Jadwal Kelas Hari Ini</span>
            </p>
            @forelse($schedules_today as $s)
            <div class="text-xs text-slate-600 flex justify-between py-1.5 border-b border-slate-200/50 last:border-0 font-medium">
                <span>{{ $s->classType->name }}</span>
                <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB</span>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic py-1">Tidak ada agenda kelas hari ini</p>
            @endforelse
        </div>

        {{-- Total Bookings --}}
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex justify-between items-center">
            <div>
                <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Booking Kelas Hari Ini</p>
                <p class="text-2xl font-extrabold text-slate-900 font-display">{{ $bookings_today }}</p>
            </div>
            <div class="w-9 h-9 rounded-xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center font-bold text-xs">
                Sesi
            </div>
        </div>
    </div>
</div>

@endsection
