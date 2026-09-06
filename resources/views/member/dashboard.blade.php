@extends('layouts.member')

@section('title', 'Member Portal — Gintung Master Fitness')
@section('page-title', 'Member Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . $member->full_name)

@section('content')

@if($member->isExpired())
<div class="mb-6 p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-rose-500 text-white flex items-center justify-center text-xl shrink-0 font-bold shadow-md shadow-rose-500/20">
            <span class="material-symbols-outlined text-[24px]">warning</span>
        </div>
        <div>
            <h4 class="font-extrabold text-sm text-rose-950 font-display">Masa Membership Anda Telah Kadaluarsa!</h4>
            <p class="text-xs text-rose-700 mt-0.5">
                Masa berlaku berakhir pada <strong>{{ $member->membership_end_date ? $member->membership_end_date->format('d M Y') : '-' }}</strong>. Presensi QR dan fitur booking kelas dikunci hingga Anda memperpanjang membership.
            </p>
        </div>
    </div>
</div>
@endif

@if(isset($pendingRenewal) && $pendingRenewal)
@php $isMidtransUnpaid = in_array($pendingRenewal->payment_method, ['qris', 'midtrans']) && $pendingRenewal->payment_status !== 'settlement'; @endphp
<div class="mb-6 p-5 rounded-2xl {{ $isMidtransUnpaid ? 'bg-indigo-50 border border-indigo-200 text-indigo-950' : 'bg-amber-50 border border-amber-200 text-amber-900' }} text-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
    <div class="flex items-start sm:items-center gap-3">
        <div class="w-11 h-11 rounded-xl {{ $isMidtransUnpaid ? 'bg-indigo-600' : 'bg-amber-500' }} text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
            @if($isMidtransUnpaid)
                <span class="material-symbols-outlined text-[24px]">credit_card</span>
            @else
                <span class="material-symbols-outlined text-[24px]">pending_actions</span>
            @endif
        </div>
        <div>
            <p class="font-extrabold text-sm {{ $isMidtransUnpaid ? 'text-indigo-950' : 'text-amber-950' }} font-display">
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
            class="px-4 py-2 bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl shadow-md inline-flex items-center gap-1.5 transition-all">
            <span>Lanjutkan Pembayaran</span>
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
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

{{-- OFFICIAL GMF DIGITAL MEMBER CARD (Stitch AI Elite Pass Layout) --}}
<div id="official-member-card" class="bento-card rounded-2xl overflow-hidden mb-8 pb-4 shadow-xl">
    {{-- Card Header Banner --}}
    <div style="background: linear-gradient(135deg, #0f1418 0%, #1e242b 60%, #ff5722 100%);" class="px-6 py-5 flex items-center justify-between text-white border-b border-white/10">
        <div class="flex items-center gap-3">
            @if(file_exists(public_path('images/gmf.png')))
                <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-9 h-9 object-contain shrink-0">
            @else
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#e13b12] to-[#ff5722] text-white flex items-center justify-center font-black text-xs shadow-md">GMF</div>
            @endif
            <div>
                <h3 class="font-extrabold text-white text-sm tracking-wider uppercase font-display">GINTUNG MASTER FITNESS</h3>
                <p class="text-[#ff5722] text-[10px] font-bold uppercase tracking-widest">OFFICIAL MEMBER PASS</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $member->isActive() ? 'bg-emerald-400 animate-pulse' : 'bg-rose-500' }}"></span>
            <span class="text-xs font-extrabold text-white tracking-wider uppercase">DIGITAL PASS</span>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
        
        {{-- Left: Member Avatar & QR Code --}}
        <div class="md:col-span-5 flex flex-col items-center justify-center text-center space-y-4 md:border-r md:border-slate-100 md:pr-6">
            
            {{-- Member Photo / Avatar Circle --}}
            <div class="relative">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-tr from-[#ff5722] to-amber-500 p-1 shadow-lg shadow-[#ff5722]/30 mx-auto">
                    <div class="w-full h-full rounded-full bg-[#0f1418] border-2 border-white flex items-center justify-center text-white font-extrabold font-display text-3xl overflow-hidden">
                        <img src="{{ $member->profile_photo_url }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            {{-- QR Code Box --}}
            @if($member->isExpired())
            <div class="w-full max-w-[200px] p-4 bg-rose-50 border border-rose-200 rounded-2xl text-center shadow-inner">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-[24px]">lock</span>
                </div>
                <p class="text-xs font-bold text-rose-800">QR Presensi Dikunci</p>
                <p class="text-[10px] text-rose-600 mt-0.5">Status Membership Kadaluarsa</p>
            </div>
            @elseif($qrCode && $member->isActive())
            <div class="p-3.5 bg-white border-2 border-slate-900 rounded-2xl inline-block shadow-md">
                <div class="bg-white p-2 rounded-xl">
                    {!! $qrCode !!}
                </div>
            </div>
            <p class="text-xs font-bold text-slate-800 flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[#ff5722] text-[16px]">qr_code_scanner</span>
                <span>Scan QR untuk Presensi Gym</span>
            </p>
            @else
            <div class="p-4 bg-white border-2 border-slate-200 rounded-2xl text-center text-xs font-bold text-slate-500">
                Member Belum Aktif
            </div>
            @endif
        </div>

        {{-- Right: Member Personal Information & Actions --}}
        <div class="md:col-span-7 space-y-4">
            <div>
                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-[#ff5722]/10 text-[#ff5722] border border-[#ff5722]/20 inline-block mb-1.5">
                    {{ $member->package?->name ?? 'MEMBER GMF' }}
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-display tracking-tight">{{ $member->full_name }}</h2>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 min-h-[70px] flex flex-col justify-center">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">ID MEMBER</p>
                    <p class="font-extrabold text-slate-900 text-sm sm:text-base font-mono leading-relaxed">#GMF-{{ str_pad($member->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 min-h-[70px] flex flex-col justify-center">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">NO. TELEPON</p>
                    <p class="font-bold text-slate-900 text-sm sm:text-base leading-relaxed truncate py-0.5">{{ $member->phone ?? '-' }}</p>
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

            {{-- Action Buttons: Simpan Kartu & Perpanjang Membership --}}
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
                        class="w-full py-3.5 bg-[#0f1418] hover:bg-slate-800 text-white text-xs font-extrabold rounded-2xl transition-all shadow-md flex items-center justify-center gap-2 border border-[#262c33]">
                        <span class="material-symbols-outlined text-[#ff5722] text-[18px]">download</span>
                        <span>Simpan Kartu (Foto PNG)</span>
                    </button>
                    @endif

                    {{-- Button 2: Perpanjang Membership Sekarang --}}
                    <button type="button" @click="openModal()"
                        class="w-full py-3.5 bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-extrabold rounded-2xl transition-all shadow-lg shadow-[#ff5722]/30 flex items-center justify-center gap-2 {{ !$member->isActive() ? 'sm:col-span-2' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">autorenew</span>
                        <span>Perpanjang Membership</span>
                    </button>
                </div>

                {{-- Modal Perpanjang --}}
                <div x-show="showRenewalModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md p-4 text-left" x-cloak @click.self="closeModal()">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-lg relative max-h-[85vh] overflow-y-auto scrollbar-thin" x-data="{ method: 'midtrans', selectedPrice: '{{ $packages->first()?->price ?? 0 }}' }">
                        <button type="button" @click="closeModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1">
                            <span class="material-symbols-outlined text-[24px]">close</span>
                        </button>

                        <h3 class="font-extrabold text-slate-900 text-lg font-display mb-1">Perpanjang Masa Membership</h3>
                        <p class="text-xs text-slate-500 mb-5">Pilih paket dan metode pembayaran untuk memperpanjang keanggotaan gym Anda.</p>

                        <form method="POST" action="{{ route('member.renewal.store') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Paket Membership</label>
                                <select name="membership_package_id" required
                                    x-on:change="selectedPrice = $event.target.options[$event.target.selectedIndex].dataset.price"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#ff5722]">
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
                                        :class="method === 'midtrans' ? 'border-[#ff5722] bg-[#ff5722]/10 text-[#ff5722]' : 'border-slate-200 text-slate-600'">
                                        <input type="radio" name="payment_method" value="midtrans" x-model="method" class="hidden">
                                        <span class="material-symbols-outlined text-[18px]">credit_card</span>
                                        <span>Midtrans Gateway</span>
                                    </label>
                                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer text-xs font-bold transition-all"
                                        :class="method === 'cash' ? 'border-[#ff5722] bg-[#ff5722]/10 text-[#ff5722]' : 'border-slate-200 text-slate-600'">
                                        <input type="radio" name="payment_method" value="cash" x-model="method" class="hidden">
                                        <span class="material-symbols-outlined text-[18px]">payments</span>
                                        <span>Tunai di Kasir</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="method === 'midtrans'" class="p-4 bg-[#0f1418] text-white rounded-2xl space-y-2 border border-[#262c33]">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#ff5722]">Pembayaran Online Instan</span>
                                    <span class="text-[10px] bg-white/10 text-slate-300 px-2 py-0.5 rounded font-mono">Midtrans Snap</span>
                                </div>
                                <p class="text-xs text-slate-300">
                                    Mendukung QRIS (GoPay, ShopeePay, Dana), Virtual Account (BCA, Mandiri, BNI, BRI), & Kartu Kredit.
                                </p>
                            </div>

                            <div x-show="method === 'cash'" class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-900 flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-amber-600 shrink-0 text-[18px]">info</span>
                                <span>Silakan lakukan pembayaran tunai kepada staf kasir di meja admin GMF. Pengajuan perpanjangan Anda akan langsung dikonfirmasi staf kami.</span>
                            </div>

                            <input type="hidden" name="payment_amount" :value="selectedPrice">

                            <div class="pt-4 flex gap-3">
                                <button type="button" @click="closeModal()" class="flex-1 py-3.5 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 bg-[#ff5722] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl shadow-lg shadow-[#ff5722]/30 transition-all flex items-center justify-center gap-1.5">
                                    <span>Bayar & Perpanjang</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </button>
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
                ['label' => 'Total Kunjungan', 'value' => $totalVisits, 'color' => 'text-slate-900', 'icon' => 'fitness_center'],
                ['label' => 'Bulan Ini',       'value' => $monthVisits, 'color' => 'text-[#ff5722]', 'icon' => 'calendar_month'],
                ['label' => 'Booking Aktif',   'value' => $activeBookings, 'color' => 'text-emerald-600', 'icon' => 'event_available'],
                ['label' => 'Sisa Masa Aktif', 'value' => max(0, $daysLeft ?? 0) . ' Hari', 'color' => ($daysLeft ?? 0) < 7 ? 'text-rose-600' : 'text-slate-900', 'icon' => 'timer'],
            ];
        @endphp
        @foreach($memberStats as $s)
        <div class="bento-card rounded-2xl p-5 text-center relative overflow-hidden group">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-[#ff5722] flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[20px]">{{ $s['icon'] }}</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black {{ $s['color'] }} font-display">{{ $s['value'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Upcoming Class Bookings --}}
    <div class="lg:col-span-3 bento-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-[#ff5722]"></span>
                <div>
                    <h3 class="font-extrabold text-slate-900 font-display text-base">Booking Kelas Mendatang</h3>
                    <p class="text-xs text-slate-500">Agenda sesi latihan grup Anda</p>
                </div>
            </div>
            @if(! $member->isExpired())
            <a href="{{ route('member.booking.index') }}" class="text-xs font-bold text-[#ff5722] hover:underline flex items-center gap-1">
                <span>Lihat Semua</span>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
            @endif
        </div>

        @if($member->isExpired())
        <div class="p-8 text-center text-slate-400 text-xs font-medium">
            🔒 Fitur booking kelas dikunci karena status membership Anda kadaluarsa. Silakan perpanjang membership.
        </div>
        @elseif($upcomingBookings->isEmpty())
        <div class="p-8 text-center text-slate-400 text-xs font-medium">
            Belum ada booking kelas terjadwal. <a href="{{ route('member.booking.index') }}" class="text-[#ff5722] font-bold hover:underline">Booking kelas sekarang →</a>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($upcomingBookings->take(3) as $b)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/70 transition-colors">
                <div>
                    <p class="text-sm font-bold text-slate-900 font-display">{{ $b->schedule->classType->name }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[#ff5722] text-[14px]">schedule</span>
                        <span>{{ \Carbon\Carbon::parse($b->booking_date)->format('l, d M Y') }} • {{ \Carbon\Carbon::parse($b->schedule->start_time)->format('H:i') }} WIB</span>
                    </p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pelatih: {{ $b->schedule->coach->user->name }}</p>
                </div>
                <form method="POST" action="{{ route('member.booking.cancel', $b) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')"
                        class="px-3.5 py-1.5 text-xs font-bold text-rose-600 border border-rose-200 rounded-xl hover:bg-rose-50 transition-colors">
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
<div class="bento-card rounded-2xl overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <div>
                <h3 class="font-extrabold text-slate-900 font-display text-base">Riwayat Kunjungan Gymnasium</h3>
                <p class="text-xs text-slate-500">Catatan waktu presensi masuk lokasi gym</p>
            </div>
        </div>
        <span class="material-symbols-outlined text-slate-400">history</span>
    </div>
    @if($recentVisits->isEmpty())
    <div class="p-8 text-center text-slate-400 text-xs font-medium">Belum ada catatan kunjungan tercatat</div>
    @else
    <div class="divide-y divide-slate-100 text-xs">
        @foreach($recentVisits as $v)
        <div class="flex items-center justify-between px-6 py-3.5 hover:bg-slate-50/70 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[16px] filled">check_circle</span>
                </div>
                <div>
                    <p class="font-bold text-slate-900">{{ $v->visited_at->format('l, d M Y') }}</p>
                    <p class="text-[11px] text-slate-400 font-medium">Pukul {{ $v->visited_at->format('H:i') }} WIB</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] border border-emerald-200">Terverifikasi</span>
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

