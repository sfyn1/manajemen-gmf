@extends('layouts.member')

@section('title', 'Booking Kelas — Gintung Master Fitness')
@section('page-title', 'Booking Kelas Grup')
@section('page-subtitle', 'Pilih, bayar via Midtrans Gateway, dan reservasi sesi kelas kebugaran Anda')

@section('content')

<div x-data="{ tab: 'available' }">

    {{-- Banner Pembayaran Class Booking Pending --}}
    @if(isset($pendingBooking) && $pendingBooking && $pendingBooking->snap_token && $pendingBooking->payment_status === 'pending')
    <div class="p-4 mb-6 bg-slate-900 border border-[#f05a2a]/40 rounded-2xl text-white shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-slideUp">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#f05a2a]/20 text-[#f05a2a] flex items-center justify-center font-bold shrink-0 border border-[#f05a2a]/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-bold text-sm font-display text-white">Pembayaran Booking Kelas Belum Selesai</p>
                <p class="text-xs text-slate-400 mt-0.5">Kelas: <strong class="text-white">{{ $pendingBooking->schedule->classType->name }}</strong> — <span class="text-[#f05a2a] font-bold">Rp {{ number_format($pendingBooking->payment_amount, 0, ',', '.') }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="payBookingSnap('{{ $pendingBooking->snap_token }}', '{{ $pendingBooking->order_id }}')"
                class="px-5 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 flex items-center gap-1.5 cursor-pointer">
                <span>Bayar Sekarang via Midtrans →</span>
            </button>
            <form method="POST" action="{{ route('member.booking.cancel', $pendingBooking) }}">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Batalkan pengajuan booking kelas ini?')"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition-all">
                    Batalkan
                </button>
            </form>
        </div>
    </div>
    @endif

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

                    <div class="mb-4">
                        <p class="text-xs text-slate-400">Biaya Sesi Kelas:</p>
                        <p class="text-xl font-extrabold text-[#f05a2a] font-display">
                            Rp {{ number_format($s->session_fee, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                @if($member->isMonthlyMember() && $available > 0)
                <form method="POST" action="{{ route('member.booking.book', $s) }}">
                    @csrf
                    <input type="hidden" name="booking_date" value="{{ $s->next_date }}">
                    <button type="submit" class="w-full py-3 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Booking & Bayar via Midtrans →</span>
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
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-bold text-slate-900 font-display text-base">{{ $b->schedule->classType->name }}</h4>
                            @if($b->payment_status === 'settlement')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                Midtrans Lunas
                            </span>
                            @elseif($b->payment_status === 'refunded')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200/60">
                                Refunded
                            </span>
                            @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/60">
                                Belum Lunas
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($b->booking_date)->format('l, d M Y') }} • {{ \Carbon\Carbon::parse($b->schedule->start_time)->format('H:i') }} WIB</p>
                        <p class="text-[11px] text-slate-400">Pelatih: {{ $b->schedule->coach->user->name }} • Biaya: Rp {{ number_format($b->payment_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($b->payment_status === 'pending' && $b->snap_token)
                    <button onclick="payBookingSnap('{{ $b->snap_token }}')"
                        class="px-4 py-2 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20 cursor-pointer">
                        Bayar Sekarang →
                    </button>
                    @endif

                    <form method="POST" action="{{ route('member.booking.cancel', $b) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin mau membatalkan booking kelas ini? {{ $b->payment_status === 'settlement' ? 'Biaya kelas akan dikembalikan otomatis via Midtrans Refund.' : '' }}')"
                            class="px-4 py-2 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-xl transition-colors">
                            Batalkan Reservasi
                        </button>
                    </form>
                </div>
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

@push('scripts')
@php
    $activeSnapToken = session('cbk_snap_token') ?? (isset($pendingBooking) && $pendingBooking && $pendingBooking->snap_token && $pendingBooking->payment_status === 'pending' ? $pendingBooking->snap_token : null);
    $activeOrderId   = isset($pendingBooking) && $pendingBooking ? $pendingBooking->order_id : null;
    $snapJsUrl = config('midtrans.is_production') 
        ? 'https://app.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
@if($activeSnapToken || (isset($pendingBooking) && $pendingBooking && $pendingBooking->snap_token))
<script src="{{ $snapJsUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    async function confirmBookingPayment(orderId) {
        try {
            const res = await fetch('{{ route("member.booking.confirm-success") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            });
            await res.json();
        } catch (e) {
            console.error('Error confirming booking payment:', e);
        } finally {
            window.location.reload();
        }
    }

    function payBookingSnap(token, orderId) {
        if (typeof snap !== 'undefined') {
            snap.pay(token, {
                onSuccess: function (result) {
                    confirmBookingPayment(result && result.order_id ? result.order_id : orderId);
                },
                onPending: function (result) {
                    confirmBookingPayment(result && result.order_id ? result.order_id : orderId);
                },
                onError: function (result) {
                    alert("Pembayaran terganggu atau dibatalkan.");
                },
                onClose: function () {
                    confirmBookingPayment(orderId);
                }
            });
        } else {
            alert("Sedang memuat sistem pembayaran... Silakan coba beberapa detik lagi.");
        }
    }
    @if(session('cbk_snap_token'))
    document.addEventListener('DOMContentLoaded', function () {
        payBookingSnap('{{ session('cbk_snap_token') }}', '{{ $activeOrderId }}');
    });
    @endif
</script>
@php session()->forget('cbk_snap_token'); @endphp
@endif
@endpush
