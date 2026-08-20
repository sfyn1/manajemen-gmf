@extends('layouts.coach')

@section('title', 'Dashboard Pelatih — Gintung Master Fitness')
@section('page-title', 'Dashboard Pelatih')
@section('page-subtitle', 'Selamat datang kembali, Coach ' . auth()->user()->name)

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Card 1: Rate Komisi / Sesi (Transparansi Pelatih) --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rate Komisi / Sesi</p>
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-[#f05a2a]">
                Rp {{ number_format($coach->rate_per_session ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <p class="text-xs text-slate-400 mt-3 font-medium flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-[#f05a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Tarif komisi per sesi</span>
        </p>
    </div>

    {{-- Card 2: Verifikasi Pending --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Verifikasi Pending</p>
            <p class="text-2xl sm:text-3xl font-extrabold font-display {{ $pendingVerifications->count() > 0 ? 'text-[#f05a2a]' : 'text-slate-900' }}">
                {{ $pendingVerifications->count() }}
            </p>
        </div>
        @if($pendingVerifications->count() > 0)
        <p class="text-xs font-semibold text-[#f05a2a] mt-3 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Membutuhkan verifikasi Anda</span>
        </p>
        @else
        <p class="text-xs text-slate-400 mt-3 font-medium">Semua presensi terverifikasi</p>
        @endif
    </div>

    {{-- Card 3: Sesi Terverifikasi Bulan Ini --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Sesi Terverifikasi Bulan Ini</p>
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-emerald-600">{{ $thisMonthApproved->count() }}</p>
        </div>
        <p class="text-xs text-slate-400 mt-3 font-medium">Disetujui admin bulan ini</p>
    </div>

    {{-- Card 4: Estimasi Komisi Bulan Ini --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Estimasi Komisi Bulan Ini</p>
            <p class="text-2xl sm:text-3xl font-extrabold font-display text-slate-900">
                Rp {{ number_format($totalEarningsMonthAmount ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <p class="text-xs text-emerald-600 font-semibold mt-3 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>{{ ($payrollStatus ?? 'pending') === 'paid' ? 'Sudah Dibayarkan' : 'Pending Payroll' }}</span>
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- Pending Verifications --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-900 font-display text-base">Verifikasi Presensi Kelas</h3>
                    <p class="text-xs text-slate-500">Daftar sesi yang belum diverifikasi</p>
                </div>
                <a href="{{ route('coach.attendance.index') }}" class="text-xs font-bold text-[#f05a2a] hover:underline">Kelola Semua</a>
            </div>
            @if($pendingVerifications->isEmpty())
            <div class="p-8 text-center text-slate-400 text-xs font-medium">
                Semua verifikasi presensi kelas telah selesai diverifikasi
            </div>
            @else
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($pendingVerifications->take(5) as $v)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-slate-900 font-display">{{ $v->schedule->classType->name }}</p>
                        <p class="text-slate-400 font-medium mt-0.5">{{ $v->session_date->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <a href="{{ route('coach.attendance.index') }}"
                        class="px-3.5 py-1.5 bg-[#f05a2a] hover:bg-[#ff6f4d] text-white text-xs font-semibold rounded-xl transition-all shadow-sm">
                        Verifikasi
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Weekly Class Schedule with Member Bookings --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 font-display text-base">Jadwal Mengajar & Peserta Member</h3>
                <p class="text-xs text-slate-500">Agenda sesi kelas & jumlah member terdaftar</p>
            </div>
            @if($upcomingSchedules->isEmpty())
            <div class="p-8 text-center text-slate-400 text-xs font-medium">
                Tidak ada jadwal mengajar pada minggu ini
            </div>
            @else
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($upcomingSchedules as $s)
                <div class="px-6 py-4 hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-center justify-between mb-1.5">
                        <div>
                            <p class="text-sm font-bold text-slate-900 font-display">{{ $s->classType->name }}</p>
                            <p class="text-slate-500 font-medium text-xs mt-0.5">
                                {{ $s->next_date_label ?? \App\Models\ClassSchedule::DAYS[$s->day_of_week] }} • {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200/60 font-bold text-xs">
                            {{ $s->bookings->count() }} / {{ $s->max_capacity }} Peserta
                        </span>
                    </div>

                    @if($s->bookings->isNotEmpty())
                    <div class="mt-2 text-[11px] text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-slate-700">Member Terdaftar:</span>
                        @foreach($s->bookings as $b)
                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 font-medium text-slate-800">
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
