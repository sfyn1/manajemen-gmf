@extends('layouts.member')

@section('title', 'Booking Kelas — Gintung Master Fitness')
@section('page-title', 'Booking Kelas Grup')
@section('page-subtitle', 'Pilih dan reservasi sesi kelas kebugaran Anda')

@section('content')

<div x-data="{ tab: 'available' }">

    {{-- Tabs --}}
    <div class="flex gap-2 mb-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-1.5 w-fit">
        <button @click="tab='available'" :class="tab==='available' ? 'bg-[#f05a2a] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all">
            Jadwal Tersedia
        </button>
        <button @click="tab='mybooking'" :class="tab==='mybooking' ? 'bg-[#f05a2a] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <span>Booking Saya</span>
            @if($myBookings->count() > 0)
            <span class="inline-flex w-4 h-4 rounded-full bg-white/20 text-[10px] items-center justify-center font-extrabold">{{ $myBookings->count() }}</span>
            @endif
        </button>
    </div>

    {{-- Tab 1: Jadwal Tersedia --}}
    <div x-show="tab==='available'" x-transition>
        @if(!$member->isMonthlyMember())
        <div class="p-4 mb-6 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 text-xs flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Paket Harian hanya mencakup fasilitas gym dasar. Lakukan upgrade ke Paket Bulanan Reguler/Pelajar untuk akses booking kelas.</span>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($schedules as $s)
            @php $available = $s->getAvailableSlots($s->next_date); @endphp
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#f05a2a]/15 text-[#f05a2a]">
                            {{ $s->classType->name }}
                        </span>
                        <span class="text-[11px] font-semibold {{ $available <= 3 ? 'text-rose-600' : 'text-slate-400' }}">
                            Sisa {{ $available }} Slot
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-slate-900 font-display mb-1">{{ $s->next_date_label }}</h3>
                    
                    <div class="space-y-1.5 text-xs text-slate-500 my-3 font-medium">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }} WIB</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Pelatih: {{ $s->coach->user->name }}</span>
                        </p>
                    </div>

                    <p class="text-lg font-extrabold text-slate-900 font-display mb-4">
                        Rp {{ number_format($s->session_fee, 0, ',', '.') }}
                    </p>
                </div>

                @if($member->isMonthlyMember() && $available > 0)
                <form method="POST" action="{{ route('member.booking.book', $s) }}">
                    @csrf
                    <input type="hidden" name="booking_date" value="{{ $s->next_date }}">
                    <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-[#f05a2a] text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        Booking Kelas Ini
                    </button>
                </form>
                @elseif($available === 0)
                <button disabled class="w-full py-3 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">Kapasitas Penuh</button>
                @endif
            </div>
            @empty
            <div class="col-span-3 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center text-slate-400">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-bold text-slate-800 text-sm">Tidak Ada Jadwal Kelas</p>
                <p class="text-xs text-slate-500 mt-1">Belum ada kelas yang dibuka untuk periode mendatang.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Tab 2: Booking Saya --}}
    <div x-show="tab==='mybooking'" x-transition>
        <div class="space-y-4">
            @forelse($myBookings as $b)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 font-display text-base">{{ $b->schedule->classType->name }}</h4>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ \Carbon\Carbon::parse($b->booking_date)->format('l, d M Y') }} • {{ \Carbon\Carbon::parse($b->schedule->start_time)->format('H:i') }} WIB</p>
                        <p class="text-[11px] text-slate-400">Pelatih: {{ $b->schedule->coach->user->name }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('member.booking.cancel', $b) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin mau membatalkan booking kelas ini?')"
                        class="px-4 py-2 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-xl transition-colors">
                        Batalkan Reservasi
                    </button>
                </form>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center text-slate-400">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-bold text-slate-800 text-sm">Belum Ada Booking Aktif</p>
                <button @click="tab='available'" class="text-[#f05a2a] font-semibold text-xs mt-2 hover:underline">Cari & reservasi kelas sekarang →</button>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
