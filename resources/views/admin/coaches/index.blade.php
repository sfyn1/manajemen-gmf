@extends('layouts.admin')
@section('title', 'Manajemen Pelatih — Gintung Master Fitness')
@section('page-title', 'Manajemen Pelatih (Coach)')
@section('page-subtitle', 'Kelola data pelatih dan rate komisi per sesi')
@section('content')

<div class="mb-6">
    <div class="p-4 bg-slate-900 border border-slate-800 rounded-2xl text-xs text-slate-300 flex items-center justify-between gap-3 shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-[#f05a2a]/20 text-[#f05a2a] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span>Akun pelatih baru dibuat melalui <strong>Undangan Email Staff</strong> oleh Owner. Gunakan opsi <strong>Edit</strong> di bawah untuk mengatur <strong>Rate Komisi / Sesi</strong>, kontak, & status pelatih.</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    @if($coaches->isEmpty())
    <div class="p-12 text-center text-slate-400">
        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <p class="font-bold text-slate-800 text-sm">Belum Ada Pelatih Terdaftar</p>
        <a href="{{ route('admin.coaches.create') }}" class="text-[#f05a2a] font-semibold text-xs mt-2 inline-block hover:underline">Tambah data pelatih pertama →</a>
    </div>
    @else
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                <tr>
                    <th class="px-6 py-4">Nama Pelatih</th>
                    <th class="px-6 py-4">Email Account</th>
                    <th class="px-6 py-4">No. HP</th>
                    <th class="px-6 py-4">Rate / Sesi</th>
                    <th class="px-6 py-4">Jadwal Sesi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($coaches as $coach)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($coach->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm font-display">{{ $coach->user->name }}</p>
                                @if($coach->bio)
                                <p class="text-xs text-slate-400 truncate max-w-xs">{{ Str::limit($coach->bio, 45) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $coach->user->email }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $coach->phone ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">Rp {{ number_format($coach->rate_per_session, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-slate-600 font-semibold">{{ $coach->schedules->count() }} Jadwal</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $coach->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                            {{ $coach->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.coaches.edit', $coach) }}"
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">Edit</a>
                            <form method="POST" action="{{ route('admin.coaches.destroy', $coach) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatih {{ $coach->user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $coaches->links() }}</div>
    @endif
</div>
@endsection
