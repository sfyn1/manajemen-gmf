@extends('layouts.coach')

@section('title', 'Dashboard Pelatih — Gintung Master Fitness')
@section('page-title', 'Coach Console')
@section('page-subtitle', 'Selamat datang kembali, Coach ' . auth()->user()->name)

@section('content')

{{-- Bento-Box Stat Cards Grid (Stitch AI Layout) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Card 1: Rate Komisi / Sesi --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#ff5722]/10 rounded-full blur-2xl group-hover:bg-[#ff5722]/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rate Komisi / Sesi</span>
            <div class="w-10 h-10 rounded-xl bg-[#ff5722]/15 text-[#ff5722] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px] filled">payments</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-[#ff5722] tracking-tight">
                Rp {{ number_format($coach->rate_per_session ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-2 font-medium flex items-center gap-1">
                <span class="material-symbols-outlined text-[#ff5722] text-[14px]">info</span>
                <span>Tarif resmi per sesi kelas</span>
            </p>
        </div>
    </div>

    {{-- Card 2: Verifikasi Pending --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group cursor-pointer hover:border-[#ff5722]/50"
         onclick="location='{{ route('coach.attendance.index') }}'">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/25 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Verifikasi Pending</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                <span class="material-symbols-outlined text-[22px]">pending_actions</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-2xl sm:text-3xl font-extrabold font-display tracking-tight {{ $pendingVerifications->count() > 0 ? 'text-[#ff5722]' : 'text-slate-900' }}">
                {{ $pendingVerifications->count() }}
            </p>
            @if($pendingVerifications->count() > 0)
            <p class="text-xs font-bold text-[#ff5722] mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">priority_high</span>
                <span>Membutuhkan verifikasi Anda</span>
            </p>
            @else
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Semua presensi terverifikasi</p>
            @endif
        </div>
    </div>

    {{-- Card 3: Sesi Terverifikasi Bulan Ini --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sesi Terverifikasi</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px] filled">task_alt</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-emerald-600 tracking-tight">{{ $thisMonthApproved->count() }}</p>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Disetujui admin bulan ini</p>
        </div>
    </div>

    {{-- Card 4: Estimasi Komisi Bulan Ini --}}
    <div class="bento-card rounded-2xl p-5 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors duration-500"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Estimasi Komisi</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-slate-900 tracking-tight">
                Rp {{ number_format($totalEarningsMonthAmount ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ ($payrollStatus ?? 'pending') === 'paid' ? 'Sudah Dibayarkan' : 'Pending Payroll' }}</span>
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- Pending Verifications --}}
    <div class="bento-card rounded-2xl overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
                    <div>
                        <h3 class="font-extrabold text-slate-900 font-display text-base">Verifikasi Presensi Kelas</h3>
                        <p class="text-xs text-slate-500">Daftar sesi kelas yang belum Anda verifikasi</p>
                    </div>
                </div>
                <a href="{{ route('coach.attendance.index') }}" class="text-xs font-bold text-[#ff5722] hover:underline flex items-center gap-1">
                    <span>Kelola</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>
            @if($pendingVerifications->isEmpty())
            <div class="p-8 text-center text-slate-400 text-xs font-medium">
                Semua verifikasi presensi kelas telah selesai diverifikasi
            </div>
            @else
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($pendingVerifications->take(5) as $v)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/70 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-slate-900 font-display">{{ $v->schedule->classType->name }}</p>
                        <p class="text-slate-400 font-medium mt-0.5">{{ $v->session_date->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <a href="{{ route('coach.attendance.index') }}"
                        class="px-4 py-2 bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl inline-flex items-center gap-1.5 shadow-md shadow-[#ff5722]/20 transition-all">
                        <span>Verifikasi</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Weekly Class Schedule with Member Bookings --}}
    <div class="bento-card rounded-2xl overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <div>
                        <h3 class="font-extrabold text-slate-900 font-display text-base">Jadwal Mengajar & Roster</h3>
                        <p class="text-xs text-slate-500">Agenda sesi kelas & daftar member terdaftar</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-slate-400">calendar_month</span>
            </div>
            @if($upcomingSchedules->isEmpty())
            <div class="p-8 text-center text-slate-400 text-xs font-medium">
                Tidak ada jadwal mengajar pada minggu ini
            </div>
            @else
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($upcomingSchedules as $s)
                <div class="px-6 py-4 hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-sm font-bold text-slate-900 font-display">{{ $s->classType->name }}</p>
                            <p class="text-slate-500 font-medium text-xs mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[#ff5722] text-[14px]">schedule</span>
                                <span>{{ $s->next_date_label ?? \App\Models\ClassSchedule::DAYS[$s->day_of_week] }} • {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB</span>
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 border border-slate-200 font-bold text-xs">
                            {{ $s->bookings->count() }} / {{ $s->max_capacity }} Peserta
                        </span>
                    </div>

                    @if($s->bookings->isNotEmpty())
                    <div class="mt-2.5 text-[11px] text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-slate-700">Member:</span>
                        @foreach($s->bookings as $b)
                        <span class="px-2.5 py-0.5 bg-white rounded-lg border border-slate-200 font-semibold text-slate-800 shadow-2xs">
                            {{ $b->member->full_name }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-[11px] text-slate-400 mt-1 italic">Belum ada member yang mendaftar online.</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

