@extends('layouts.admin')

@section('title', 'Detail Member — ' . $member->full_name)
@section('page-title', 'Detail Member')
@section('page-subtitle', $member->full_name)

@push('styles')
<style>
    .qr-img { image-rendering: pixelated; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ── Breadcrumb & Actions ──────────────────────────────────────────── --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.membership.index') }}" class="hover:text-[#f05a2a] transition-colors">Membership</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800 font-medium">{{ $member->full_name }}</span>
        </nav>
        <a href="{{ route('admin.membership.edit', $member) }}"
           class="inline-flex items-center gap-2 bg-[#f05a2a] hover:bg-[#c8451a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm shadow-[#f05a2a]/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Data
        </a>
    </div>

    {{-- ── 2-Column Layout ──────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ═══════════════════════ KOLOM KIRI (3/5) ══════════════════════ --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Card Info Member --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- Header dengan gradient --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-4"
                     style="background: linear-gradient(135deg, #1e3a5f08, #f05a2a08);">
                    @php
                        $initials = collect(explode(' ', $member->full_name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                        $statusMap = [
                            'active'               => ['label' => 'Aktif',    'class' => 'bg-green-100 text-green-700'],
                            'pending_verification' => ['label' => 'Pending Verifikasi', 'class' => 'bg-yellow-100 text-yellow-700'],
                            'rejected'             => ['label' => 'Ditolak',  'class' => 'bg-red-100 text-red-700'],
                            'expired'              => ['label' => 'Kadaluarsa','class' => 'bg-gray-100 text-gray-500'],
                        ];
                        $badge = $statusMap[$member->status] ?? ['label' => $member->status, 'class' => 'bg-gray-100 text-gray-600'];
                    @endphp
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#f05a2a] to-[#c8451a] flex items-center justify-center text-white font-bold text-xl shrink-0 shadow-md shadow-[#f05a2a]/25">
                        {{ $initials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-bold text-gray-800 font-display truncate">{{ $member->full_name }}</h2>
                        <p class="text-sm text-gray-500">{{ $member->email }}</p>
                        <span class="mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>
                </div>

                {{-- Info grid --}}
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $infoFields = [
                            ['label' => 'NIK',         'value' => $member->nik ?? '—',  'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2'],
                            ['label' => 'Jenis Kelamin','value' => $member->gender === 'male' ? 'Laki-laki' : ($member->gender === 'female' ? 'Perempuan' : '—'), 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            ['label' => 'Tanggal Lahir','value' => $member->birth_date ? $member->birth_date->format('d M Y') : '—', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'No. Telepon', 'value' => $member->phone ?? '—', 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                            ['label' => 'Paket',       'value' => $member->package?->name ?? '—', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                            ['label' => 'Tgl Daftar',  'value' => $member->created_at->format('d M Y'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Mulai',       'value' => $member->membership_start_date ? $member->membership_start_date->format('d M Y') : '—', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'Berakhir',    'value' => $member->membership_end_date ? $member->membership_end_date->format('d M Y') : '—', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'red' => $member->membership_end_date?->isPast()],
                        ];
                    @endphp

                    @foreach($infoFields as $field)
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50/70 hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $field['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400 leading-tight">{{ $field['label'] }}</p>
                            <p class="text-sm font-semibold {{ ($field['red'] ?? false) ? 'text-red-500' : 'text-gray-800' }} mt-0.5 break-words">
                                {{ $field['value'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach

                    {{-- Alamat - full width --}}
                    @if($member->address)
                    <div class="sm:col-span-2 flex items-start gap-3 p-3 rounded-xl bg-gray-50/70">
                        <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Alamat</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $member->address }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Alasan penolakan & Aksi On-Site Admin Desk --}}
                    @if($member->status === 'rejected')
                    <div class="sm:col-span-2 p-4 rounded-xl bg-rose-50 border border-rose-200 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-extrabold text-rose-700 uppercase tracking-wider">Status Pendaftaran Ditolak</p>
                                <p class="text-sm font-semibold text-rose-900 mt-0.5">Alasan: {{ $member->rejection_reason ?? 'Pendaftaran ditolak oleh administrator' }}</p>
                                <p class="text-xs text-rose-600 mt-1">Calon member dapat melakukan refund uang tunai/QRIS di kasir GMF atau menyelesaikan pendaftaran langsung di tempat.</p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-rose-200/60 flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('admin.approval.member.approve', $member) }}" onsubmit="return confirm('Setujui dan aktifkan membership {{ $member->full_name }} secara langsung dari Kasir?')">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui & Aktifkan Member Sekarang (Kasir On-Site)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Kunjungan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-[#1e3a5f]/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#1e3a5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 font-display">Riwayat Kunjungan</h3>
                    </div>
                    <span class="text-xs text-gray-400">10 terakhir</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($visits as $visit)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50/50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $visit->visited_at->format('l, d M Y') }}</p>
                            <p class="text-xs text-gray-400">Masuk pukul {{ $visit->visited_at->format('H:i') }} WIB</p>
                        </div>
                        <span class="text-xs text-gray-300 font-mono">{{ $visit->visited_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm">Belum ada riwayat kunjungan</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ═══════════════════════ KOLOM KANAN (2/5) ═════════════════════ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card Dokumen --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#f05a2a]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#f05a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 font-display">Dokumen</h3>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($member->documents as $doc)
                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                {{ strtoupper($doc->identity_document_type ?? 'KTP') }}
                                + Bukti Bayar
                            </span>
                            <span class="text-xs text-gray-400">
                                Rp {{ number_format($doc->payment_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            {{-- Identity document --}}
                            @if($doc->identity_document_path)
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Identitas</p>
                                @if(in_array(pathinfo($doc->identity_document_path, PATHINFO_EXTENSION), ['jpg','jpeg','png','webp']))
                                <a href="{{ Storage::url($doc->identity_document_path) }}" target="_blank">
                                    <img src="{{ Storage::url($doc->identity_document_path) }}"
                                         alt="Identitas" class="w-full h-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity qr-img">
                                </a>
                                @else
                                <a href="{{ Storage::url($doc->identity_document_path) }}" target="_blank"
                                   class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors text-xs text-[#f05a2a] font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Lihat File
                                </a>
                                @endif
                            </div>
                            @endif
                            {{-- Payment proof --}}
                            @if($doc->payment_proof_path)
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Bukti Bayar</p>
                                @if(in_array(pathinfo($doc->payment_proof_path, PATHINFO_EXTENSION), ['jpg','jpeg','png','webp']))
                                <a href="{{ Storage::url($doc->payment_proof_path) }}" target="_blank">
                                    <img src="{{ Storage::url($doc->payment_proof_path) }}"
                                         alt="Bukti Bayar" class="w-full h-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity">
                                </a>
                                @else
                                <a href="{{ Storage::url($doc->payment_proof_path) }}" target="_blank"
                                   class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors text-xs text-[#f05a2a] font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Lihat File
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">Belum ada dokumen</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Card QR Code --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#f05a2a]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#f05a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 font-display">QR Code Member</h3>
                </div>
                <div class="p-6 flex flex-col items-center">
                    @if($member->status === 'active' && $member->qr_token)
                    <div class="p-3 bg-white border-2 border-gray-200 rounded-2xl shadow-inner mb-3">
                        {{-- QR code via Google Chart API --}}
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('qr.verify', $member->qr_token)) }}&format=png&margin=2"
                             alt="QR Code {{ $member->full_name }}"
                             class="w-44 h-44 qr-img rounded-lg">
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-1 mb-3">Scan untuk verifikasi kunjungan</p>
                    <code class="text-[10px] bg-gray-100 text-gray-600 px-2 py-1 rounded-lg font-mono break-all text-center">
                        {{ substr($member->qr_token, 0, 24) }}...
                    </code>
                    <p class="text-xs text-gray-400 mt-2">
                        Berlaku hingga:
                        <span class="font-semibold text-gray-600">
                            {{ $member->membership_end_date?->format('d M Y') ?? '—' }}
                        </span>
                    </p>
                    @elseif($member->status === 'active' && !$member->qr_token)
                    <div class="text-center py-4">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-yellow-50 border border-yellow-200 flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">QR Token Belum Ada</p>
                        <p class="text-xs text-gray-400 mt-1">Token akan dibuat otomatis saat approval</p>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center mb-3 opacity-50">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">QR Tidak Tersedia</p>
                        <p class="text-xs text-gray-400 mt-1">Member harus berstatus aktif untuk mendapatkan QR</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
