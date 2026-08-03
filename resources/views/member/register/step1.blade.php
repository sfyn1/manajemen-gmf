@extends('layouts.guest')

@section('title', 'Pendaftaran Member (1/4) — Gintung Master Fitness')

@section('content')
<div class="min-h-screen py-12 px-4 bg-[#08090d]">

    <div class="max-w-2xl mx-auto">
        {{-- Brand Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-4">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-10 w-auto object-contain">
                @endif
                <div class="text-left">
                    <span class="text-white font-extrabold font-display text-base leading-tight block">Gintung Master</span>
                    <span class="text-[#f05a2a] font-bold text-xs uppercase tracking-wider block">Fitness Center</span>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-display text-white mb-1">Formulir Pendaftaran Member</h1>
            <p class="text-slate-400 text-xs sm:text-sm font-normal">Lengkapi data pribadi Anda secara lengkap dan akurat</p>
        </div>

        @include('member.register._step-indicator', ['currentStep' => 1])

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl animate-slideUp">
            <h2 class="text-lg font-bold font-display text-white mb-6 flex items-center gap-2.5 border-b border-slate-800 pb-4">
                <span class="w-7 h-7 rounded-lg bg-[#f05a2a] text-white text-xs font-extrabold flex items-center justify-center">1</span>
                <span>Data Pribadi Member</span>
            </h2>

            <form method="POST" action="{{ route('register.step1.post') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nama Lengkap --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Nama Lengkap <span class="text-[#f05a2a]">*</span></label>
                        <input type="text" name="full_name" required value="{{ old('full_name') }}" placeholder="Sesuai Kartu Identitas (KTP)"
                            class="w-full px-4 py-3 bg-slate-950 border @error('full_name') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        @error('full_name') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">NIK (16 Digit) <span class="text-[#f05a2a]">*</span></label>
                        <input type="text" name="nik" required maxlength="16" inputmode="numeric" value="{{ old('nik') }}" placeholder="3271xxxxxxxxx"
                            class="w-full px-4 py-3 bg-slate-950 border @error('nik') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        @error('nik') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Jenis Kelamin <span class="text-[#f05a2a]">*</span></label>
                        <select name="gender" required
                            class="w-full px-4 py-3 bg-slate-950 border @error('gender') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                            <option value="" class="bg-slate-900">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }} class="bg-slate-900">Laki-laki</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }} class="bg-slate-900">Perempuan</option>
                        </select>
                        @error('gender') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Tanggal Lahir <span class="text-[#f05a2a]">*</span></label>
                        <input type="date" name="birth_date" required value="{{ old('birth_date') }}"
                            class="w-full px-4 py-3 bg-slate-950 border @error('birth_date') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        @error('birth_date') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Nomor Telepon / WhatsApp <span class="text-[#f05a2a]">*</span></label>
                        <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3 bg-slate-950 border @error('phone') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        @error('phone') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Alamat Email Aktif <span class="text-[#f05a2a]">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@email.com"
                            class="w-full px-4 py-3 bg-slate-950 border @error('email') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                        <p class="mt-1 text-[11px] text-slate-500">Kredensial dan status verifikasi akan dikirimkan ke email ini</p>
                        @error('email') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Alamat Lengkap <span class="text-[#f05a2a]">*</span></label>
                        <textarea name="address" required rows="3" placeholder="Alamat domisili lengkap"
                            class="w-full px-4 py-3 bg-slate-950 border @error('address') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#f05a2a] transition-all resize-none">{{ old('address') }}</textarea>
                        @error('address') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                    <a href="{{ route('home') }}" class="text-slate-400 hover:text-white text-xs font-medium transition-colors">Kembali</a>
                    <button type="submit"
                        class="px-7 py-3 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                        Lanjut ke Pilih Paket →
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-[#f05a2a] font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection
