@extends('layouts.guest')

@section('title', 'Masuk Akun — Gintung Master Fitness')
@section('meta_description', 'Masuk ke portal akun Gintung Master Fitness.')

@section('content')
<div class="min-h-screen flex" x-data>

    {{-- LEFT PANEL — Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-[#08090d] via-[#12141c] to-[#08090d] items-center justify-center p-12 border-r border-slate-800">

        {{-- Background Glow --}}
        <div class="absolute top-1/4 left-1/4 w-80 h-80 bg-[#f05a2a]/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-[#e13b12]/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative z-10 max-w-lg text-center">
            {{-- GMF Logo --}}
            <div class="flex justify-center mb-8">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-28 w-auto object-contain drop-shadow-2xl">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-[#f05a2a] to-[#e13b12] flex items-center justify-center text-white font-extrabold text-2xl shadow-xl shadow-[#f05a2a]/20">
                        GMF
                    </div>
                @endif
            </div>

            <h1 class="text-3xl font-extrabold font-display text-white mb-3 tracking-tight">
                Gintung Master <span class="text-[#f05a2a]">Fitness</span>
            </h1>
            <p class="text-slate-400 text-sm leading-relaxed mb-10 font-normal">
                Sistem Informasi Manajemen Gymnasium & Center Training Terpadu.
            </p>

            {{-- Feature list without emojis --}}
            <div class="space-y-4 text-left">
                @php
                    $features = [
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Manajemen Keanggotaan', 'desc' => 'Integrasi pendaftaran member, paket reguler, pelajar, dan harian.'],
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Monitoring & Laporan Realtime', 'desc' => 'Informasi pendapatan, absensi QR, dan rekapitulasi data center.'],
                        ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Reservasi Kelas Terjadwal', 'desc' => 'Akses mudah untuk booking sesi Zumba, Pound Fit, dan Yoga.'],
                    ];
                @endphp

                @foreach($features as $f)
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/60 border border-slate-800 backdrop-blur-sm">
                    <div class="w-10 h-10 rounded-xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm font-display">{{ $f['title'] }}</p>
                        <p class="text-slate-400 text-xs mt-0.5 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL — Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-[#08090d]">
        <div class="w-full max-w-md animate-slideUp">

            {{-- Header Logo for Mobile --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-10 w-auto object-contain">
                @endif
                <div>
                    <p class="text-white font-extrabold font-display text-base leading-tight">Gintung Master</p>
                    <p class="text-[#f05a2a] font-bold text-xs leading-tight">Fitness Center</p>
                </div>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold font-display text-white mb-2">Masuk Akun</h2>
            <p class="text-slate-400 text-xs sm:text-sm mb-8 font-normal">Silakan masukkan email dan kata sandi Anda.</p>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Email Input --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            placeholder="nama@email.com"
                            class="w-full pl-11 pr-4 py-3 bg-slate-900 border @error('email') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Input --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="text-xs font-semibold text-slate-300">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-[#f05a2a] hover:underline font-medium">Lupa kata sandi?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            :type="show ? 'text' : 'password'"
                            name="password"
                            id="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full pl-11 pr-11 py-3 bg-slate-900 border @error('password') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all"
                        >
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2.5">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-[#f05a2a] focus:ring-[#f05a2a]">
                    <label for="remember" class="text-xs text-slate-400 cursor-pointer">Ingat sesi saya</label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-3.5 px-6 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            {{-- Footer Links --}}
            <div class="mt-8 text-center space-y-3">
                <p class="text-slate-400 text-xs">
                    Belum terdaftar sebagai member?
                    <a href="{{ route('register.step1') }}" class="text-[#f05a2a] font-semibold hover:underline ml-1">
                        Daftar Member Baru
                    </a>
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-300 text-xs transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
