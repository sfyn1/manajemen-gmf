@extends('layouts.guest')

@section('title', 'Pendaftaran Berhasil — Gintung Master Fitness')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-[#08090d]">
    <div class="relative z-10 w-full max-w-lg text-center animate-slideUp">

        {{-- Success Check --}}
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center shadow-2xl">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-extrabold font-display text-white mb-2">Pendaftaran Terkirim</h1>

        @if(session('member_name'))
            <p class="text-[#f05a2a] font-semibold text-sm mb-4">Terima kasih, {{ session('member_name') }}</p>
        @endif

        <p class="text-slate-300 text-sm mb-8 leading-relaxed font-normal">
            Berkas pendaftaran dan bukti transaksi telah kami terima secara aman.<br>
            Tim Administrator GMF akan memverifikasi dalam waktu maksimal <strong class="text-white">1×24 jam</strong>.
        </p>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 mb-8 text-left space-y-4 shadow-xl">
            <h3 class="text-white font-bold text-xs uppercase tracking-wider text-center border-b border-slate-800 pb-3 font-display">Tahapan Verifikasi Selanjutya</h3>
            @php
                $nextSteps = [
                    ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Cek Email Notifikasi', 'desc' => 'Status verifikasi pendaftaran akan dikirimkan langsung ke email Anda.'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Persetujuan Admin GMF', 'desc' => 'Admin akan memeriksa kelengkapan identitas dan bukti transfer Anda.'],
                    ['icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1', 'title' => 'Akses Member & QR Code', 'desc' => 'Setelah disetujui, login ke portal member untuk mendapatkan kartu digital & QR.'],
                ];
            @endphp

            @foreach($nextSteps as $s)
            <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                <div class="w-9 h-9 rounded-xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-xs font-display">{{ $s['title'] }}</p>
                    <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}"
                class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition-all">
                Kembali ke Beranda
            </a>
            <a href="{{ route('login') }}"
                class="px-6 py-3 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                Masuk ke Halaman Login
            </a>
        </div>
    </div>
</div>
@endsection
