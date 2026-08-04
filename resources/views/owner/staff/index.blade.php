@extends('layouts.owner')
@section('title', 'Kelola Staff & Undangan — Gintung Master Fitness')
@section('page-title', 'Kelola Staff')
@section('page-subtitle', 'Manajemen akun admin, coach, dan kirim undangan registrasi staff')

@section('content')

<div class="space-y-8">

    {{-- ── 1. FORM KIRIM UNDANGAN STAFF ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 font-display text-base">Kirim Undangan Registrasi Staff Baru</h3>
                <p class="text-xs text-gray-500">Kirim link registrasi bertoken unik (berlaku 48 jam) ke email calon Admin atau Coach.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('owner.staff.invite') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            @csrf
            <div class="sm:col-span-6">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Email Calon Staff <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@gmail.com / email staff..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all">
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Peran / Role <span class="text-rose-500">*</span></label>
                <select name="role" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all">
                    <option value="admin">Admin</option>
                    <option value="coach">Coach (Pelatih)</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <button type="submit"
                    class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Kirim Undangan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ── 2. TABEL MONITORING STATUS TOKEN UNDANGAN ────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 font-display text-sm">Status Undangan Token Staff</h3>
                <p class="text-xs text-gray-500">Daftar token undangan yang dikirimkan oleh Pemilik Gym</p>
            </div>
            <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-md">{{ $invitations->count() }} Undangan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-5 py-3">Email Undangan</th>
                        <th class="text-left px-5 py-3">Role</th>
                        <th class="text-left px-5 py-3">Status Token</th>
                        <th class="text-left px-5 py-3">Kadaluarsa Pada</th>
                        <th class="text-right px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($invitations as $inv)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-semibold text-gray-800">
                            {{ $inv->email }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold uppercase tracking-wider
                                {{ $inv->role === 'admin' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                                {{ ucfirst($inv->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($inv->status === 'pending')
                                <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    Belum Dipakai
                                </span>
                            @elseif($inv->status === 'used')
                                <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    Sudah Dipakai
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                    Kedaluwarsa
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500 font-medium">
                            {{ $inv->expired_at ? $inv->expired_at->format('d M Y, H:i') : '—' }} WIB
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($inv->status === 'pending')
                            <form method="POST" action="{{ route('owner.staff.invitations.cancel', $inv) }}" onsubmit="return confirm('Batalkan undangan ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-bold text-rose-500 hover:text-rose-700 transition-colors">Batal</button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400 font-medium">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400 text-xs">Belum ada undangan staff dikirim.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── 3. TABEL DAFTAR AKUN STAFF TERDAFTAR ─────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 font-display text-sm">Daftar Akun Staff Terdaftar</h3>
                <p class="text-xs text-gray-500">Akun Admin dan Coach yang telah aktif di sistem</p>
            </div>
            <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-md">{{ $staff->count() }} Staff</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-5 py-3">Nama</th>
                        <th class="text-left px-5 py-3">Email</th>
                        <th class="text-left px-5 py-3">Role</th>
                        <th class="text-left px-5 py-3">No HP</th>
                        <th class="text-left px-5 py-3">Status Sakelar</th>
                        <th class="text-right px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($staff as $u)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-[#1c1917] text-amber-400 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-gray-800 font-display">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-500 font-medium">{{ $u->email }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wider
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
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full p-1 transition-colors duration-200 ease-in-out focus:outline-none shadow-inner"
                                    :class="active ? 'bg-emerald-500' : 'bg-slate-300'"
                                    title="{{ $u->is_active ? 'Klik untuk menonaktifkan' : 'Klik untuk mengaktifkan' }}">
                                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                                        :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md"
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
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada staff terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
