@extends('layouts.admin')

@section('title', 'Scan QR Presensi — Gintung Master Fitness')
@section('page-title', 'Scan QR Presensi')
@section('page-subtitle', 'Pindai QR code member untuk memverifikasi kunjungan gym')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="qrScanner()">

    {{-- Kamera Scanner --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 font-display text-base">Kamera Pindai</h3>
                    <p class="text-xs text-slate-500">Pindai QR digital pada aplikasi member</p>
                </div>
                <button @click="toggleCamera()" :class="cameraActive ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-[#f05a2a] text-white shadow-md shadow-[#f05a2a]/20'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                    <span x-text="cameraActive ? 'Matikan Kamera' : 'Aktifkan Kamera'"></span>
                </button>
            </div>

            <div class="p-6">
                {{-- Video Viewport --}}
                <div class="relative rounded-2xl overflow-hidden bg-slate-950 aspect-video mb-5 border border-slate-800">
                    <video id="qr-video" x-ref="video" class="w-full h-full object-cover" playsinline></video>
                    <canvas id="qr-canvas" x-ref="canvas" class="hidden"></canvas>

                    {{-- Scanning Reticle --}}
                    <div x-show="cameraActive" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-48 h-48 sm:w-56 sm:h-56 relative">
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-4 border-l-4 border-[#f05a2a] rounded-tl-xl"></div>
                            <div class="absolute top-0 right-0 w-10 h-10 border-t-4 border-r-4 border-[#f05a2a] rounded-tr-xl"></div>
                            <div class="absolute bottom-0 left-0 w-10 h-10 border-b-4 border-l-4 border-[#f05a2a] rounded-bl-xl"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-[#f05a2a] rounded-br-xl"></div>
                            <div class="absolute left-0 right-0 h-0.5 bg-[#f05a2a]/80 shadow-lg shadow-[#f05a2a]" style="animation: scan-line 2s linear infinite; top: 50%"></div>
                        </div>
                    </div>

                    <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center text-white p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 text-slate-500 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <p class="text-slate-300 text-xs font-semibold">Kamera Belum Aktif</p>
                        <p class="text-slate-500 text-[11px] mt-1">Klik tombol "Aktifkan Kamera" untuk memulai pemindaian otomatis</p>
                    </div>
                </div>

                {{-- Manual Input --}}
                <div class="pt-2 border-t border-slate-100">
                    <p class="text-[11px] font-semibold text-slate-400 mb-2 text-center uppercase tracking-wider">Pemasukan Kode Manual</p>
                    <div class="flex gap-2">
                        <input type="text" x-model="manualToken" placeholder="Tempelkan token / kode QR member..."
                            @keydown.enter="scanManual()"
                            class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a]">
                        <button @click="scanManual()" :disabled="!manualToken.trim()"
                            class="px-5 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 disabled:opacity-40">
                            Verifikasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hasil & Riwayat Scan --}}
    <div class="space-y-6">

        {{-- Result Status Card --}}
        <div x-show="result" x-transition class="rounded-3xl border p-6 shadow-md"
            :class="result?.success ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200'">
            <div class="flex items-center gap-4 border-b border-slate-200/60 pb-4 mb-4">
                {{-- Foto Profil Member --}}
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-white shadow-md overflow-hidden bg-slate-900 shrink-0">
                    <img :src="result?.member_photo || 'https://ui-avatars.com/api/?name=Member&background=f05a2a&color=fff'" alt="Foto Member" class="w-full h-full object-cover">
                </div>
                
                <div class="min-w-0 flex-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-1 inline-block"
                        :class="result?.success ? 'bg-emerald-200 text-emerald-900' : 'bg-rose-200 text-rose-900'"
                        x-text="result?.package_name || 'Member GMF'">
                    </span>
                    <p class="font-extrabold text-lg sm:text-xl font-display leading-tight truncate" :class="result?.success ? 'text-emerald-950' : 'text-rose-950'" x-text="result?.member_name || 'Member Tidak Dikenali'"></p>
                    <p class="text-xs font-semibold mt-1" :class="result?.success ? 'text-emerald-700' : 'text-rose-700'" x-text="result?.message"></p>
                </div>
            </div>

            <template x-if="result?.success">
                <div class="space-y-1 text-xs text-emerald-900 font-medium bg-emerald-100/60 p-3 rounded-2xl border border-emerald-200">
                    <p>💡 <strong>Identifikasi Admin:</strong> Pastikan wajah pengunjung cocok dengan pasfoto terdaftar di atas.</p>
                    <p>Masa Berlaku: <strong x-text="result.expires_on"></strong></p>
                </div>
            </template>

            <button @click="reset()" class="mt-4 w-full py-3 text-xs font-extrabold rounded-2xl transition-all shadow-md"
                :class="result?.success ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-rose-600 hover:bg-rose-700 text-white'">
                Lanjutkan Pemindaian Berikutnya →
            </button>
        </div>

        {{-- Idle State --}}
        <div x-show="!result" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
            <p class="text-slate-800 font-bold text-sm">Menunggu Hasil Pindaian</p>
            <p class="text-slate-400 text-xs mt-1">Arahkan kamera scanner ke QR Pass digital milik member</p>
        </div>

        {{-- Riwayat Scan Terakhir --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-900 font-display text-base">Riwayat Scan Hari Ini</h3>
                <a href="{{ route('admin.scan-qr.history') }}" class="text-xs font-bold text-[#f05a2a] hover:underline">Lihat Semua →</a>
            </div>
            <div class="px-6 py-3 max-h-52 overflow-y-auto scrollbar-thin">
                <template x-if="scanHistory.length === 0">
                    <p class="text-slate-400 text-xs py-6 text-center">Belum ada riwayat pemindaian hari ini</p>
                </template>
                <template x-for="h in scanHistory" :key="h.time">
                    <div class="flex items-center gap-3 py-3 border-b border-slate-100 last:border-0 text-xs">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center text-white shrink-0"
                            :class="h.success ? 'bg-emerald-500' : 'bg-rose-500'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="h.success ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 font-display" x-text="h.name"></p>
                            <p class="text-[11px] text-slate-400" x-text="h.time"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<style>
@keyframes scan-line { 0% { top: 20%; } 50% { top: 80%; } 100% { top: 20%; } }
</style>
<script>
function qrScanner() {
    return {
        cameraActive: false,
        scanning: false,
        result: null,
        manualToken: '',
        scanHistory: [],
        stream: null,
        animFrame: null,
        audioSuccess: new Audio('{{ asset("audio/success.mp3") }}'),
        audioError: new Audio('{{ asset("audio/error.mp3") }}'),

        playAudio(type) {
            try {
                const sound = type === 'success' ? this.audioSuccess : this.audioError;
                sound.currentTime = 0;
                sound.play().catch(err => console.warn('Audio play blocked by browser:', err));
            } catch (e) {
                console.error('Audio error:', e);
            }
        },

        async toggleCamera() {
            if (this.cameraActive) {
                this.stopCamera();
            } else {
                await this.startCamera();
            }
        },

        async startCamera() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                this.$refs.video.srcObject = this.stream;
                this.$refs.video.play();
                this.cameraActive = true;
                this.scan();
            } catch (e) {
                alert('Tidak dapat mengakses kamera: ' + e.message);
            }
        },

        stopCamera() {
            if (this.stream) this.stream.getTracks().forEach(t => t.stop());
            if (this.animFrame) cancelAnimationFrame(this.animFrame);
            this.cameraActive = false;
        },

        scan() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            const ctx = canvas.getContext('2d');

            const tick = () => {
                if (video.readyState === video.HAVE_ENOUGH_DATA && !this.result) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
                    if (code) {
                        this.processToken(code.data);
                        return;
                    }
                }
                this.animFrame = requestAnimationFrame(tick);
            };
            this.animFrame = requestAnimationFrame(tick);
        },

        async scanManual() {
            if (!this.manualToken.trim()) return;
            await this.processToken(this.manualToken.trim());
            this.manualToken = '';
        },

        async processToken(token) {
            if (this.animFrame) cancelAnimationFrame(this.animFrame);
            try {
                const res = await fetch('{{ route("admin.scan-qr.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ token }),
                });
                this.result = await res.json();

                if (this.result.success) {
                    this.playAudio('success');
                } else {
                    this.playAudio('error');
                }

                this.scanHistory.unshift({
                    name: this.result.member_name || 'Tidak dikenali',
                    time: new Date().toLocaleTimeString('id-ID'),
                    success: this.result.success,
                });
                if (this.scanHistory.length > 20) this.scanHistory.pop();
            } catch (e) {
                this.result = { success: false, message: 'Gagal terhubung ke server.' };
                this.playAudio('error');
            }
        },

        reset() {
            this.result = null;
            this.manualToken = '';
            if (this.cameraActive) this.scan();
        },
    };
}
</script>
@endpush
@endsection
