@extends('layouts.admin')

@section('title', 'Riwayat Kunjungan Gym — Gintung Master Fitness')
@section('page-title', 'Riwayat Kunjungan Gymnasium')
@section('page-subtitle', 'Seluruh riwayat presensi masuk member melalui scan QR Pass')

@section('content')

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.scan-qr.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Scanner</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-900 font-display text-base">Histori Presensi Masuk</h3>
                    <p class="text-xs text-slate-500">Catatan kunjungan lokasi fitness</p>
                </div>
                <span class="text-xs font-bold text-slate-400">Total: {{ $visits->total() }} Kunjungan</span>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Member</th>
                            <th class="px-6 py-4">Paket Membership</th>
                            <th class="px-6 py-4">Waktu Presensi</th>
                            <th class="px-6 py-4 text-right">Status Keanggotaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($visits as $visit)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-[#f05a2a] flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($visit->member->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm font-display">{{ $visit->member->full_name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">NIK: {{ $visit->member->nik }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#f05a2a]/15 text-[#f05a2a]">
                                    {{ $visit->member->package?->name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">
                                {{ $visit->visited_at ? $visit->visited_at->translatedFormat('d M Y, H:i:s') : '—' }} WIB
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php $isActive = $visit->member->status === \App\Models\Member::STATUS_ACTIVE; @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    {{ $isActive ? 'Aktif' : ucfirst($visit->member->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="font-bold text-slate-800 text-sm">Belum Ada Riwayat Kunjungan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($visits->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $visits->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
