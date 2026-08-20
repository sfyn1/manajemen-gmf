@extends('layouts.admin')
@section('title', 'Paket Membership — Gintung Master Fitness')
@section('page-title', 'Kelola Paket Membership')
@section('page-subtitle', 'Pengaturan harga, durasi, dan jenis keanggotaan fitness')
@section('content')

<div x-data="{ showModal: false, editPkg: null, form: { name:'', type:'monthly_regular', price:'', duration_days:'', description:'', required_document:'ktp', is_active:true } }">

    <div class="flex justify-end mb-6">
        <button @click="showModal=true; editPkg=null; form={name:'',type:'monthly_regular',price:'',duration_days:'',description:'',required_document:'ktp',is_active:true}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Paket Baru</span>
        </button>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($packages as $pkg)
        <div class="bg-white rounded-3xl border {{ $pkg->is_active ? 'border-slate-200/80' : 'border-dashed border-slate-300 opacity-60' }} shadow-sm p-6 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if($pkg->type === 'monthly_regular') bg-sky-50 text-sky-700 border border-sky-200
                            @elseif($pkg->type === 'monthly_student') bg-purple-50 text-purple-700 border border-purple-200
                            @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                            {{ ['monthly_regular' => 'Reguler', 'monthly_student' => 'Pelajar', 'daily' => 'Harian'][$pkg->type] }}
                        </span>
                        <h3 class="font-bold text-slate-900 font-display text-base mt-2">{{ $pkg->name }}</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $pkg->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <p class="text-3xl font-extrabold text-[#f05a2a] font-display mb-1">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 font-medium mb-3">{{ $pkg->duration_days }} Hari • Syarat Dokumen: {{ strtoupper($pkg->required_document) }}</p>
                @if($pkg->description)
                <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $pkg->description }}</p>
                @endif
            </div>

            <div class="flex gap-2 pt-4 border-t border-slate-100">
                <button @click="
                    showModal=true;
                    editPkg={{ $pkg->id }};
                    form={name:'{{ $pkg->name }}',type:'{{ $pkg->type }}',price:'{{ $pkg->price }}',duration_days:'{{ $pkg->duration_days }}',description:'{{ addslashes($pkg->description) }}',required_document:'{{ $pkg->required_document }}',is_active:{{ $pkg->is_active ? 'true' : 'false' }}}
                "
                class="flex-1 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Edit</button>

                <form method="POST" action="{{ route('admin.packages.destroy', $pkg) }}" onsubmit="return confirm('Nonaktifkan paket ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="py-2 px-4 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors">Nonaktifkan</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-3xl border border-slate-200/80 p-12 text-center text-slate-400">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <p class="font-bold text-slate-800 text-sm">Belum Ada Paket</p>
            <p class="text-xs text-slate-500 mt-1">Klik tombol Tambah Paket Baru untuk membuat skema harga keanggotaan.</p>
        </div>
        @endforelse
    </div>

    {{-- Modal Tambah/Edit --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm" @click.self="showModal=false">
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-lg mx-4">
            <h3 class="font-bold text-slate-900 font-display text-base mb-5" x-text="editPkg ? 'Edit Paket Membership' : 'Tambah Paket Baru'"></h3>
            <form :action="editPkg ? `/admin/packages/${editPkg}` : '{{ route('admin.packages.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editPkg">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Nama Paket</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Tipe Kategori</label>
                        <select name="type" x-model="form.type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a]">
                            <option value="monthly_regular">Reguler Bulanan</option>
                            <option value="monthly_student">Pelajar Bulanan</option>
                            <option value="daily">Harian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Harga (Rp)</label>
                        <input type="number" name="price" x-model="form.price" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Durasi (Hari)</label>
                        <input type="number" name="duration_days" x-model="form.duration_days" required min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Dokumen Wajib</label>
                        <select name="required_document" x-model="form.required_document" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a]">
                            <option value="ktp">KTP</option>
                            <option value="ktm">KTM / Kartu Pelajar</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi Ringkas</label>
                        <textarea name="description" x-model="form.description" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#f05a2a] resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="pkg_active" :checked="form.is_active" @change="form.is_active=$event.target.checked" class="w-4 h-4 accent-[#f05a2a]">
                        <label for="pkg_active" class="text-xs font-semibold text-slate-700">Status Aktif</label>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showModal=false" class="px-5 py-2.5 text-xs font-bold border border-slate-200 rounded-xl text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">Simpan Data Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
