@extends('layouts.owner')
@section('title', 'Kelola Staff — Gintung Master Fitness')
@section('page-title', 'Kelola Staff')
@section('page-subtitle', 'Manajemen akun admin dan coach')

@section('content')

<div class="flex justify-end mb-5">
    <a href="{{ route('owner.staff.create') }}"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Staff</span>
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Nama</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Email</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Role</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">No HP</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status Sakelar</th>
                    <th class="px-5 py-3 text-right text-gray-400 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($staff as $u)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#1c1917] text-amber-400 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <span class="font-bold text-gray-800 font-display">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-500 font-medium">{{ $u->email }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $u->role === 'admin' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 font-semibold text-gray-700">
                        {{ $u->phone ?? $u->coach?->phone ?? '—' }}
                    </td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('owner.staff.toggle-active', $u) }}" x-data="{ active: {{ $u->is_active ? 'true' : 'false' }} }" class="flex items-center gap-2">
                            @csrf
                            <button type="submit"
                                @click="active = !active"
                                class="relative inline-flex h-6 w-12 shrink-0 cursor-pointer rounded-full p-1 transition-colors duration-200 ease-in-out focus:outline-none shadow-inner"
                                :class="active ? 'bg-emerald-500' : 'bg-slate-300'"
                                title="{{ $u->is_active ? 'Klik untuk menonaktifkan' : 'Klik untuk mengaktifkan' }}">
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                                    :class="active ? 'translate-x-6' : 'translate-x-0'"></span>
                            </button>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md"
                                :class="active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-rose-50 text-rose-700 border border-rose-200/60'">
                                <span x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                            </span>
                        </form>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex gap-3 justify-end items-center">
                            <a href="{{ route('owner.staff.edit', $u) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">Edit</a>
                            <form method="POST" action="{{ route('owner.staff.destroy', $u) }}" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-bold text-rose-500 hover:text-rose-700 transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada staff.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
