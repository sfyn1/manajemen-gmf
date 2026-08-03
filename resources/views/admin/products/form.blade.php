@extends('layouts.admin')
@section('page-title', $product ? 'Edit Produk' : 'Tambah Produk')
@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}">
            @csrf
            @if($product) @method('PUT') @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $product?->name) }}"
                        class="w-full px-4 py-3 bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Kategori <span class="text-red-400">*</span></label>
                        <select name="category" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                            @foreach(['supplement' => 'Suplemen', 'equipment' => 'Peralatan', 'drink' => 'Minuman', 'snack' => 'Snack', 'other' => 'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('category', $product?->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Satuan <span class="text-red-400">*</span></label>
                        <input type="text" name="unit" required value="{{ old('unit', $product?->unit) }}" placeholder="pcs, botol, kg..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Harga (Rp) <span class="text-red-400">*</span></label>
                        <input type="number" name="price" required min="0" value="{{ old('price', $product?->price) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Stok <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" required min="0" value="{{ old('stock', $product?->stock) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] resize-none">{{ old('description', $product?->description) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#f05a2a]">
                    <label for="is_active" class="text-sm font-medium text-gray-600">Produk aktif / dijual</label>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-[#f05a2a] hover:bg-[#c8451a] text-white text-sm font-semibold rounded-xl">
                        {{ $product ? 'Simpan' : 'Tambah Produk' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
