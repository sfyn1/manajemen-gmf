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

            <form method="POST"
                action="{{ $v->status === 'rejected' ? route('coach.attendance.resubmit', $v) : route('coach.attendance.submit', $v) }}"
                enctype="multipart/form-data">
                @csrf
                <div x-data="{ preview: null }" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[#ff5722] text-[16px]">add_a_photo</span>
                            <span>Unggah Foto Bukti Kehadiran Sesi <span class="text-[#ff5722]">*</span></span>
                        </label>
                        <div class="border-2 border-dashed border-slate-300 hover:border-[#ff5722] rounded-2xl p-6 text-center transition-all cursor-pointer bg-slate-50/70"
                            @click="$refs.photoInput{{ $v->id }}.click()">
                            <template x-if="preview">
                                <img :src="preview" class="max-h-56 mx-auto rounded-xl object-cover border border-slate-200 shadow-md">
                            </template>
                            <template x-if="!preview">
                                <div>
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-[#ff5722] flex items-center justify-center mx-auto mb-2 shadow-sm">
                                        <span class="material-symbols-outlined text-[28px]">photo_camera</span>
                                    </div>
                                    <p class="text-slate-900 text-xs font-bold">Ketuk untuk Ambil Foto / Pilih dari Galeri</p>
                                    <p class="text-slate-400 text-[11px] mt-0.5 font-medium">Format JPG / PNG (Maksimal 5MB)</p>
                                </div>
                            </template>
                        </div>
                        <input type="file" name="photo_proof" x-ref="photoInput{{ $v->id }}" accept="image/*" required class="hidden"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Tuliskan catatan kondisi kelas jika ada..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#ff5722] resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-extrabold rounded-xl transition-all shadow-lg shadow-[#ff5722]/30 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        <span>Kirim Verifikasi Presensi</span>
                    </button>
                </div>
            </form>
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
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ $v->status === 'approved' ? 'Disetujui Admin' : 'Tidak Memenuhi Syarat' }}
                                </span>
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
@endsection

