@extends('layouts.guest')

@section('title', 'Verifikasi OTP — Gintung Master Fitness')

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

            <h2 class="text-2xl font-extrabold font-display text-white text-center mb-1">Verifikasi Kode OTP</h2>
            <p class="text-slate-400 text-xs text-center mb-2 font-normal">Kode OTP 6 digit telah dikirimkan ke alamat email</p>
            <p class="text-[#f05a2a] font-semibold text-center text-xs mb-8">{{ $email ?? session('otp_email') }}</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.verify-otp.post') }}" class="space-y-5" x-data="otpForm()">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? session('otp_email') }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-3 text-center">Masukkan 6-Digit Kode OTP</label>

                    {{-- 6 digit OTP --}}
                    <div class="flex gap-2.5 justify-center" @keydown.backspace="focusPrev($event)" @keyup="handleInput($event)">
                        @for($i = 0; $i < 6; $i++)
                        <input
                            type="text"
                            maxlength="1"
                            inputmode="numeric"
                            pattern="[0-9]"
                            class="w-11 h-13 text-center text-lg font-bold bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-[#f05a2a] transition-all otp-digit"
                            @keypress="isNumber($event)"
                            autocomplete="off"
                        >
                        @endfor
                    </div>

                    <input type="hidden" name="code" x-model="fullCode">
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                    Verifikasi OTP
                </button>
            </form>

            <div class="mt-6 text-center space-y-3">
                <p class="text-xs text-slate-400">Tidak menerima kode?
                    <a href="{{ route('password.request') }}" class="text-[#f05a2a] font-semibold hover:underline">Kirim ulang</a>
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-300 text-xs transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Masuk
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function otpForm() {
    return {
        fullCode: '',
        handleInput(e) {
            const inputs = document.querySelectorAll('.otp-digit');
            const idx    = [...inputs].indexOf(e.target);
            if (e.target.value.length === 1 && idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            }
            this.fullCode = [...inputs].map(i => i.value).join('');
        },
        focusPrev(e) {
            const inputs = document.querySelectorAll('.otp-digit');
            const idx    = [...inputs].indexOf(e.target);
            if (!e.target.value && idx > 0) {
                inputs[idx - 1].focus();
                inputs[idx - 1].value = '';
            }
            this.fullCode = [...inputs].map(i => i.value).join('');
        },
        isNumber(e) {
            if (!/[0-9]/.test(e.key)) e.preventDefault();
        },
    };
}
</script>
@endpush
@endsection
