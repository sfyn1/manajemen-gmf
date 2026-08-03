@extends('layouts.coach')
@section('page-title', 'Daftar Peserta Kelas')
@section('page-subtitle', $schedule->classType->name . ' — ' . $parsedDate->translatedFormat('l, d M Y'))
@section('content')

<div class="max-w-4xl">
    {{-- Header Info --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex flex-wrap gap-4 items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 font-display">{{ $schedule->classType->name }}</h2>
                <p class="text-gray-500 mt-1">
                    {{ $parsedDate->translatedFormat('l, d F Y') }} •
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                </p>
            </div>
            <div class="flex gap-3">
                <div class="text-center px-4 py-2 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-2xl font-black text-blue-600">{{ $bookings->count() }}</p>
                    <p class="text-xs text-blue-500">Peserta Booking</p>
                </div>
                <div class="text-center px-4 py-2 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-2xl font-black text-green-600">{{ $schedule->max_capacity }}</p>
                    <p class="text-xs text-green-500">Kapasitas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Roster table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-700">Daftar Peserta</h3>
        </div>
        @if($bookings->isEmpty())
        <div class="p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm">Belum ada peserta yang booking untuk kelas ini</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-semibold">#</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-semibold">Nama Member</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-semibold">No. HP</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-semibold">Status Booking</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-semibold">Biaya Sesi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($bookings as $i => $booking)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs">
                                {{ strtoupper(substr($booking->member->full_name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $booking->member->full_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $booking->member->phone }}</td>
                    <td class="px-6 py-3">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                            @if($booking->status === 'booked') bg-blue-100 text-blue-700
                            @elseif($booking->status === 'attended') bg-green-100 text-green-700
                            @elseif($booking->status === 'no_show') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-600">Rp {{ number_format($booking->payment_amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Verification status --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-700 mb-4">Status Verifikasi Kehadiran</h3>
        <div class="flex items-center gap-4">
            <span class="inline-flex px-3 py-1.5 rounded-full text-sm font-semibold
                @if($verification->status === 'approved') bg-green-100 text-green-700
                @elseif($verification->status === 'pending' && $verification->submitted_at) bg-yellow-100 text-yellow-700
                @elseif($verification->status === 'rejected') bg-red-100 text-red-700
                @else bg-gray-100 text-gray-600 @endif">
                @if($verification->status === 'approved') ✓ Disetujui Admin
                @elseif($verification->status === 'pending' && $verification->submitted_at) ⏳ Menunggu Review Admin
                @elseif($verification->status === 'rejected') ✗ Ditolak Admin
                @else Belum Diverifikasi
                @endif
            </span>
            @if($verification->status !== 'approved')
            <a href="{{ route('coach.attendance.index') }}"
                class="px-4 py-2 bg-[#14532d] hover:bg-[#0f3d20] text-white text-sm font-semibold rounded-xl transition-colors">
                Verifikasi Sekarang →
            </a>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('coach.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
