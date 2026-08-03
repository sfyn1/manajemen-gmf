@extends('layouts.guest')

@section('title', 'Upload Dokumen & Pasfoto (3/4) — Gintung Master Fitness')

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
            <h1 class="text-2xl sm:text-3xl font-extrabold font-display text-white mb-1">Upload Dokumen & Pasfoto</h1>
            <p class="text-slate-400 text-xs sm:text-sm font-normal">Ambil foto profil wajah dan unggah dokumen identitas calon member GMF</p>
        </div>

        @include('member.register._step-indicator', ['currentStep' => 3])

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl animate-slideUp">
            <h2 class="text-lg font-bold font-display text-white mb-4 flex items-center gap-2.5 border-b border-slate-800 pb-4">
                <span class="w-7 h-7 rounded-lg bg-[#f05a2a] text-white text-xs font-extrabold flex items-center justify-center">3</span>
                <span>Pasfoto Profil & Dokumen Identitas</span>
            </h2>

            @php $docLabel = $package->requiresKtm() ? 'KTM / Kartu Pelajar' : 'KTP (Kartu Tanda Penduduk)'; @endphp

            <div class="mb-6 p-4 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-300 text-xs flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Foto profil wajah dan dokumen <strong>{{ $docLabel }}</strong> digunakan untuk identifikasi digital member saat presensi gym.</span>
            </div>

            <form method="POST" action="{{ route('register.step3.post') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- 1. Pasfoto Profil Wajah (Kamera / Galeri) --}}
                <div x-data="photoUpload()">
                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                        Pasfoto Profil Wajah Member <span class="text-[#f05a2a]">*</span>
                    </label>

                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 flex flex-col sm:flex-row items-center gap-4">
                        {{-- Circular Photo Preview --}}
                        <div class="relative w-28 h-28 rounded-full bg-slate-900 border-2 border-slate-700 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!photoPreview">
                                <div class="text-center text-slate-500 p-2">
                                    <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </template>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex-1 space-y-3 text-center sm:text-left">
                            <input type="file" name="profile_photo" x-ref="photoInput" class="sr-only"
                                accept="image/jpeg,image/png,image/jpg"
                                @change="handlePhoto($event.target.files[0])">

                            <div class="flex flex-wrap gap-2.5 justify-center sm:justify-start">
                                {{-- Tombol Kamera Web/Mobile Live Stream --}}
                                <button type="button" @click="startCamera()"
                                    class="px-4 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Ambil Foto Kamera</span>
                                </button>

                                {{-- Tombol Galeri/Dokumen File Picker --}}
                                <button type="button" @click="$refs.photoInput.click()"
                                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl transition-all border border-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Upload dari File</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-500">Ambil selfie wajah atau unggah file pasfoto jelas calon member. Format: JPG, PNG (Maks 5MB).</p>
                        </div>
                    </div>
                    @error('profile_photo') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror

                    {{-- Live WebCam Capture Modal --}}
                    <div x-show="showCameraModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 text-center" x-cloak>
                        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
                            <button type="button" @click="stopCamera()" class="absolute top-4 right-4 text-slate-400 hover:text-white p-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <h3 class="font-bold text-white font-display text-base mb-1">Ambil Foto Wajah Member</h3>
                            <p class="text-xs text-slate-400 mb-4">Posisikan wajah Anda di tengah lingkaran kamera</p>

                            <div class="relative w-64 h-64 mx-auto rounded-full overflow-hidden border-4 border-[#f05a2a] bg-slate-950 mb-6 shadow-2xl">
                                <video x-ref="webcamVideo" class="w-full h-full object-cover" autoplay playsinline></video>
                                <canvas x-ref="webcamCanvas" class="hidden"></canvas>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="stopCamera()" class="flex-1 py-3 bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold rounded-xl border border-slate-700">
                                    Batal
                                </button>
                                <button type="button" @click="takeSnapshot()" class="flex-1 py-3 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl shadow-lg shadow-[#f05a2a]/30 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Jepret Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Dokumen Identitas (KTP/KTM) --}}
                <div x-data="docUpload()">
                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                        Unggah Dokumen {{ $docLabel }} <span class="text-[#f05a2a]">*</span>
                    </label>

                    {{-- Drop Zone --}}
                    <div
                        class="relative rounded-2xl border-2 border-dashed transition-all duration-200 cursor-pointer p-6 text-center"
                        :class="isDragging ? 'border-[#f05a2a] bg-[#f05a2a]/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700'"
                        @dragover.prevent="isDragging = true"
                        @dragleave="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.docInput.click()">

                        <input type="file" name="identity_document" x-ref="docInput" class="sr-only"
                            accept=".jpg,.jpeg,.png,.pdf"
                            @change="handleFile($event.target.files[0])">

                        <template x-if="!preview">
                            <div>
                                <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <p class="text-slate-200 font-semibold text-xs mb-1">Klik atau seret file {{ $docLabel }} ke sini</p>
                                <p class="text-slate-500 text-[11px]">Format: JPG, PNG, PDF (Maksimal 5MB)</p>
                            </div>
                        </template>

                        <template x-if="preview">
                            <div>
                                <img :src="preview" alt="Preview Dokumen" class="max-h-48 mx-auto rounded-xl object-contain border border-slate-800">
                                <p class="mt-3 text-emerald-400 text-xs font-semibold" x-text="fileName"></p>
                                <button type="button" @click.stop="clearFile()" class="mt-2 text-xs text-slate-500 hover:text-rose-400 transition-colors">
                                    Ganti File Dokumen
                                </button>
                            </div>
                        </template>
                    </div>
                    @error('identity_document') <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                    <a href="{{ route('register.step2') }}" class="text-slate-400 hover:text-white text-xs font-medium transition-colors">← Langkah 2</a>
                    <button type="submit"
                        class="px-7 py-3 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                        Lanjut ke Pembayaran →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function photoUpload() {
    return {
        photoPreview: null,
        showCameraModal: false,
        stream: null,

        async startCamera() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 640 } }
                });
                this.$refs.webcamVideo.srcObject = this.stream;
                this.$refs.webcamVideo.play();
                this.showCameraModal = true;
            } catch (err) {
                alert('Tidak dapat mengakses kamera: ' + err.message + '. Silakan gunakan tombol Upload dari Galeri/File.');
            }
        },

        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.showCameraModal = false;
        },

        takeSnapshot() {
            const video = this.$refs.webcamVideo;
            const canvas = this.$refs.webcamCanvas;
            const ctx = canvas.getContext('2d');

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 640;

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            this.photoPreview = dataUrl;

            // Convert DataURL to File object and set into input
            fetch(dataUrl)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], "pasfoto_kamera.jpg", { type: "image/jpeg" });
                    const container = new DataTransfer();
                    container.items.add(file);
                    this.$refs.photoInput.files = container.files;
                });

            this.stopCamera();
        },

        handlePhoto(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => { this.photoPreview = e.target.result; };
            reader.readAsDataURL(file);
        }
    };
}

function docUpload() {
    return {
        isDragging: false, preview: null, fileName: '',
        handleFile(file) {
            if (!file) return;
            this.fileName = file.name;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => { this.preview = e.target.result; };
                reader.readAsDataURL(file);
            } else { this.preview = null; }
        },
        handleDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer.files[0];
            if (file) {
                this.$refs.docInput.files = e.dataTransfer.files;
                this.handleFile(file);
            }
        },
        clearFile() {
            this.preview = null; this.fileName = ''; this.$refs.docInput.value = '';
        },
    };
}
</script>
@endpush
@endsection
