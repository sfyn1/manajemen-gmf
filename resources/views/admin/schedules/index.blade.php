@extends('layouts.admin')

@section('title', 'Jadwal Kelas — Gintung Master Fitness')
@section('page-title', 'Manajemen Jadwal Kelas')
@section('page-subtitle', 'Pengaturan jenis kelas, jam operasional, dan penugasan pelatih')

@section('content')

<div x-data="{ showTypeForm: false, editType: null, editTypeName: '' }" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Jenis Kelas Panel --}}
    <div>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-900 font-display text-base">Jenis Kelas</h3>
                        <p class="text-xs text-slate-500">Kategori program kebugaran</p>
                    </div>
                    <button @click="showTypeForm=!showTypeForm; editType=null; editTypeName=''"
                        class="w-8 h-8 rounded-xl bg-[#f05a2a] text-white flex items-center justify-center text-base font-bold hover:bg-[#e13b12] transition-colors shadow-sm">+</button>
                </div>

                {{-- Form tambah/edit jenis --}}
                <div x-show="showTypeForm" x-transition class="p-4 bg-slate-50 border-b border-slate-100">
                    <form method="POST" :action="editType ? `/admin/class-types/${editType}` : '{{ route('admin.class-types.store') }}'">
                        @csrf
                        <template x-if="editType">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="text" name="name" x-model="editTypeName" required placeholder="Nama jenis kelas (cth: Zumba)..."
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs mb-3 focus:outline-none focus:border-[#f05a2a]">
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 py-2 bg-[#f05a2a] text-white text-xs font-bold rounded-xl shadow-sm">Simpan</button>
                            <button type="button" @click="showTypeForm=false; editType=null; editTypeName=''" class="py-2 px-3 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl">Batal</button>
                        </div>
                    </form>
                </div>

                <ul class="divide-y divide-slate-100 max-h-80 overflow-y-auto scrollbar-thin text-xs">
                    @forelse($classTypes as $ct)
                    <li class="flex items-center justify-between px-6 py-3.5 hover:bg-slate-50/70 transition-colors">
                        <span class="font-bold text-slate-800 font-display">{{ $ct->name }}</span>
                        <div class="flex items-center gap-1">
                            <button @click="showTypeForm=true; editType={{ $ct->id }}; editTypeName='{{ $ct->name }}'"
                                class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.class-types.destroy', $ct) }}" onsubmit="return confirm('Hapus jenis kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-8 text-center text-slate-400">Belum ada jenis kelas terdaftar</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Jadwal Per Hari --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="flex justify-end">
            <a href="{{ route('admin.schedules.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Jadwal Sesi Baru</span>
            </a>
        </div>

        @forelse($grouped as $day => $schedules)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-900 text-white flex justify-between items-center">
                <h4 class="font-bold font-display text-sm tracking-wide">{{ \App\Models\ClassSchedule::DAYS[$day] ?? $day }}</h4>
                <span class="text-[11px] font-semibold text-slate-400">{{ count($schedules) }} Sesi</span>
            </div>
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                        <tr>
                            <th class="px-6 py-3.5">Jenis Kelas</th>
                            <th class="px-6 py-3.5">Jam Latihan</th>
                            <th class="px-6 py-3.5">Pelatih</th>
                            <th class="px-6 py-3.5">Kapasitas</th>
                            <th class="px-6 py-3.5">Tarif Sesi</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($schedules as $s)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#f05a2a]/15 text-[#f05a2a]">{{ $s->classType->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">
                                {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }} WIB
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 font-display">{{ $s->coach->user->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $s->max_capacity }} Orang</td>
                            <td class="px-6 py-4 text-slate-800 font-bold">Rp {{ number_format($s->session_fee, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $s->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ $s->is_active ? 'Aktif' : 'Off' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.schedules.edit', $s) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold text-[11px] transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg font-semibold text-[11px] transition-colors">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center text-slate-400">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="font-bold text-slate-800 text-sm">Belum Ada Jadwal Kelas Terdaftar</p>
            <p class="text-xs text-slate-500 mt-1">Klik "Tambah Jadwal Sesi Baru" untuk menyusun jadwal mingguan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
