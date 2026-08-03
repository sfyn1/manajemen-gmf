@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi — Gintung Master Fitness')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-[#08090d]">

    <div class="relative z-10 w-full max-w-md animate-slideUp">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">

            <div class="flex justify-center mb-6">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-14 w-auto object-contain">
                @else
                    <div class="w-12 h-12 rounded-xl bg-[#f05a2a]/20 border border-[#f05a2a]/40 text-[#f05a2a] flex items-center justify-center font-bold text-sm">
                        GMF
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-extrabold font-display text-white text-center mb-1">Lupa Kata Sandi</h2>
            <p class="text-slate-400 text-xs text-center mb-8 font-normal">Masukkan email terdaftar Anda untuk menerima kode OTP pemulihan akun.</p>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.send-otp') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-2">Alamat Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        class="w-full px-4 py-3 bg-slate-950 border @error('email') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                    @error('email') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                    Kirim Kode OTP
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-xs font-medium transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Halaman Masuk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
