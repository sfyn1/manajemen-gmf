@extends('layouts.guest')

@section('title', 'Pembayaran (4/4) — Gintung Master Fitness')

@section('content')
<div class="min-h-screen py-12 px-4 bg-[#08090d]">
    <div class="max-w-2xl mx-auto">
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
            <h1 class="text-2xl sm:text-3xl font-extrabold font-display text-white mb-1">Pembayaran & Konfirmasi</h1>
            <p class="text-slate-400 text-xs sm:text-sm font-normal">Selesaikan pembayaran instan via Midtrans Payment Gateway (Sandbox)</p>
        </div>

        @include('member.register._step-indicator', ['currentStep' => 4])

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl animate-slideUp space-y-6">
            <h2 class="text-lg font-bold font-display text-white border-b border-slate-800 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-[#f05a2a] text-white text-xs font-extrabold flex items-center justify-center">4</span>
                    <span>Metode Pembayaran Online</span>
                </div>
                <span class="text-[11px] font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-md">
                    Midtrans Sandbox
                </span>
            </h2>

            {{-- Ringkasan Paket --}}
            <div class="p-5 rounded-2xl bg-slate-950/80 border border-slate-800">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-slate-500 text-xs font-medium">Paket Terpilih</p>
                        <p class="text-white font-bold text-base font-display">{{ $package->name }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">Akses keanggotaan {{ $package->duration_days }} hari</p>
                        <p class="text-slate-500 text-[11px] mt-1">Order ID: <code class="text-slate-300 font-mono">{{ $orderId }}</code></p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-500 text-xs font-medium">Total Pembayaran</p>
                        <p class="text-[#f05a2a] font-extrabold text-xl font-display">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- OPSI 1: MIDTRANS SNAP POPUP (UTAMA & OTOMATIS) --}}
            <div class="p-6 rounded-2xl bg-gradient-to-b from-slate-950 to-slate-900 border border-slate-800 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center font-bold shrink-0 border border-[#f05a2a]/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm">Pembayaran Instan via Midtrans</h3>
                        <p class="text-slate-400 text-xs">Mendukung QRIS (GoPay, ShopeePay, Dana), Virtual Account (BCA, Mandiri, BNI, BRI), & Kartu Kredit (Sandbox Mode).</p>
                    </div>
                </div>

                @if($snapToken)
                <button type="button" id="pay-button"
                    class="w-full py-4 bg-[#f05a2a] hover:bg-[#e13b12] text-white font-extrabold text-sm rounded-2xl transition-all shadow-lg shadow-[#f05a2a]/25 flex items-center justify-center gap-2 border-0 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>Bayar Sekarang via Midtrans Snap →</span>
                </button>
                @else
                <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs">
                    Token Midtrans sedang tidak tersedia.
                </div>
                @endif
            </div>

            {{-- Form Tersembunyi untuk Submit Otomatis via Midtrans --}}
            <form id="midtrans-reg-form" method="POST" action="{{ route('register.step4.post') }}">
                @csrf
                <input type="hidden" name="payment_amount" value="{{ $package->price }}">
                <input type="hidden" name="payment_type" value="midtrans_snap">
                <input type="hidden" name="payment_status" id="payment-status-input" value="settlement">

                <div class="flex justify-start items-center pt-4 border-t border-slate-800">
                    <a href="{{ route('register.step3') }}" class="text-slate-400 hover:text-white text-xs font-medium transition-colors">← Langkah 3</a>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
@if($snapToken)
@php
    $snapJsUrl = config('midtrans.is_production') 
        ? 'https://app.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
<script src="{{ $snapJsUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                console.log('Payment success:', result);
                document.getElementById('payment-status-input').value = 'settlement';
                document.getElementById('midtrans-reg-form').submit();
            },
            onPending: function (result) {
                console.log('Payment pending:', result);
                document.getElementById('payment-status-input').value = 'settlement';
                document.getElementById('midtrans-reg-form').submit();
            },
            onError: function (result) {
                alert('Pembayaran gagal atau terjadi kesalahan.');
                console.log('Payment error:', result);
            },
            onClose: function () {
                console.log('Customer closed Snap popup without finishing payment.');
            }
        });
    };
</script>
@endif
@endpush
@endsection
