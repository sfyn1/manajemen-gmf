@extends('layouts.coach')

@section('title', 'Verifikasi Kehadiran — Gintung Master Fitness')
@section('page-title', 'Verifikasi Kehadiran Sesi')
@section('page-subtitle', 'Unggah foto bukti kehadiran sesi mengajar kelas kebugaran')

@section('content')

<div x-data="{ tab: 'todo' }">

    {{-- Tabs --}}
    <div class="flex gap-2 mb-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-1.5 w-fit">
        <button @click="tab='todo'" :class="tab==='todo' ? 'bg-[#f05a2a] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <span>Perlu Aksi</span>
            <span class="inline-flex w-5 h-5 rounded-full bg-white/20 text-[10px] items-center justify-center font-extrabold">{{ $todo->count() }}</span>
        </button>
        <button @click="tab='review'" :class="tab==='review' ? 'bg-[#f05a2a] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <span>Menunggu Review</span>
            <span class="inline-flex w-5 h-5 rounded-full bg-white/20 text-[10px] items-center justify-center font-extrabold">{{ $review->count() }}</span>
        </button>
        <button @click="tab='done'" :class="tab==='done' ? 'bg-[#f05a2a] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all">
            Selesai
        </button>
    </div>

    {{-- Tab 1: Perlu Aksi --}}
    <div x-show="tab==='todo'" x-transition class="space-y-4">
        @forelse($todo as $v)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex justify-between items-start mb-4 pb-4 border-b border-slate-100">
                <div>
                    <h4 class="font-bold text-slate-900 font-display text-base">{{ $v->schedule->classType->name }}</h4>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $v->session_date->translatedFormat('l, d M Y') }} • {{ \Carbon\Carbon::parse($v->schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($v->schedule->end_time)->format('H:i') }} WIB</p>
                </div>
                @if($v->status === 'rejected')
                <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold">Ditolak — Upload Ulang</span>
                @else
                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold">Belum Diverifikasi</span>
                @endif
            </div>

            {{-- Member Bookings List --}}
            @if(isset($v->bookings) && $v->bookings->isNotEmpty())
            <div class="mb-4 p-4 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs">
                <div class="flex items-center justify-between mb-2 font-bold text-slate-800">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#f05a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Peserta Terdaftar ({{ $v->bookings->count() }} Member)</span>
                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($v->bookings as $b)
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-slate-800 font-semibold text-xs shadow-xs">
                        {{ $b->member->full_name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mb-4 p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs text-slate-500 font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Tidak ada member yang melakukan booking online untuk sesi ini.</span>
            </div>
            @endif

            @if($v->status === 'rejected' && $v->rejection_reason)
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 font-medium">
                <strong>Alasan Penolakan:</strong> {{ $v->rejection_reason }}
            </div>
            @endif

            <form method="POST"
                action="{{ $v->status === 'rejected' ? route('coach.attendance.resubmit', $v) : route('coach.attendance.submit', $v) }}"
                enctype="multipart/form-data">
                @csrf
                <div x-data="{ preview: null }" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Unggah Foto Bukti Kehadiran Sesi <span class="text-[#f05a2a]">*</span></label>
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-[#f05a2a] transition-all cursor-pointer bg-slate-50/50"
                            @click="$refs.photoInput{{ $v->id }}.click()">
                            <template x-if="preview">
                                <img :src="preview" class="max-h-48 mx-auto rounded-xl object-cover border border-slate-200">
                            </template>
                            <template x-if="!preview">
                                <div>
                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-2 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-slate-800 text-xs font-semibold">Klik atau seret foto bukti di sini</p>
                                    <p class="text-slate-400 text-[11px] mt-0.5">Format JPG / PNG (Maksimal 5MB)</p>
                                </div>
                            </template>
                        </div>
                        <input type="file" name="photo_proof" x-ref="photoInput{{ $v->id }}" accept="image/*" required class="hidden"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Tuliskan catatan kondisi kelas jika ada..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a] resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                        Kirim Verifikasi Presensi
                    </button>
                </div>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center text-slate-400">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="font-bold text-slate-800 text-sm">Semua Presensi Telah Terverifikasi</p>
            <p class="text-xs text-slate-500 mt-1">Tidak ada tugas verifikasi yang membutuhkan aksi Anda saat ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Tab 2: Menunggu Review --}}
    <div x-show="tab==='review'" x-transition>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if($review->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs font-medium">Tidak ada permohonan yang sedang dalam peninjauan admin</div>
            @else
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold"><tr>
                        <th class="px-6 py-4">Nama Kelas</th>
                        <th class="px-6 py-4">Tanggal Sesi</th>
                        <th class="px-6 py-4">Peserta Terdaftar</th>
                        <th class="px-6 py-4">Waktu Pengiriman</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($review as $v)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 font-display">{{ $v->schedule->classType->name }}</td>
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
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">Proses Peninjauan Admin</span>
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
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if($done->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs font-medium">Belum ada riwayat verifikasi yang selesai</div>
            @else
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold"><tr>
                        <th class="px-6 py-4">Nama Kelas</th>
                        <th class="px-6 py-4">Tanggal Sesi</th>
                        <th class="px-6 py-4 text-right">Status Hasil</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($done as $v)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 font-display">{{ $v->schedule->classType->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $v->session_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
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
