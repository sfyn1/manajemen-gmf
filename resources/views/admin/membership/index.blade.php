@extends('layouts.admin')

@section('title', 'Manajemen Membership')
@section('page-title', 'Manajemen Membership')
@section('page-subtitle', 'Kelola data dan status keanggotaan member')

@section('content')
<div class="space-y-6">

    {{-- ── Header Stats ─────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @php
            $statCards = [
                ['label' => 'Semua',    'key' => 'all',                  'color' => 'bg-gray-100 text-gray-700',   'dot' => 'bg-gray-400'],
                ['label' => 'Aktif',    'key' => 'active',               'color' => 'bg-green-50 text-green-700',  'dot' => 'bg-green-500'],
                ['label' => 'Pending',  'key' => 'pending_verification', 'color' => 'bg-yellow-50 text-yellow-700','dot' => 'bg-yellow-500'],
                ['label' => 'Ditolak', 'key' => 'rejected',             'color' => 'bg-red-50 text-red-700',      'dot' => 'bg-red-500'],
                ['label' => 'Kadaluarsa','key' => 'expired',            'color' => 'bg-gray-50 text-gray-500',   'dot' => 'bg-gray-300'],
            ];
        @endphp
        @foreach($statCards as $card)
        <a href="{{ route('admin.membership.index', ['status' => $card['key']]) }}"
           class="flex items-center gap-3 p-3 rounded-xl border {{ $status === $card['key'] ? 'border-[#f05a2a] ring-2 ring-[#f05a2a]/20 bg-[#f05a2a]/5' : 'border-gray-200 bg-white hover:border-gray-300' }} transition-all shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full {{ $card['dot'] }} shrink-0"></span>
            <div class="min-w-0">
                <p class="text-xs text-gray-500 truncate">{{ $card['label'] }}</p>
                <p class="text-xl font-bold font-display {{ $status === $card['key'] ? 'text-[#f05a2a]' : 'text-gray-800' }}">
                    {{ $statusCounts[$card['key']] }}
                </p>
            </div>
        </a>
        @endforeach
    </div>

    {{-- ── Toolbar: search + filter ─────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.membership.index') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row gap-3">
        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau NIK..."
                   class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all">
        </div>
        {{-- Status filter --}}
        <select name="status"
                class="sm:w-48 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all">
            <option value="all"                  {{ $status === 'all'                  ? 'selected' : '' }}>Semua Status</option>
            <option value="active"               {{ $status === 'active'               ? 'selected' : '' }}>Aktif</option>
            <option value="pending_verification" {{ $status === 'pending_verification' ? 'selected' : '' }}>Pending Verifikasi</option>
            <option value="rejected"             {{ $status === 'rejected'             ? 'selected' : '' }}>Ditolak</option>
            <option value="expired"              {{ $status === 'expired'              ? 'selected' : '' }}>Kadaluarsa</option>
        </select>
        <button type="submit"
                class="bg-[#f05a2a] hover:bg-[#c8451a] text-white rounded-xl px-5 py-2.5 text-sm font-semibold transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
        </button>
    </form>

    {{-- ── Tabel Member ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 font-display">Daftar Member</h2>
            <p class="text-sm text-gray-400">{{ $members->total() }} total member</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-3 text-left font-semibold">Member</th>
                        <th class="px-4 py-3 text-left font-semibold">NIK</th>
                        <th class="px-4 py-3 text-left font-semibold">Paket</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Masa Berlaku</th>
                        <th class="px-4 py-3 text-center font-semibold">QR Token</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $member)
                    @php
                        $initials = collect(explode(' ', $member->full_name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                        $statusMap = [
                            'active'               => ['label' => 'Aktif',    'class' => 'bg-green-100 text-green-700'],
                            'pending_verification' => ['label' => 'Pending',  'class' => 'bg-yellow-100 text-yellow-700'],
                            'rejected'             => ['label' => 'Ditolak',  'class' => 'bg-red-100 text-red-700'],
                            'expired'              => ['label' => 'Kadaluarsa','class' => 'bg-gray-100 text-gray-500'],
                        ];
                        $badge = $statusMap[$member->status] ?? ['label' => $member->status, 'class' => 'bg-gray-100 text-gray-600'];
                    @endphp
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        {{-- Foto + Nama --}}
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#f05a2a] to-[#c8451a] flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 leading-tight">{{ $member->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- NIK --}}
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $member->nik ?? '-' }}</td>
                        {{-- Paket --}}
                        <td class="px-4 py-3">
                            <span class="text-gray-700 font-medium">{{ $member->package?->name ?? '—' }}</span>
                        </td>
                        {{-- Status badge --}}
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        {{-- Masa berlaku --}}
                        <td class="px-4 py-3 text-gray-600">
                            @if($member->membership_end_date)
                                <span class="{{ $member->membership_end_date->isPast() ? 'text-red-500 font-semibold' : '' }}">
                                    {{ $member->membership_end_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        {{-- QR Token --}}
                        <td class="px-4 py-3 text-center">
                            @if($member->qr_token)
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Ada
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Action --}}
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.membership.show', $member) }}"
                               class="inline-flex items-center gap-1.5 bg-[#1e3a5f] hover:bg-[#152c4a] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-sm font-medium">Tidak ada member ditemukan</p>
                                <p class="text-xs">Coba ubah filter atau kata kunci pencarian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan {{ $members->firstItem() }}–{{ $members->lastItem() }} dari {{ $members->total() }} member
            </p>
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if($members->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $members->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">← Prev</a>
                @endif
                {{-- Pages --}}
                @foreach($members->getUrlRange(max(1, $members->currentPage()-2), min($members->lastPage(), $members->currentPage()+2)) as $page => $url)
                    <a href="{{ $url }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $page === $members->currentPage() ? 'bg-[#f05a2a] text-white font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $page }}
                    </a>
                @endforeach
                {{-- Next --}}
                @if($members->hasMorePages())
                    <a href="{{ $members->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">Next →</a>
                @else
                    <span class="px-3 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
