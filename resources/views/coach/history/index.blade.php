@extends('layouts.coach')

@section('title', 'Riwayat Mengajar — Gintung Master Fitness')
@section('page-title', 'Riwayat Sesi Mengajar')
@section('page-subtitle', 'Daftar riwayat kelas yang telah selesai Anda ajar')

@section('content')
<div class="bento-card rounded-2xl overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
            <div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Riwayat Sesi Mengajar</h3>
                <p class="text-xs text-slate-500">Catatan kelas dan status verifikasi komisi</p>
            </div>
        </div>
        <span class="material-symbols-outlined text-slate-400">history</span>
    </div>
    
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                <tr>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Jam</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Komisi Sesi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($verifications as $v)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 font-extrabold text-slate-900 font-display">{{ $v->schedule->classType->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $v->session_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-slate-400 font-semibold">{{ \Carbon\Carbon::parse($v->schedule->start_time)->format('H:i') }} WIB</td>
                    <td class="px-6 py-4">
                        @php
                            $badgeClass = match($v->status) {
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200'
                            };
                            $statusLabel = match($v->status) {
                                'approved' => 'Disetujui',
                                'pending' => 'Pending',
                                'rejected' => 'Ditolak',
                                'auto_failed' => 'Auto-Gagal',
                                default => ucfirst($v->status)
                            };
                        @endphp
                        <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold border {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-extrabold font-display {{ $v->status === 'approved' ? 'text-emerald-600' : 'text-slate-300' }}">
                        {{ $v->status === 'approved' ? 'Rp ' . number_format($coach->rate_per_session, 0, ',', '.') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-medium">Belum ada riwayat mengajar tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($verifications->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">{{ $verifications->links() }}</div>
    @endif
</div>
@endsection

