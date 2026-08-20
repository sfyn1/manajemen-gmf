@extends('layouts.member')

@section('title', 'Dashboard Member — Gintung Master Fitness')
@section('page-title', 'Dashboard Member')
@section('page-subtitle', 'Selamat datang kembali, ' . $member->full_name)

@section('content')

@if($member->isExpired())
<div class="mb-6 p-5 rounded-3xl bg-rose-50 border border-rose-200 text-rose-900 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl shrink-0 font-bold">
            ⚠️
        </div>
        <div>
            <h4 class="font-bold text-sm text-rose-900">Masa Membership Anda Telah Kadaluarsa!</h4>
            <p class="text-xs text-rose-700 mt-0.5">
                Masa berlaku berakhir pada <strong>{{ $member->membership_end_date ? $member->membership_end_date->format('d M Y') : '-' }}</strong>. Presensi QR dan fitur booking kelas dikunci hingga Anda memperpanjang membership.
            </p>
        </div>
    </div>
</div>
@endif

@if(isset($pendingRenewal) && $pendingRenewal)
@php $isMidtransUnpaid = in_array($pendingRenewal->payment_method, ['qris', 'midtrans']) && $pendingRenewal->payment_status !== 'settlement'; @endphp
<div class="mb-6 p-4 sm:p-5 rounded-3xl {{ $isMidtransUnpaid ? 'bg-indigo-50 border border-indigo-200 text-indigo-950' : 'bg-amber-50 border border-amber-200 text-amber-900' }} text-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
    <div class="flex items-start sm:items-center gap-3">
        <div class="w-10 h-10 rounded-2xl {{ $isMidtransUnpaid ? 'bg-indigo-600' : 'bg-amber-500' }} text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-sm">
            @if($isMidtransUnpaid)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
        </div>
        <div>
            <p class="font-extrabold text-xs sm:text-sm {{ $isMidtransUnpaid ? 'text-indigo-950' : 'text-amber-950' }} font-display">
                {{ $isMidtransUnpaid ? 'Pembayaran Midtrans Online Belum Selesai' : 'Pengajuan Perpanjangan (Tunai) Sedang Diproses' }}
            </p>
            <p class="text-xs {{ $isMidtransUnpaid ? 'text-indigo-800' : 'text-amber-800' }} mt-0.5 font-medium">
                Paket: <strong>{{ $pendingRenewal->package?->name }}</strong> — Rp {{ number_format($pendingRenewal->payment_amount, 0, ',', '.') }}
                ({{ $isMidtransUnpaid ? 'Midtrans Gateway' : 'Tunai di Kasir' }})
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-2 self-stretch sm:self-auto justify-end">
        @if($isMidtransUnpaid && $pendingRenewal->snap_token)
        <button type="button" onclick="payRenewalSnap('{{ $pendingRenewal->snap_token }}')"
            class="px-4 py-2 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl shadow-md transition-all">
            Lanjutkan Pembayaran →
        </button>
        @endif

        <form method="POST" action="{{ route('member.renewal.cancel', $pendingRenewal) }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Batalkan pengajuan perpanjangan ini?')"
                class="px-3.5 py-2 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 text-xs font-bold rounded-xl transition-colors">
                Batalkan
            </button>
        </form>
    </div>
</div>
@endif

