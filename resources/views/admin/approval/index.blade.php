@extends('layouts.admin')
@section('page-title', 'Approval Center')
@section('page-subtitle', 'Verifikasi pendaftaran member, perpanjangan membership, & kehadiran coach')
@section('content')

<div x-data="{ tab: 'members' }">

    {{-- Tabs Navigation --}}
    <div class="flex flex-wrap gap-2 mb-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-1.5 w-full sm:w-fit">
        <button @click="tab='members'" :class="tab==='members' ? 'bg-[#f05a2a] text-white shadow-md shadow-[#f05a2a]/20' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2">
            <span>Pendaftaran Member</span>
            @if($pendingMembers->count() > 0)
            <span class="w-5 h-5 rounded-full bg-white/30 text-xs flex items-center justify-center font-black">{{ $pendingMembers->count() }}</span>
            @endif
        </button>
        
        <button @click="tab='renewals'" :class="tab==='renewals' ? 'bg-[#f05a2a] text-white shadow-md shadow-[#f05a2a]/20' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2">
            <span>Perpanjangan Membership</span>
            @if($pendingRenewals->count() > 0)
            <span class="w-5 h-5 rounded-full bg-white/30 text-xs flex items-center justify-center font-black">{{ $pendingRenewals->count() }}</span>
            @endif
        </button>

        <button @click="tab='verification'" :class="tab==='verification' ? 'bg-[#f05a2a] text-white shadow-md shadow-[#f05a2a]/20' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2">
            <span>Verifikasi Coach</span>
            @if($pendingVerifications->count() > 0)
            <span class="w-5 h-5 rounded-full bg-white/30 text-xs flex items-center justify-center font-black">{{ $pendingVerifications->count() }}</span>
            @endif
        </button>

        <button @click="tab='history'" :class="tab==='history' ? 'bg-slate-900 text-white shadow-md' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span>Riwayat Approval & Pembayaran</span>
            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">{{ $historyRenewals->count() }}</span>
        </button>
    </div>

    {{-- Tab 1: Member Approval --}}
    <div x-show="tab==='members'" x-transition>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-700">Pendaftaran Member Menunggu Verifikasi</h3>
            </div>
            @if($pendingMembers->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-gray-500 font-medium text-sm">Semua pendaftaran sudah diproses!</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Nama / NIK</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Paket</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tgl Daftar</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Dokumen</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Nominal Bayar</th>
                            <th class="px-5 py-3 text-gray-400 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pendingMembers as $member)
                        @php $doc = $member->latestDocument; @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800">{{ $member->full_name }}</p>
                                <p class="text-xs text-gray-400">NIK: {{ $member->nik }}</p>
                                <p class="text-xs text-gray-400">{{ $member->email }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $member->package?->name }}</td>
                            <td class="px-5 py-4 text-gray-500 text-xs">{{ $member->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4">
                                @if($doc)
                                <div class="space-y-1">
                                    @if($doc->identity_document_path)
                                    <a href="{{ asset('storage/' . $doc->identity_document_path) }}" target="_blank"
                                        class="flex items-center gap-1 text-xs text-blue-600 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Identitas
                                    </a>
                                    @endif
                                    @if($doc->payment_proof_path)
                                    <a href="{{ asset('storage/' . $doc->payment_proof_path) }}" target="_blank"
                                        class="flex items-center gap-1 text-xs text-emerald-600 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Bukti Bayar
                                    </a>
                                    @endif
                                </div>
                                @else
                                <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-700">
                                {{ $doc ? 'Rp ' . number_format($doc->payment_amount, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-5 py-4" x-data="{ showRejectModal: false }">
                                <div class="flex gap-2 justify-center">
                                    <form method="POST" action="{{ route('admin.approval.member.approve', $member) }}" onsubmit="return confirm('Setujui member {{ $member->full_name }}?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <button @click="showRejectModal=true" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Tolak</span>
                                    </button>

                                    <div x-show="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showRejectModal=false">
                                        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
                                            <h4 class="font-bold text-gray-800 mb-4">Tolak Pendaftaran</h4>
                                            <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan untuk <strong>{{ $member->full_name }}</strong>:</p>
                                            <form method="POST" action="{{ route('admin.approval.member.reject', $member) }}">
                                                @csrf
                                                <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Bukti pembayaran tidak valid..."
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-200 resize-none mb-4"></textarea>
                                                <div class="flex gap-3 justify-end">
                                                    <button type="button" @click="showRejectModal=false" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl">Batal</button>
                                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Tab 2: Perpanjangan Membership --}}
    <div x-show="tab==='renewals'" x-transition>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-700">Permohonan Perpanjangan Membership</h3>
            </div>
            @if($pendingRenewals->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-gray-500 font-medium text-sm">Tidak ada permohonan perpanjangan yang menunggu</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Member</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Paket Diperpanjang</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Metode Bayar</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Nominal</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tgl Pengajuan</th>
                            <th class="px-5 py-3 text-gray-400 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pendingRenewals as $renewal)
                        <tr class="hover:bg-gray-50/50" x-data="{ showRejectRenewal: false }">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800">{{ $renewal->member->full_name }}</p>
                                <p class="text-xs text-gray-400">NIK: {{ $renewal->member->nik }}</p>
                                <p class="text-xs text-gray-400">{{ $renewal->member->email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-700">{{ $renewal->package->name }}</span>
                                <p class="text-xs text-gray-400">Durasi: {{ $renewal->package->duration_days }} Hari</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($renewal->payment_method === 'qris')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                        QRIS Digital
                                    </span>
                                    @if($renewal->payment_proof_path)
                                    <a href="{{ asset('storage/' . $renewal->payment_proof_path) }}" target="_blank"
                                        class="flex items-center gap-1 text-xs text-blue-600 hover:underline mt-1 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Bukti Transfer</span>
                                    </a>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/60">
                                        Tunai di Kasir
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-800">
                                Rp {{ number_format($renewal->payment_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">
                                {{ $renewal->created_at->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex gap-2 justify-center">
                                    <form method="POST" action="{{ route('admin.approval.renewal.approve', $renewal) }}" onsubmit="return confirm('Setujui perpanjangan membership {{ $renewal->member->full_name }}?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <button @click="showRejectRenewal=true" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Tolak</span>
                                    </button>

                                    {{-- Reject Modal With Refund Notes --}}
                                    <div x-show="showRejectRenewal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 text-left" @click.self="showRejectRenewal=false">
                                        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
                                            <h4 class="font-bold text-gray-800 text-base mb-1">Tolak Perpanjangan Membership</h4>
                                            <p class="text-xs text-gray-500 mb-4">Member: <strong>{{ $renewal->member->full_name }}</strong> (Pembayaran: {{ strtoupper($renewal->payment_method) }})</p>
                                            
                                            <form method="POST" action="{{ route('admin.approval.renewal.reject', $renewal) }}" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                                    <textarea name="rejection_reason" required rows="2" placeholder="Contoh: Bukti transfer QRIS tidak sesuai nominal..."
                                                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-200 resize-none"></textarea>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Pengembalian Dana / Refund Manual</label>
                                                    <textarea name="refund_notes" rows="2" placeholder="Contoh: Dana telah ditransfer kembali via QRIS BCA ref: #98123 (atau silakan ambil uang tunai di kasir GMF)."
                                                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-200 resize-none"></textarea>
                                                    <p class="text-[11px] text-gray-400 mt-1">Catatan ini akan langsung terlihat oleh member di halaman tagihan sebagai kejelasan refund.</p>
                                                </div>

                                                <div class="flex gap-3 justify-end pt-2">
                                                    <button type="button" @click="showRejectRenewal=false" class="px-4 py-2 text-xs text-gray-500 border border-gray-200 rounded-xl">Batal</button>
                                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl">Kirim Penolakan & Refund</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Tab 3: Verification Coach --}}
    <div x-show="tab==='verification'" x-transition>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-700">Verifikasi Kehadiran Coach</h3>
            </div>
            @if($pendingVerifications->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Tidak ada verifikasi kehadiran yang menunggu</p>
            </div>
            @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-400 font-semibold">Coach / Kelas</th>
                        <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tanggal & Jam</th>
                        <th class="text-left px-5 py-3 text-gray-400 font-semibold">Foto Bukti</th>
                        <th class="px-5 py-3 text-gray-400 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pendingVerifications as $v)
                    <tr class="hover:bg-gray-50/50" x-data="{showRejectV: false}">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800">{{ $v->coach->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $v->schedule->classType->name }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-600 text-xs">
                            {{ $v->session_date->format('d M Y') }}<br>
                            {{ \Carbon\Carbon::parse($v->schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($v->schedule->end_time)->format('H:i') }}
                        </td>
                        <td class="px-5 py-4">
                            @if($v->photo_path)
                            <a href="{{ asset('storage/' . $v->photo_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $v->photo_path) }}" alt="Bukti" class="w-16 h-16 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity">
                            </a>
                            @else
                            <span class="text-xs text-gray-400">Belum upload</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex gap-2 justify-center">
                                <form method="POST" action="{{ route('admin.approval.verification.approve', $v) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Setujui</span>
                                    </button>
                                </form>
                                <button @click="showRejectV=true" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-semibold rounded-lg flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Tolak</span>
                                </button>

                                <div x-show="showRejectV" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showRejectV=false">
                                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
                                        <h4 class="font-bold text-gray-800 mb-3">Tolak Verifikasi</h4>
                                        <form method="POST" action="{{ route('admin.approval.verification.reject', $v) }}">
                                            @csrf
                                            <textarea name="rejection_notes" required rows="3" placeholder="Alasan penolakan..."
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-200 resize-none mb-4"></textarea>
                                            <div class="flex gap-3 justify-end">
                                                <button type="button" @click="showRejectV=false" class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-xl">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-rose-600 text-white text-sm font-semibold rounded-xl">Kirim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Tab 4: Riwayat Approval & Pembayaran --}}
    <div x-show="tab==='history'" x-transition class="space-y-6">
        
        {{-- ── Section 1: Riwayat Pendaftaran Member Awal (Approved & Rejected) ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-700">Riwayat Pendaftaran Member Awal</h3>
                    <p class="text-xs text-gray-400">Daftar calon member baru yang sudah disetujui atau ditolak</p>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">{{ $historyMembers->count() }} Data</span>
            </div>

            @if($historyMembers->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs font-medium">
                Belum ada riwayat pendaftaran member awal.
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Nama / NIK / Email</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Paket</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Dokumen / Bukti</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Alasan Penolakan / Catatan</th>
                            <th class="text-right px-5 py-3 text-gray-400 font-semibold">Aksi Kasir Desk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($historyMembers as $m)
                        @php $doc = $m->latestDocument; @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4">
                                <p class="font-bold text-gray-800">{{ $m->full_name }}</p>
                                <p class="text-xs text-gray-400">NIK: {{ $m->nik ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $m->email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800">{{ $m->package?->name ?? '—' }}</p>
                                <p class="text-xs font-bold text-[#f05a2a]">
                                    {{ $doc ? 'Rp ' . number_format($doc->payment_amount, 0, ',', '.') : '—' }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-xs">
                                @if($doc && $doc->payment_proof_path)
                                <a href="{{ asset('storage/' . $doc->payment_proof_path) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Bukti Transfer</span>
                                </a>
                                @else
                                <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($m->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui (Aktif)
                                    </span>
                                @elseif($m->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs">
                                @if($m->status === 'rejected')
                                    <p class="text-rose-700 font-semibold">Alasan: {{ $m->rejection_reason ?? '-' }}</p>
                                    <p class="text-gray-500 mt-0.5">Dapat di-refund / daftar ulang kasir meja admin.</p>
                                @else
                                    <span class="text-gray-400">Pendaftaran disetujui</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.membership.show', $m) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#f05a2a] hover:underline">
                                    <span>Detail & Kasir →</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ── Section 2: Riwayat Perpanjangan Membership & Pembayaran ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-700">Riwayat Perpanjangan Membership</h3>
                    <p class="text-xs text-gray-400">Catatan permohonan perpanjangan yang sudah disetujui atau ditolak</p>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">{{ $historyRenewals->count() }} Data</span>
            </div>

            @if($historyRenewals->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs font-medium">
                Belum ada riwayat perpanjangan yang diproses.
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Member</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Paket / Nominal</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Metode</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Catatan / Alasan / Refund</th>
                            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Diproses Oleh / Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($historyRenewals as $h)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4">
                                <p class="font-bold text-gray-800">{{ $h->member->full_name }}</p>
                                <p class="text-xs text-gray-400">NIK: {{ $h->member->nik }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800">{{ $h->package?->name }}</p>
                                <p class="text-xs font-bold text-[#f05a2a]">Rp {{ number_format($h->payment_amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $h->payment_method === 'qris' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/60' : 'bg-amber-50 text-amber-800 border border-amber-200/60' }}">
                                    {{ strtoupper($h->payment_method) }}
                                </span>
                                @if($h->payment_proof_path)
                                <a href="{{ asset('storage/' . $h->payment_proof_path) }}" target="_blank" class="flex items-center gap-1 text-[11px] text-blue-600 hover:underline mt-1 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Bukti</span>
                                </a>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($h->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs">
                                @if($h->status === 'rejected')
                                    <p class="text-rose-700 font-semibold">Alasan: {{ $h->rejection_reason ?? '-' }}</p>
                                    @if($h->refund_notes)
                                    <p class="text-gray-600 mt-0.5">Refund: <em>{{ $h->refund_notes }}</em></p>
                                    @endif
                                @else
                                    <span class="text-gray-400">Persetujuan Perpanjangan Sukses</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">
                                <p class="font-medium text-gray-700">{{ $h->processor?->name ?? 'Admin' }}</p>
                                <p class="text-[11px] text-gray-400">{{ $h->processed_at ? $h->processed_at->format('d M Y H:i') : '-' }}</p>
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
