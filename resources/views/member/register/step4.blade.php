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
            <p class="text-slate-400 text-xs sm:text-sm font-normal">Selesaikan pembayaran sesuai nominal paket yang dipilih</p>
        </div>

        @include('member.register._step-indicator', ['currentStep' => 4])

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl animate-slideUp">
            <h2 class="text-lg font-bold font-display text-white mb-6 flex items-center gap-2.5 border-b border-slate-800 pb-4">
                <span class="w-7 h-7 rounded-lg bg-[#f05a2a] text-white text-xs font-extrabold flex items-center justify-center">4</span>
                <span>Rincian & Pembayaran QRIS</span>
            </h2>

            {{-- Ringkasan Paket --}}
            <div class="mb-6 p-5 rounded-2xl bg-slate-950/80 border border-slate-800">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-slate-500 text-xs font-medium">Paket Terpilih</p>
                        <p class="text-white font-bold text-base font-display">{{ $package->name }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">Akses keanggotaan {{ $package->duration_days }} hari</p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-500 text-xs font-medium">Total Pembayaran</p>
                        <p class="text-[#f05a2a] font-extrabold text-xl font-display">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- QRIS Section --}}
            <div class="mb-6">
                <p class="text-xs font-semibold text-slate-300 mb-3">Scan QRIS Resmi Gintung Master Fitness</p>
                <div class="flex flex-col items-center p-6 rounded-2xl bg-white border border-slate-200">
                    <div class="w-48 h-48 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-center mb-3">
                        <div class="text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-2 text-slate-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h7v7H3V3zm1 1v5h5V4H4zm1 1h3v3H5V5zM14 3h7v7h-7V3zm1 1v5h5V4h-5zm1 1h3v3h-3V5zM3 14h7v7H3v-7zm1 1v5h5v-5H4zm1 1h3v3H5v-3zM18 14h1v1h-1v-1zm-4 0h1v1h-1v-1zm1 1h1v1h-1v-1zm1 0h1v1h-1v-1zm1 1h1v1h-1v-1zm-3 0h1v1h-1v-1zm0 1h1v2h-1v-2zm3 0h2v1h-2v-1zm1 1h1v1h-1v-1zm-2 0h1v1h-1v-1zm0 1h3v1h-3v-1z"/>
                            </svg>
                            <p class="text-xs font-bold text-slate-900 uppercase">QRIS GMF Official</p>
                            <p class="text-xs font-bold text-[#f05a2a] mt-0.5">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 text-center font-medium">Gunakan aplikasi e-wallet (GoPay, OVO, ShopeePay, Dana) atau Mobile Banking</p>
                </div>
            </div>

            {{-- Form Upload Bukti --}}
            <form method="POST" action="{{ route('register.step4.post') }}" enctype="multipart/form-data"
                x-data="fileUpload()" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                        Unggah Bukti Transaksi Pembayaran <span class="text-[#f05a2a]">*</span>
                    </label>
                    <div class="relative rounded-2xl border-2 border-dashed transition-all cursor-pointer p-5 text-center"
                        :class="isDragging ? 'border-[#f05a2a] bg-[#f05a2a]/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700'"
                        @dragover.prevent="isDragging = true"
                        @dragleave="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()">

                        <input type="file" name="payment_proof" x-ref="fileInput" class="sr-only"
                            accept=".jpg,.jpeg,.png,.pdf"
                            @change="handleFile($event.target.files[0])">

                        <template x-if="!preview">
                            <div>
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-200 font-semibold text-xs mb-0.5">Tarik atau pilih foto struk / screenshot pembayaran</p>
                                <p class="text-slate-500 text-[11px]">JPG, PNG, PDF (Maksimal 3MB)</p>
                            </div>
                        </template>

                        <template x-if="preview">
                            <div>
                                <img :src="preview" alt="Preview Struk" class="max-h-40 mx-auto rounded-xl object-contain border border-slate-800">
                                <p class="mt-2 text-emerald-400 text-xs font-semibold" x-text="fileName"></p>
                                <button type="button" @click.stop="clearFile()" class="mt-1 text-xs text-slate-500 hover:text-rose-400">Ganti File</button>
                            </div>
                        </template>
                    </div>
                    @error('payment_proof') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                        Nominal Transfer <span class="text-[#f05a2a]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-xs font-bold">Rp</span>
                        <input type="number" name="payment_amount" required min="0" value="{{ old('payment_amount', $package->price) }}"
                            class="w-full pl-11 pr-4 py-3 bg-slate-950 border @error('payment_amount') border-rose-500 @else border-slate-800 @enderror rounded-xl text-white text-sm focus:outline-none focus:border-[#f05a2a] transition-all">
                    </div>
                    @error('payment_amount') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Pendaftaran akan diverifikasi oleh Administrator GMF secara langsung. Notifikasi konfirmasi dikirim via email.</span>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                    <a href="{{ route('register.step3') }}" class="text-slate-400 hover:text-white text-xs font-medium transition-colors">← Langkah 3</a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-[#f05a2a]/20">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function fileUpload() {
    return {
        isDragging: false, preview: null, fileName: '',
        handleFile(file) {
            if (!file) return;
            this.fileName = file.name;
            if (file.type.startsWith('image/')) {
                const r = new FileReader();
                r.onload = e => { this.preview = e.target.result; };
                r.readAsDataURL(file);
            } else { this.preview = null; }
        },
        handleDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer.files[0];
            if (file) { this.$refs.fileInput.files = e.dataTransfer.files; this.handleFile(file); }
        },
        clearFile() { this.preview = null; this.fileName = ''; this.$refs.fileInput.value = ''; },
    };
}
</script>
@endpush
@endsection
