@extends('layouts.admin')

@section('title', 'Konten Landing Page')
@section('page-title', 'Konten Landing Page')
@section('page-subtitle', 'CMS – atur tampilan halaman publik')

@section('content')
<div class="space-y-6" x-data="landingCMS()">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-gray-800 font-display">Manajemen Konten</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $contents->count() }} konten tersedia</p>
        </div>
        <button @click="openModal()"
            class="bg-[#f05a2a] hover:bg-[#c8451a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold flex items-center gap-2 transition-colors shadow-sm shadow-[#f05a2a]/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Konten Baru
        </button>
    </div>

    {{-- ── Section Filter Tabs ─────────────────────────────────────────────── --}}
    <div class="flex flex-wrap gap-2">
        @php
            $sections = ['all' => 'Semua', 'hero' => 'Hero', 'gallery' => 'Gallery', 'promo' => 'Promo', 'info' => 'Info'];
            $sectionColors = [
                'hero'    => 'bg-purple-100 text-purple-700',
                'gallery' => 'bg-blue-100 text-blue-700',
                'promo'   => 'bg-[#f05a2a]/10 text-[#f05a2a]',
                'info'    => 'bg-teal-100 text-teal-700',
            ];
        @endphp
        @foreach($sections as $key => $label)
        <button
            @click="filterSection = '{{ $key }}'"
            :class="filterSection === '{{ $key }}'
                ? 'bg-[#f05a2a] text-white shadow-sm shadow-[#f05a2a]/30'
                : 'bg-white text-gray-600 border border-gray-200 hover:border-[#f05a2a]/40'"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ── Content Grid ─────────────────────────────────────────────────────── --}}
    @if($contents->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">Belum ada konten. Klik "Tambah Konten Baru" untuk mulai.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" id="contentGrid">
        @foreach($contents as $index => $item)
        <div
            data-id="{{ $item->id }}"
            x-show="filterSection === 'all' || filterSection === '{{ $item->section }}'"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md hover:border-[#f05a2a]/20 transition-all duration-200 flex flex-col">

            {{-- Image / Placeholder --}}
            <div class="relative h-40 bg-gradient-to-br from-gray-100 to-gray-50 shrink-0 overflow-hidden">
                @if($item->image_path)
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                {{-- Order badge --}}
                <div class="absolute top-2 left-2 w-7 h-7 rounded-lg bg-black/50 backdrop-blur-sm flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ $item->sort_order }}</span>
                </div>

                {{-- Active badge --}}
                <div class="absolute top-2 right-2">
                    @if($item->is_active)
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold bg-green-500 text-white shadow">Aktif</span>
                    @else
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold bg-gray-500 text-white shadow">Nonaktif</span>
                    @endif
                </div>

                {{-- Reorder arrows --}}
                <div class="absolute bottom-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    @if($index > 0)
                    <button onclick="moveItem({{ $item->id }}, 'up')"
                        class="w-6 h-6 rounded-lg bg-white/90 backdrop-blur-sm shadow flex items-center justify-center text-gray-600 hover:text-[#f05a2a] transition-colors"
                        title="Pindah ke atas">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    @endif
                    @if($index < $contents->count() - 1)
                    <button onclick="moveItem({{ $item->id }}, 'down')"
                        class="w-6 h-6 rounded-lg bg-white/90 backdrop-blur-sm shadow flex items-center justify-center text-gray-600 hover:text-[#f05a2a] transition-colors"
                        title="Pindah ke bawah">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex items-center gap-2 mb-2">
                    @php
                        $colors = [
                            'hero'    => 'bg-purple-100 text-purple-700',
                            'gallery' => 'bg-blue-100 text-blue-700',
                            'promo'   => 'bg-[#f05a2a]/10 text-[#f05a2a]',
                            'info'    => 'bg-teal-100 text-teal-700',
                        ];
                    @endphp
                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $colors[$item->section] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $item->section }}
                    </span>
                </div>
                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $item->title }}</h3>
                @if($item->body)
                    <p class="text-xs text-gray-400 line-clamp-2 flex-1">{{ $item->body }}</p>
                @else
                    <p class="text-xs text-gray-300 flex-1 italic">Tidak ada deskripsi</p>
                @endif

                {{-- Actions --}}
                <div class="flex gap-2 mt-3 pt-3 border-t border-gray-50">
                    <button @click="openModal({{ json_encode($item) }})"
                        class="flex-1 bg-gray-100 hover:bg-[#f05a2a]/10 hover:text-[#f05a2a] text-gray-600 rounded-lg px-3 py-1.5 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                    <form method="POST" action="{{ route('admin.landing-content.destroy', $item) }}"
                        onsubmit="return confirm('Hapus konten ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-50 hover:bg-red-100 text-red-500 rounded-lg px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Modal Tambah / Edit ──────────────────────────────────────────────── --}}
    <div x-show="modalOpen" x-transition.opacity
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
        @click.self="closeModal()">

        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 font-display" x-text="editingItem ? 'Edit Konten' : 'Tambah Konten Baru'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form :action="editingItem ? '{{ url('admin/landing-content') }}/' + editingItem.id : '{{ route('admin.landing-content.store') }}'"
                method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" :value="editingItem ? 'PUT' : 'POST'">

                {{-- Section --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Section <span class="text-red-400">*</span></label>
                    <select name="section" x-model="form.section" required
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] outline-none">
                        <option value="">Pilih Section</option>
                        <option value="hero">Hero</option>
                        <option value="gallery">Gallery</option>
                        <option value="promo">Promo</option>
                        <option value="info">Info</option>
                    </select>
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Judul <span class="text-red-400">*</span></label>
                    <input type="text" name="title" x-model="form.title" required
                        placeholder="Masukkan judul konten..."
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] outline-none">
                </div>

                {{-- Body --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Deskripsi</label>
                    <textarea name="body" x-model="form.body" rows="3"
                        placeholder="Tulis deskripsi singkat..."
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] outline-none resize-none"></textarea>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Gambar</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-[#f05a2a]/40 transition-colors"
                        x-data="imagePreview()">
                        <input type="file" name="image" accept="image/*" class="hidden" id="imageUpload"
                            @change="preview($event)">
                        <label for="imageUpload" class="cursor-pointer block">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="w-full h-32 object-cover rounded-lg mb-2">
                            </template>
                            <template x-if="!previewUrl">
                                <div>
                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs text-gray-400">Klik untuk upload gambar</p>
                                    <p class="text-[10px] text-gray-300 mt-0.5">JPG, PNG, WebP – maks. 2MB</p>
                                </div>
                            </template>
                        </label>
                    </div>
                    <template x-if="editingItem && editingItem.image_path">
                        <p class="text-xs text-gray-400 mt-1">Gambar saat ini akan diganti jika kamu upload baru.</p>
                    </template>
                </div>

                {{-- Sort Order + Active --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" x-model="form.sort_order" min="0" required
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Publikasi</label>
                        <div class="flex items-center h-10 px-3 border border-gray-200 rounded-xl bg-gray-50/50">
                            <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#f05a2a]"></div>
                                <span class="text-xs font-semibold text-gray-700" x-text="form.is_active ? 'Status: Aktif' : 'Status: Nonaktif'"></span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="closeModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-[#f05a2a] hover:bg-[#c8451a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="editingItem ? 'Simpan Perubahan' : 'Tambah Konten'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function landingCMS() {
    return {
        modalOpen: false,
        editingItem: null,
        filterSection: 'all',
        form: {
            section: '',
            title: '',
            body: '',
            sort_order: 0,
            is_active: true,
        },

        openModal(item = null) {
            this.editingItem = item;
            if (item) {
                this.form.section    = item.section;
                this.form.title      = item.title;
                this.form.body       = item.body ?? '';
                this.form.sort_order = item.sort_order;
                this.form.is_active  = !!item.is_active;
            } else {
                this.form = { section: '', title: '', body: '', sort_order: 0, is_active: true };
            }
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.editingItem = null;
        },
    };
}

function imagePreview() {
    return {
        previewUrl: null,
        preview(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            }
        },
    };
}

// Reorder via arrows
function moveItem(id, direction) {
    const grid  = document.getElementById('contentGrid');
    const cards = Array.from(grid.querySelectorAll('[data-id]'));
    const idx   = cards.findIndex(c => c.dataset.id == id);

    if (direction === 'up' && idx > 0) {
        grid.insertBefore(cards[idx], cards[idx - 1]);
    } else if (direction === 'down' && idx < cards.length - 1) {
        grid.insertBefore(cards[idx + 1], cards[idx]);
    }

    // Send new order to server
    const ids = Array.from(grid.querySelectorAll('[data-id]')).map(c => parseInt(c.dataset.id));
    fetch('{{ route('admin.landing-content.reorder') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ ids }),
    });
}
</script>
@endpush
