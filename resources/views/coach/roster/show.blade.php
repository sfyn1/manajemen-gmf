@extends('layouts.coach')

@section('title', 'Daftar Peserta Kelas — Gintung Master Fitness')
@section('page-title', 'Daftar Peserta Kelas')
@section('page-subtitle', $schedule->classType->name . ' — ' . $parsedDate->translatedFormat('l, d M Y'))

@section('content')

<div class="space-y-6">
    {{-- Header Info --}}
    <div class="bento-card rounded-2xl p-6 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div>
                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/60 inline-block mb-1.5">
                    ROSTER KELAS
                </span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 font-display tracking-tight">{{ $schedule->classType->name }}</h2>
                <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[#ff5722] text-[16px]">schedule</span>
                    <span>{{ $parsedDate->translatedFormat('l, d F Y') }} • {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</span>
                </p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <div class="flex-1 sm:flex-initial text-center px-5 py-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <p class="text-2xl font-extrabold text-[#ff5722] font-display">{{ $bookings->count() }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Booking</p>
                </div>
                <div class="flex-1 sm:flex-initial text-center px-5 py-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <p class="text-2xl font-extrabold text-slate-800 font-display">{{ $schedule->max_capacity }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kapasitas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Roster table --}}
    <div class="bento-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Daftar Member Terdaftar</h3>
            </div>
            <span class="text-xs font-bold text-slate-500">{{ $bookings->count() }} Orang</span>
        </div>
        @if($bookings->isEmpty())
        <div class="p-12 text-center text-slate-400">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-[28px]">groups</span>
            </div>
            <p class="text-xs font-bold text-slate-700">Belum ada peserta yang booking untuk sesi kelas ini</p>
        </div>
        @else
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Member</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4">Status Booking</th>
                        <th class="px-6 py-4 text-right">Biaya Sesi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($bookings as $i => $booking)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-bold">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-[#0f1418] text-[#ff5722] flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                    {{ strtoupper(substr($booking->member->full_name, 0, 1)) }}
                                </div>
                                <span class="font-extrabold text-slate-900 text-sm">{{ $booking->member->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono">{{ $booking->member->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold border
                                @if($booking->status === 'booked') bg-sky-50 text-sky-700 border-sky-200
                                @elseif($booking->status === 'attended') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($booking->status === 'no_show') bg-rose-50 text-rose-700 border-rose-200
                                @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-extrabold text-slate-900 font-display">Rp {{ number_format($booking->payment_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Verification status --}}
    <div class="bento-card rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-900 font-display text-sm mb-1">Status Verifikasi Kehadiran Sesi</h3>
            <p class="text-xs text-slate-500 font-medium">Pastikan mengunggah foto setelah kelas selesai</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold border
                @if($verification->status === 'approved') bg-emerald-50 text-emerald-700 border-emerald-200
                @elseif($verification->status === 'pending' && $verification->submitted_at) bg-amber-50 text-amber-700 border-amber-200
                @elseif($verification->status === 'rejected') bg-rose-50 text-rose-700 border-rose-200
                @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                @if($verification->status === 'approved') ✓ Disetujui Admin
                @elseif($verification->status === 'pending' && $verification->submitted_at) ⏳ Menunggu Review
                @elseif($verification->status === 'rejected') ✗ Ditolak Admin
                @else Belum Diverifikasi
                @endif
            </span>
            @if($verification->status !== 'approved')
            <a href="{{ route('coach.attendance.index') }}"
                class="px-4 py-2 bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#ff5722]/20 inline-flex items-center gap-1">
                <span>Verifikasi</span>
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
            @endif
        </div>
    </div>

    <div>
        <a href="{{ route('coach.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-[#ff5722] transition-colors inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali ke Dashboard Coach</span>
        </a>
    </div>
</div>
@endsection

