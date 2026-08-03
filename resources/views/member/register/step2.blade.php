@extends('layouts.guest')

@section('title', 'Pilihan Paket (2/4) — Gintung Master Fitness')

@section('content')
<div class="min-h-screen py-12 px-4 bg-[#08090d]">
    <div class="max-w-4xl mx-auto">
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
            <h1 class="text-2xl sm:text-3xl font-extrabold font-display text-white mb-1">Pilih Paket Membership</h1>
            <p class="text-slate-400 text-xs sm:text-sm font-normal">Pilih paket yang paling sesuai dengan kebutuhan latihan Anda</p>
        </div>

        @include('member.register._step-indicator', ['currentStep' => 2])

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl animate-slideUp">
            <h2 class="text-lg font-bold font-display text-white mb-6 flex items-center gap-2.5 border-b border-slate-800 pb-4">
                <span class="w-7 h-7 rounded-lg bg-[#f05a2a] text-white text-xs font-extrabold flex items-center justify-center">2</span>
                <span>Pilih Paket Keanggotaan</span>
            </h2>

            <form method="POST" action="{{ route('register.step2.post') }}" x-data="{ selected: null }">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    @foreach($packages as $package)
                    @php
                        $highlights = [
                            'monthly_regular' => ['Akses penuh 30 hari', 'Booking kelas grup', 'Presensi QR digital', 'KTP valid'],
                            'daily'           => ['Kunjungan gym 1 hari', 'Kode QR 24 jam', 'Tanpa booking kelas', 'KTP valid'],
                            'monthly_student' => ['Akses 30 hari khusus pelajar', 'Booking kelas grup', 'Tarif hemat pelajar', 'KTM / Kartu Pelajar'],
                        ];
                        $feats = $highlights[$package->type] ?? [];
                    @endphp
                    <label class="cursor-pointer group" x-on:click="selected = {{ $package->id }}">
                        <input type="radio" name="membership_package_id" value="{{ $package->id }}" class="sr-only" :checked="selected === {{ $package->id }}">
                        <div class="relative h-full rounded-2xl border-2 p-6 transition-all duration-300 flex flex-col justify-between"
                            :class="selected === {{ $package->id }}
                                ? 'border-[#f05a2a] bg-slate-950 shadow-xl shadow-[#f05a2a]/15'
                                : 'border-slate-800 bg-slate-950/60 hover:border-slate-700'">

                            {{-- Selected Badge Indicator --}}
                            <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                                :class="selected === {{ $package->id }} ? 'border-[#f05a2a] bg-[#f05a2a]' : 'border-slate-700 bg-slate-900'">
                                <svg x-show="selected === {{ $package->id }}" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <div>
                                <div class="w-10 h-10 rounded-xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-white mb-1 font-display">{{ $package->name }}</h3>
                                <p class="text-2xl font-extrabold text-[#f05a2a] font-display mb-1">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-slate-500 mb-5 font-medium">
                                    Masa berlaku {{ $package->duration_days }} hari
                                </p>

                                <ul class="space-y-2.5">
                                    @foreach($feats as $feat)
                                    <li class="flex items-start gap-2.5 text-xs text-slate-300">
                                        <svg class="w-3.5 h-3.5 text-[#f05a2a] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>{{ $feat }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                @error('membership_package_id')
                    <p class="text-rose-400 text-xs font-medium mb-4">{{ $message }}</p>
                @enderror

                <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                    <a href="{{ route('register.step1') }}" class="text-slate-400 hover:text-white text-xs font-medium transition-colors">← Langkah 1</a>
                    <button type="submit" :disabled="!selected"
                        class="px-7 py-3 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 disabled:opacity-40 disabled:cursor-not-allowed">
                        Lanjut Upload Dokumen →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
