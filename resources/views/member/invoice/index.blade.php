@extends('layouts.member')

@section('title', 'Tagihan & Riwayat Pembayaran — Gintung Master Fitness')
@section('page-title', 'Tagihan & Pembayaran')
@section('page-subtitle', 'Riwayat perpanjangan membership dan bukti pembayaran Anda')

@section('content')

{{-- Info Summary Card --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 mb-6">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
        <div>
            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-1">NAMA MEMBER</p>
            <p class="font-bold text-slate-800 text-sm font-display">{{ $member->full_name }}</p>
        </div>
        <div>
            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-1">PAKET AKTIF</p>
            <p class="font-bold text-slate-800 text-sm font-display">{{ $member->package?->name ?? '—' }}</p>
        </div>
        <div>
            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-1">STATUS</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase {{ $member->isActive() ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $member->isActive() ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                {{ $member->isActive() ? 'Member Aktif' : 'Kadaluarsa' }}
            </span>
        </div>
        <div>
            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-1">MASA BERLAKU</p>
            <p class="font-bold text-sm {{ ($member->membership_end_date && $member->membership_end_date->lt(now())) ? 'text-rose-600' : 'text-slate-800' }}">
                {{ $member->membership_end_date?->format('d M Y') ?? '—' }}
            </p>
        </div>
    </div>
</div>

{{-- 1. Riwayat Perpanjangan Membership --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-900 font-display text-base">Riwayat Perpanjangan Membership</h3>
            <p class="text-xs text-slate-500">Daftar transaksi perpanjangan keanggotaan gym Anda</p>
        </div>
        <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold text-xs rounded-full">{{ $renewals->count() }} Transaksi</span>
    </div>

    @if($renewals->isEmpty())
    <div class="p-8 text-center text-slate-400 text-xs font-medium">
        Belum ada riwayat perpanjangan membership.
    </div>
    @else
    <div class="divide-y divide-slate-100 text-xs">
        @foreach($renewals as $ren)
        <div class="p-5 hover:bg-slate-50/50 transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-slate-900 text-sm font-display">{{ $ren->package?->name }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $ren->payment_method === 'qris' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/60' : 'bg-amber-50 text-amber-800 border border-amber-200/60' }}">
                            {{ $ren->payment_method === 'qris' ? 'QRIS Digital' : 'Tunai di Kasir' }}
                        </span>
                    </div>
                    <p class="text-slate-400 text-[11px]">
                        Tanggal Pengajuan: {{ $ren->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="font-black text-slate-900 text-sm font-display">
                        Rp {{ number_format($ren->payment_amount, 0, ',', '.') }}
                    </span>

                    @if($ren->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Disetujui
                        </span>
                    @elseif($ren->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Konfirmasi
                        </span>
                    @endif
                </div>
            </div>

            {{-- Proof & Rejection Details --}}
            <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-slate-600">
                <div>
                    @if($ren->payment_proof_path)
                    <a href="{{ asset('storage/' . $ren->payment_proof_path) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs text-[#f05a2a] hover:underline font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Lihat Bukti Transfer QRIS</span>
                    </a>
                    @endif
                </div>

                @if($ren->status === 'rejected')
                <div class="w-full mt-2 p-4 bg-rose-50/70 border border-rose-200 rounded-2xl text-rose-900 text-xs space-y-2">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Alasan Penolakan Admin:</span>
                    </div>
                    <p class="text-rose-700 pl-6">{{ $ren->rejection_reason ?? 'Persyaratan tidak sesuai.' }}</p>

                    <div class="pt-2 border-t border-rose-200/60 font-medium">
                        <p class="font-bold text-rose-900 mb-0.5">Status Pengembalian Dana (Refund):</p>
                        <p class="text-rose-800 leading-relaxed">
                            {{ $ren->refund_notes ? $ren->refund_notes : ($ren->payment_method === 'cash' ? 'Silakan mengambil pengembalian uang tunai di kasir reception desk GMF.' : 'Silakan hubungi admin di meja kasir GMF untuk proses refund manual QRIS.') }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- 2. Riwayat Pembayaran Pendaftaran Pertama --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-900 font-display text-base">Riwayat Pendaftaran Awal</h3>
        <p class="text-xs text-slate-500">Bukti pembayaran pendaftaran pertama kali</p>
    </div>
    <div class="divide-y divide-slate-100 text-xs">
        @forelse($documents as $doc)
        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <p class="font-bold text-slate-900 text-sm font-display">{{ $member->package?->name }} (Pendaftaran Awal)</p>
                <p class="text-slate-400 text-[11px] mt-0.5">Tanggal: {{ $doc->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-black text-slate-900 text-sm font-display">Rp {{ number_format($doc->payment_amount, 0, ',', '.') }}</span>
                @if($doc->payment_proof_path)
                <a href="{{ asset('storage/' . $doc->payment_proof_path) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-[11px] flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Bukti Bayar</span>
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-slate-400 text-xs font-medium">Belum ada riwayat pendaftaran.</div>
        @endforelse
    </div>
</div>

@endsection