{{-- OFFICIAL GMF DIGITAL MEMBER CARD (Dapat Diunduh Sebagai Foto PNG Tanpa Potong) --}}
<div id="official-member-card" class="bg-white rounded-3xl border border-slate-200/80 shadow-xl overflow-hidden mb-8 pb-4">
    {{-- Card Header Banner --}}
    <div style="background: linear-gradient(135deg, #f05a2a 0%, #d9481c 100%);" class="px-6 py-4 flex items-center justify-between text-white">
        <div class="flex items-center gap-3">
            @if(file_exists(public_path('images/gmf.png')))
                <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-8 h-8 object-contain brightness-0 invert">
            @else
                <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xs">GMF</div>
            @endif
            <div>
                <h3 class="font-extrabold text-white text-sm tracking-wider uppercase font-display">GINTUNG MASTER FITNESS</h3>
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-widest">OFFICIAL MEMBER CARD</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $member->isActive() ? 'bg-emerald-300 animate-pulse' : 'bg-rose-900' }}"></span>
            <span class="text-xs font-extrabold text-white tracking-wider uppercase">DIGITAL PASS</span>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
        
        {{-- Left: Member Avatar & QR Code --}}
        <div class="md:col-span-5 flex flex-col items-center justify-center text-center space-y-4 md:border-r md:border-slate-100 md:pr-6">
            
            {{-- Member Photo / Avatar Circle --}}
            <div class="relative">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-tr from-[#f05a2a] to-amber-500 p-1 shadow-lg shadow-[#f05a2a]/20 mx-auto">
                    <div class="w-full h-full rounded-full bg-slate-900 border-2 border-white flex items-center justify-center text-white font-extrabold font-display text-3xl overflow-hidden">
                        <img src="{{ $member->profile_photo_url }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            {{-- QR Code Box --}}
            @if($member->isExpired())
            <div class="w-full max-w-[200px] p-4 bg-rose-50 border border-rose-200 rounded-2xl text-center shadow-inner">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <p class="text-xs font-bold text-rose-800">QR Presensi Dikunci</p>
                <p class="text-[10px] text-rose-600 mt-0.5">Status Membership Kadaluarsa</p>
            </div>
            @elseif($qrCode && $member->isActive())
            <div class="p-3.5 bg-white border-2 border-slate-800 rounded-2xl inline-block shadow-md">
                <div class="bg-white p-2 rounded-xl">
                    {!! $qrCode !!}
                </div>
            </div>
            <p class="text-xs font-bold text-slate-800">Scan QR di atas untuk Presensi lokasi GMF</p>
            @else
            <div class="p-4 bg-white border-2 border-slate-200 rounded-2xl text-center text-xs font-bold text-slate-500">
                Member Belum Aktif
            </div>
            @endif
        </div>

        {{-- Right: Member Personal Information & Actions --}}
        <div class="md:col-span-7 space-y-4">
            <div>
                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-[#f05a2a]/10 text-[#f05a2a] border border-[#f05a2a]/20 inline-block mb-1.5">
                    {{ $member->package?->name ?? 'MEMBER GMF' }}
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-display tracking-tight">{{ $member->full_name }}</h2>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 min-h-[70px] flex flex-col justify-center">
                    <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-1">ID MEMBER</p>
                    <p class="font-bold text-slate-800 text-sm sm:text-base font-mono leading-relaxed">#GMF-{{ str_pad($member->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 min-h-[70px] flex flex-col justify-center">
                    <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-1">NO. TELEPON</p>
                    <p class="font-bold text-slate-800 text-sm sm:text-base leading-relaxed truncate py-0.5">{{ $member->phone ?? '-' }}</p>
                </div>
            </div>

            {{-- Masa Aktif Pill Box --}}
            @if($member->membership_end_date)
            @php $daysLeft = (int) now()->diffInDays($member->membership_end_date, false); @endphp
            <div class="p-4 rounded-2xl border {{ $member->isExpired() ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-slate-50 border-slate-200 text-slate-800' }} flex items-center justify-between gap-3 min-h-[70px]">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">MASA BERLAKU</p>
                    <p class="font-extrabold text-sm sm:text-base font-display leading-relaxed">
                        {{ $member->membership_end_date->format('d M Y') }}
                    </p>
                </div>
                <div class="shrink-0 py-1">
                    @if($member->isExpired())
                        <span class="px-4 py-2 bg-rose-200 text-rose-800 text-xs font-black rounded-full uppercase leading-normal inline-block">KADALUARSA</span>
                    @else
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-700 text-xs font-extrabold rounded-full uppercase leading-normal inline-block {{ $daysLeft < 7 ? 'bg-amber-100 text-amber-800' : '' }}">
                            {{ $daysLeft }} HARI LAGI
                        </span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Action Buttons: Simpan Kartu (Foto PNG) & Perpanjang Membership --}}
            <div class="action-buttons-area space-y-3 pt-2" x-data="{
                showRenewalModal: false,
                openModal() {
                    this.showRenewalModal = true;
                    window.dispatchEvent(new CustomEvent('modal-open'));
                },
                closeModal() {
                    this.showRenewalModal = false;
                    window.dispatchEvent(new CustomEvent('modal-close'));
                }
            }">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    
                    {{-- Button 1: Simpan Kartu Member sebagai Foto PNG --}}
                    @if($member->isActive())
                    <button type="button" onclick="downloadCardPNG()"
                        class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-2xl transition-all shadow-md shadow-slate-900/20 flex items-center justify-center gap-2 border border-slate-800">
                        <svg class="w-4 h-4 text-[#f05a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Simpan Kartu Member (Foto PNG)</span>
                    </button>
                    @endif

                    {{-- Button 2: Perpanjang Membership Sekarang --}}
                    <button type="button" @click="openModal()"
                        class="w-full py-3.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-extrabold rounded-2xl transition-all shadow-lg shadow-[#f05a2a]/25 flex items-center justify-center gap-2 {{ !$member->isActive() ? 'sm:col-span-2' : '' }}">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Perpanjang Membership Sekarang</span>
                    </button>
                </div>

                {{-- Modal Perpanjang (Akses Penuh Z-Index & Sembunyikan Navigasi Bawah) --}}
                <div x-show="showRenewalModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md p-4 text-left" x-cloak @click.self="closeModal()">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-lg relative max-h-[85vh] overflow-y-auto scrollbar-thin" x-data="{ method: 'midtrans', selectedPrice: '{{ $packages->first()?->price ?? 0 }}' }">
                        <button type="button" @click="closeModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <h3 class="font-bold text-slate-900 text-lg font-display mb-1">Perpanjang Masa Membership</h3>
                        <p class="text-xs text-slate-500 mb-5">Pilih paket dan metode pembayaran untuk memperpanjang keanggotaan gym Anda.</p>

                        <form method="POST" action="{{ route('member.renewal.store') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Paket Membership</label>
                                <select name="membership_package_id" required
                                    x-on:change="selectedPrice = $event.target.options[$event.target.selectedIndex].dataset.price"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#f05a2a]">
                                    @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" data-price="{{ $pkg->price }}" {{ $pkg->id === $member->membership_package_id ? 'selected' : '' }}>
                                        {{ $pkg->name }} — Rp {{ number_format($pkg->price, 0, ',', '.') }} ({{ $pkg->duration_days }} Hari)
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer text-xs font-bold transition-all"
                                        :class="method === 'midtrans' ? 'border-[#f05a2a] bg-[#f05a2a]/10 text-[#f05a2a]' : 'border-slate-200 text-slate-600'">
                                        <input type="radio" name="payment_method" value="midtrans" x-model="method" class="hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2a1 1 0 001-1v-5a1 1 0 00-1-1h-3m-6 0H7a1 1 0 00-1 1v5a1 1 0 001 1h2m-6 0h16M5 8h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2z"/></svg>
                                        <span>Midtrans Gateway</span>
                                    </label>
                                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer text-xs font-bold transition-all"
                                        :class="method === 'cash' ? 'border-[#f05a2a] bg-[#f05a2a]/10 text-[#f05a2a]' : 'border-slate-200 text-slate-600'">
                                        <input type="radio" name="payment_method" value="cash" x-model="method" class="hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Tunai di Kasir</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="method === 'midtrans'" class="p-4 bg-slate-900 text-white rounded-2xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#f05a2a]">Pembayaran Online Instan</span>
                                    <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-mono">Midtrans Snap</span>
                                </div>
                                <p class="text-xs text-slate-300">
                                    Mendukung QRIS (GoPay, ShopeePay, Dana), Virtual Account (BCA, Mandiri, BNI, BRI), & Kartu Kredit.
                                </p>
                            </div>

                            <div x-show="method === 'cash'" class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-900 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Silakan lakukan pembayaran tunai kepada staf kasir di meja admin GMF. Pengajuan perpanjangan Anda akan langsung dikonfirmasi staf kami.</span>
                            </div>

                            <input type="hidden" name="payment_amount" :value="selectedPrice">

                            <div class="pt-4 flex gap-3">
                                <button type="button" @click="closeModal()" class="flex-1 py-3.5 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 bg-[#f05a2a] hover:bg-[#e13b12] text-[#fff] text-xs font-bold rounded-xl shadow-md shadow-[#f05a2a]/20">Bayar & Perpanjang →</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Stats Grid & Bookings --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    {{-- Stats Grid --}}
    <div class="lg:col-span-3 grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $memberStats = [
                ['label' => 'Total Kunjungan', 'value' => $totalVisits, 'color' => 'text-slate-900'],
                ['label' => 'Bulan Ini',       'value' => $monthVisits, 'color' => 'text-[#f05a2a]'],
                ['label' => 'Booking Aktif',   'value' => $activeBookings, 'color' => 'text-emerald-600'],
                ['label' => 'Sisa Masa Aktif', 'value' => max(0, $daysLeft ?? 0) . ' Hari', 'color' => ($daysLeft ?? 0) < 7 ? 'text-rose-600' : 'text-slate-800'],
            ];
        @endphp
        @foreach($memberStats as $s)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 text-center">
            <p class="text-2xl sm:text-3xl font-black {{ $s['color'] }} font-display">{{ $s['value'] }}</p>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Upcoming Class Bookings --}}
    <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-slate-900 font-display text-base">Booking Kelas Mendatang</h3>
                <p class="text-xs text-slate-500">Agenda sesi latihan grup Anda</p>
            </div>
            @if(! $member->isExpired())
            <a href="{{ route('member.booking.index') }}" class="text-xs font-bold text-[#f05a2a] hover:underline flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>

        @if($member->isExpired())
        <div class="p-8 text-center text-slate-400 text-xs font-medium">
            🔒 Fitur booking kelas dikunci karena status membership Anda kadaluarsa. Silakan perpanjang membership.
        </div>
        @elseif($upcomingBookings->isEmpty())
        <div class="p-8 text-center text-slate-400 text-xs font-medium">
            Belum ada booking kelas terjadwal. <a href="{{ route('member.booking.index') }}" class="text-[#f05a2a] font-semibold hover:underline">Booking kelas sekarang →</a>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($upcomingBookings->take(3) as $b)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition-colors">
                <div>
                    <p class="text-sm font-bold text-slate-900 font-display">{{ $b->schedule->classType->name }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        {{ \Carbon\Carbon::parse($b->booking_date)->format('l, d M Y') }} • {{ \Carbon\Carbon::parse($b->schedule->start_time)->format('H:i') }} WIB
                    </p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pelatih: {{ $b->schedule->coach->user->name }}</p>
                </div>
                <form method="POST" action="{{ route('member.booking.cancel', $b) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')"
                        class="px-3.5 py-1.5 text-xs font-semibold text-rose-600 border border-rose-200 rounded-xl hover:bg-rose-50 transition-colors">
                        Batalkan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Recent Gym Visit Log --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="font-bold text-slate-900 font-display text-base">Riwayat Kunjungan Gymnasium</h3>
        <p class="text-xs text-slate-500">Catatan waktu presensi masuk lokasi gym</p>
    </div>
    @if($recentVisits->isEmpty())
    <div class="p-8 text-center text-slate-400 text-xs font-medium">Belum ada catatan kunjungan tercatat</div>
    @else
    <div class="divide-y divide-slate-100 text-xs">
        @foreach($recentVisits as $v)
        <div class="flex items-center justify-between px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">{{ $v->visited_at->format('l, d M Y') }}</p>
                    <p class="text-[11px] text-slate-400">Pukul {{ $v->visited_at->format('H:i') }} WIB</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-[11px]">Terverifikasi</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
function downloadCardPNG() {
    const card = document.getElementById('official-member-card');
    if (!card) return;

    const btnArea = card.querySelector('.action-buttons-area');
    if (btnArea) btnArea.style.display = 'none';

    html2canvas(card, {
        scale: 3,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        logging: false,
        onclone: function(clonedDoc) {
            const clonedCard = clonedDoc.getElementById('official-member-card');
            if (clonedCard) {
                clonedCard.style.paddingBottom = '32px';
            }
        }
    }).then(canvas => {
        if (btnArea) btnArea.style.display = 'block';

        const link = document.createElement('a');
        link.download = 'Kartu_Member_GMF_{{ Str::slug($member->full_name) }}.png';
        link.href = canvas.toDataURL('image/png', 1.0);
        link.click();
    }).catch(err => {
        if (btnArea) btnArea.style.display = 'block';
        alert('Gagal mengunduh foto kartu member: ' + err.message);
    });
}
</script>

@php
    $activeSnapToken = session('rnw_snap_token') ?? (isset($pendingRenewal) && $pendingRenewal->snap_token && $pendingRenewal->payment_status !== 'settlement' ? $pendingRenewal->snap_token : null);
    $snapJsUrl = config('midtrans.is_production') 
        ? 'https://app.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
@if($activeSnapToken || (isset($pendingRenewal) && $pendingRenewal->snap_token))
<script src="{{ $snapJsUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    async function confirmRenewalPayment(orderId) {
        try {
            const res = await fetch('{{ route("member.renewal.confirm-success") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            });
            await res.json();
        } catch (e) {
            console.error('Error confirming renewal payment:', e);
        } finally {
            window.location.reload();
        }
    }

    function payRenewalSnap(token) {
        if (typeof snap !== 'undefined') {
            snap.pay(token, {
                onSuccess: function (result) {
                    confirmRenewalPayment(result ? result.order_id : null);
                },
                onPending: function (result) {
                    confirmRenewalPayment(result ? result.order_id : null);
                },
                onError: function (result) {
                    alert("Pembayaran terganggu atau dibatalkan.");
                },
                onClose: function () {
                    window.location.reload();
                }
            });
        } else {
            alert("Sedang memuat sistem pembayaran... Silakan coba beberapa detik lagi.");
        }
    }
    @if(session('rnw_snap_token'))
    document.addEventListener('DOMContentLoaded', function () {
        payRenewalSnap('{{ session('rnw_snap_token') }}');
    });
    @endif
</script>
@php session()->forget('rnw_snap_token'); @endphp
@endif
@endpush
@endsection
