@extends('layouts.guest')

@section('title', 'Atur Ulang Kata Sandi — Gintung Master Fitness')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-[#08090d]">

    <div class="relative z-10 w-full max-w-md animate-slideUp">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">

            <div class="flex justify-center mb-6">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-14 w-auto object-contain">
                @else
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center font-bold text-sm">
                        GMF
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-extrabold font-display text-white text-center mb-1">Kata Sandi Baru</h2>
            <p class="text-slate-400 text-xs text-center mb-8 font-normal">Kode OTP terverifikasi. Buat kata sandi baru untuk akun Anda.</p>

            <form method="POST" action="{{ route('password.reset.post') }}" class="space-y-5" x-data="{ show1: false, show2: false }">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? session('reset_email') }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="show1 ? 'text' : 'password'" name="password" required minlength="8"
                            placeholder="Minimal 8 karakter"
                            class="w-full pl-4 pr-11 py-3 bg-slate-950 border @error('password') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        <button type="button" @click="show1 = !show1" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300">
                            <svg x-show="!show1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="show2 ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="Ulangi kata sandi baru"
                            class="w-full pl-4 pr-11 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        <button type="button" @click="show2 = !show2" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300">
                            <svg x-show="!show2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                    Simpan Kata Sandi Baru
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
