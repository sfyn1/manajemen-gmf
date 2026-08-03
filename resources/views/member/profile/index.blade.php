@extends('layouts.member')

@section('title', 'Profil Member — Gintung Master Fitness')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Informasi akun dan data keanggotaan Anda')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Avatar & Identity Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 text-center">
        <div class="w-24 h-24 rounded-3xl bg-slate-900 text-[#f05a2a] border-2 border-slate-800 flex items-center justify-center text-3xl font-extrabold font-display mx-auto mb-4 shadow-xl">
            {{ strtoupper(substr($member->full_name, 0, 1)) }}
        </div>
        <h3 class="font-extrabold text-slate-900 text-lg font-display">{{ $member->full_name }}</h3>
        <p class="text-slate-500 text-xs mt-0.5 font-medium">{{ $member->user?->email ?? $member->email }}</p>
        <p class="text-slate-400 text-xs font-medium">{{ $member->phone ?? '—' }}</p>

        <div class="mt-5 pt-5 border-t border-slate-100">
            @php
                $badgeStyle = match($member->status) {
                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'pending_verification' => 'bg-amber-50 text-amber-700 border-amber-200',
                    default => 'bg-slate-100 text-slate-600 border-slate-200'
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeStyle }}">
                Status {{ ucfirst($member->status) }}
            </span>
            @if($member->package)
            <p class="text-xs font-semibold text-[#f05a2a] mt-3 uppercase tracking-wider">Paket {{ $member->package->name }}</p>
            @endif
        </div>
    </div>

    {{-- Full Details Grid --}}
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <h3 class="font-bold text-slate-900 font-display text-base mb-6 border-b border-slate-100 pb-3">Informasi Detail Keanggotaan</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @php
                $fields = [
                    ['label' => 'Nomor NIK', 'value' => $member->nik],
                    ['label' => 'Jenis Kelamin', 'value' => $member->gender === 'male' ? 'Laki-laki' : ($member->gender === 'female' ? 'Perempuan' : '—')],
                    ['label' => 'Tanggal Lahir', 'value' => $member->date_of_birth?->format('d M Y')],
                    ['label' => 'Alamat Domisili', 'value' => $member->address],
                    ['label' => 'Status Membership', 'value' => ucfirst($member->status)],
                    ['label' => 'Tanggal Bergabung', 'value' => $member->created_at->format('d M Y')],
                    ['label' => 'Mulai Masa Aktif', 'value' => $member->membership_start_date?->format('d M Y') ?? '—'],
                    ['label' => 'Akhir Masa Aktif', 'value' => $member->membership_end_date?->format('d M Y') ?? '—'],
                ];
            @endphp

            @foreach($fields as $field)
            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $field['label'] }}</p>
                <p class="text-xs font-bold text-slate-800 font-display">{{ $field['value'] ?? '—' }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-6 p-4 bg-sky-50 border border-sky-100 rounded-2xl text-sky-800 text-xs flex items-center gap-3">
            <svg class="w-5 h-5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Perubahan data identitas member dilakukan melalui Administrator GMF. Hubungi petugas jika memerlukan pembaruan profil.</span>
        </div>
    </div>
</div>
@endsection
