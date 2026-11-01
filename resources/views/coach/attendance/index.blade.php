@extends('layouts.coach')

@section('title', 'Verifikasi Kehadiran — Gintung Master Fitness')
@section('page-title', 'Verifikasi Kehadiran Sesi')
@section('page-subtitle', 'Unggah foto bukti kehadiran sesi mengajar kelas kebugaran')

@section('content')

<div x-data="{ tab: 'todo' }">

    {{-- Tabs (Mobile-Friendly Pill Tabs) --}}
    <div class="flex gap-2 mb-6 bg-slate-200/60 p-1.5 rounded-2xl w-full sm:w-fit overflow-x-auto scrollbar-thin">
        <button @click="tab='todo'"
            :class="tab==='todo' ? 'bg-[#ff5722] text-white shadow-md shadow-[#ff5722]/30' : 'text-slate-600 hover:text-slate-900 bg-white/60'"
            class="flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px]">pending_actions</span>
            <span>Perlu Aksi</span>
            <span class="inline-flex w-5 h-5 rounded-full bg-white/20 text-[10px] items-center justify-center font-extrabold">{{ $todo->count() }}</span>
        </button>
        <button @click="tab='review'"
            :class="tab==='review' ? 'bg-[#ff5722] text-white shadow-md shadow-[#ff5722]/30' : 'text-slate-600 hover:text-slate-900 bg-white/60'"
            class="flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px]">hourglass_top</span>
            <span>Review</span>
            <span class="inline-flex w-5 h-5 rounded-full bg-white/20 text-[10px] items-center justify-center font-extrabold">{{ $review->count() }}</span>
        </button>
        <button @click="tab='done'"
            :class="tab==='done' ? 'bg-[#ff5722] text-white shadow-md shadow-[#ff5722]/30' : 'text-slate-600 hover:text-slate-900 bg-white/60'"
            class="flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px]">task_alt</span>
            <span>Selesai</span>
        </button>
    </div>

    {{-- Tab 1: Perlu Aksi --}}
    <div x-show="tab==='todo'" x-transition class="space-y-5">
        @forelse($todo as $v)
        <div class="bento-card rounded-2xl p-5 sm:p-6 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-4 pb-4 border-b border-slate-100">
                <div>
                    <h4 class="font-extrabold text-slate-900 font-display text-base sm:text-lg">{{ $v->schedule->classType->name }}</h4>
                    <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[#ff5722] text-[16px]">calendar_month</span>
                        <span>{{ $v->session_date->translatedFormat('l, d M Y') }}</span>
                        <span>•</span>
                        <span>{{ \Carbon\Carbon::parse($v->schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($v->schedule->end_time)->format('H:i') }} WIB</span>
                    </p>
                </div>
                <div>
                    @if($v->status === 'rejected')
                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span> Ditolak — Upload Ulang
                    </span>
                    @else
                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">priority_high</span> Belum Diverifikasi
                    </span>
                    @endif
                </div>
            </div>

            @php
                $deadline = $v->effective_deadline;
                $isExpired = $v->isPastDeadline();
                $diffText = null;
                if ($deadline && now()->lt($deadline)) {
                    $diffText = now()->diffForHumans($deadline, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]);
                }
            @endphp

            {{-- SOP Deadline Banner --}}
            <div x-data="countdownTimer('{{ $deadline?->toIso8601String() }}')"
                x-init="{{ $isExpired ? '' : 'start()' }}"
                class="mb-4 p-3.5 rounded-2xl flex items-center justify-between gap-3 text-xs {{ $isExpired ? 'bg-rose-50 border border-rose-200 text-rose-800' : 'bg-amber-50 border border-amber-200 text-amber-800' }}">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] {{ $isExpired ? 'text-rose-600' : 'text-amber-600' }}">
                        {{ $isExpired ? 'timer_off' : 'schedule' }}
                    </span>
                    <div>
                        <span class="font-bold">SOP Batas Waktu Upload:</span>
                        <span class="ml-1">Maksimal 1 jam setelah kelas selesai (Batas: <strong>{{ $deadline ? $deadline->format('H:i') . ' WIB' : '-' }}</strong>)</span>
                    </div>
                </div>
                <div>
                    @if($isExpired)
                    <span class="px-2.5 py-1 rounded-xl bg-rose-200/80 text-rose-900 font-extrabold text-[11px] shrink-0">Waktu Habis (Kadaluarsa)</span>
                    @else
                    <span class="px-2.5 py-1 rounded-xl bg-amber-200/80 text-amber-900 font-extrabold text-[11px] shrink-0">
                        Sisa waktu: <span x-text="display"></span>
                    </span>
                    @endif
                </div>
            </div>

            {{-- Member Bookings List --}}
            @if(isset($v->bookings) && $v->bookings->isNotEmpty())
            <div class="mb-5 p-4 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs">
                <div class="flex items-center justify-between mb-2 font-bold text-slate-800">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[#ff5722] text-[18px]">groups</span>
                        <span>Peserta Terdaftar ({{ $v->bookings->count() }} Member)</span>
                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($v->bookings as $b)
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-slate-800 font-semibold text-xs shadow-2xs">
                        {{ $b->member->full_name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mb-5 p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs text-slate-500 font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">info</span>
                <span>Tidak ada member yang melakukan booking online untuk sesi ini.</span>
            </div>
            @endif

            @if($v->status === 'rejected' && $v->rejection_reason)
            <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 font-medium">
                <strong>Alasan Penolakan:</strong> {{ $v->rejection_reason }}
            </div>
            @endif

            @if($isExpired)
            <div class="p-6 bg-slate-50 border border-dashed border-rose-200 rounded-2xl text-center">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-[26px]">block</span>
                </div>
                <p class="text-sm font-extrabold text-slate-900">Formulir Upload Ditutup</p>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                    Anda telah melewati batas toleransi upload 1 jam setelah jam kelas berakhir ({{ $deadline ? $deadline->format('H:i') . ' WIB' : '' }}). Sesuai SOP, sesi ini tidak dapat diverifikasi lagi.
                </p>
            </div>
            @else
            <form method="POST"
                action="{{ $v->status === 'rejected' ? route('coach.attendance.resubmit', $v) : route('coach.attendance.submit', $v) }}"
                x-data="cameraCapture()"
                @submit.prevent="submitCapture($event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[#ff5722] text-[16px]">photo_camera</span>
                            <span>Ambil Foto Bukti dari Kamera <span class="text-[#ff5722]">*</span></span>
                        </label>
                        <div class="rounded-2xl overflow-hidden bg-slate-950 border-2 border-slate-200 min-h-48 flex items-center justify-center">
                            <video x-ref="video" autoplay playsinline muted class="w-full max-h-72 object-cover" x-show="cameraOpen && !preview"></video>
                            <img x-show="preview" :src="preview" class="w-full max-h-72 object-cover">
                            <div x-show="!cameraOpen && !preview" class="p-8 text-center text-white">
                                <span class="material-symbols-outlined text-4xl text-[#ff5722]">no_photography</span>
                                <p class="text-xs font-bold mt-2">Kamera belum dibuka</p>
                                <p class="text-[11px] text-slate-400 mt-1">Tidak tersedia pilihan galeri atau dokumen.</p>
                            </div>
                        </div>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        <div class="flex gap-2 mt-3">
                            <button type="button" x-show="!cameraOpen && !preview" @click="startCamera()" class="flex-1 py-3 bg-slate-900 text-white text-xs font-extrabold rounded-xl flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">videocam</span> Buka Kamera
                            </button>
                            <button type="button" x-show="cameraOpen && !preview" @click="takePhoto()" class="flex-1 py-3 bg-[#ff5722] text-white text-xs font-extrabold rounded-xl flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">photo_camera</span> Ambil Foto
                            </button>
                            <button type="button" x-show="preview" @click="retake()" class="flex-1 py-3 bg-slate-700 text-white text-xs font-extrabold rounded-xl flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">refresh</span> Foto Ulang
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2 font-medium">Kamera HP wajib digunakan. Foto lama dari galeri/file manager tidak dapat dipilih.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Tuliskan catatan kondisi kelas jika ada..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#ff5722] resize-none"></textarea>
                    </div>
                    <button type="submit" :disabled="!photoBlob || submitting" class="w-full py-3.5 bg-[#ff5722] disabled:bg-slate-300 disabled:cursor-not-allowed hover:bg-[#e13b12] text-white text-xs font-extrabold rounded-xl transition-all shadow-lg shadow-[#ff5722]/30 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        <span x-text="submitting ? 'Mengirim...' : 'Kirim Bukti Kamera'"></span>
                    </button>
                </div>
            </form>
            @endif
        </div>
        @empty
        <div class="bento-card rounded-2xl p-12 text-center text-slate-400">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <span class="material-symbols-outlined text-[32px] filled">check_circle</span>
            </div>
            <p class="font-extrabold text-slate-900 text-base font-display">Semua Presensi Terverifikasi</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Tidak ada tugas verifikasi yang membutuhkan aksi Anda saat ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Tab 2: Menunggu Review --}}
    <div x-show="tab==='review'" x-transition>
        <div class="bento-card rounded-2xl overflow-hidden">
            @if($review->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs font-medium">Tidak ada permohonan yang sedang dalam peninjauan admin</div>
            @else
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4">Tanggal Sesi</th>
                            <th class="px-6 py-4">Peserta Terdaftar</th>
                            <th class="px-6 py-4">Waktu Pengiriman</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($review as $v)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-slate-900 font-display">{{ $v->schedule->classType->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $v->session_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-slate-700">
                                @if(isset($v->bookings) && $v->bookings->isNotEmpty())
                                    <span class="font-bold text-slate-900">{{ $v->bookings->count() }} Member:</span>
                                    <span class="text-slate-500 text-[11px] block">{{ $v->bookings->pluck('member.full_name')->join(', ') }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $v->submitted_at?->format('d M Y H:i') }} WIB</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">Menunggu Review</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Tab 3: Selesai --}}
    <div x-show="tab==='done'" x-transition>
        <div class="bento-card rounded-2xl overflow-hidden">
            @if($done->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs font-medium">Belum ada riwayat verifikasi yang selesai</div>
            @else
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4">Tanggal Sesi</th>
                            <th class="px-6 py-4 text-right">Status Hasil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($done as $v)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-slate-900 font-display">{{ $v->schedule->classType->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $v->session_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($v->status === 'approved')
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200">
                                    Disetujui Admin
                                </span>
                                @elseif($v->status === 'auto_failed')
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold border bg-rose-50 text-rose-700 border-rose-200" title="{{ $v->rejection_reason }}">
                                    Gagal (Lewat Batas 1 Jam)
                                </span>
                                @else
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold border bg-slate-100 text-slate-600 border-slate-200">
                                    Tidak Memenuhi Syarat
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
function cameraCapture() {
    return {
        cameraOpen: false,
        preview: null,
        photoBlob: null,
        stream: null,
        submitting: false,
        async startCamera() {
            if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
                alert('Kamera membutuhkan HTTPS dan browser yang mendukung akses kamera.');
                return;
            }
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } }, audio: false });
                this.$refs.video.srcObject = this.stream;
                this.cameraOpen = true;
            } catch (error) {
                alert('Izin kamera diperlukan. Tidak ada pilihan upload dokumen atau galeri.');
            }
        },
        takePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(blob => {
                this.photoBlob = blob;
                this.preview = URL.createObjectURL(blob);
                this.stopCamera();
            }, 'image/jpeg', 0.9);
        },
        retake() {
            this.preview = null;
            this.photoBlob = null;
            this.startCamera();
        },
        stopCamera() {
            this.stream?.getTracks().forEach(track => track.stop());
            this.stream = null;
            this.cameraOpen = false;
        },
        async submitCapture(event) {
            if (!this.photoBlob || this.submitting) return;
            this.submitting = true;
            const form = event.target;
            const data = new FormData(form);
            data.delete('photo_proof');
            data.append('photo_proof', this.photoBlob, 'camera-capture.jpg');
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: data,
                    headers: { 'Accept': 'text/html' },
                    credentials: 'same-origin'
                });
                if (response.redirected) window.location.href = response.url;
                else window.location.reload();
            } catch (error) {
                this.submitting = false;
                alert('Gagal mengirim foto. Silakan coba lagi.');
            }
        }
    };
}

function countdownTimer(deadline) {
    return {
        display: '00:00:00',
        timer: null,
        start() {
            if (!deadline) return;
            const target = Date.parse(deadline);
            const tick = () => {
                const remaining = Math.max(0, target - Date.now());
                const total = Math.floor(remaining / 1000);
                const hours = String(Math.floor(total / 3600)).padStart(2, '0');
                const minutes = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
                const seconds = String(total % 60).padStart(2, '0');
                this.display = `${hours}:${minutes}:${seconds}`;
                if (remaining <= 0) {
                    clearInterval(this.timer);
                    window.setTimeout(() => window.location.reload(), 1000);
                }
            };
            tick();
            this.timer = window.setInterval(tick, 250);
        }
    };
}
</script>
@endpush

@endsection

