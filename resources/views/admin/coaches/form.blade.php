@extends('layouts.admin')
@section('page-title', $coach ? 'Edit Coach' : 'Tambah Coach')
@section('page-subtitle', $coach ? $coach->user->name : 'Tambahkan pelatih baru')
@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ $coach ? route('admin.coaches.update', $coach) : route('admin.coaches.store') }}">
            @csrf
            @if($coach) @method('PUT') @endif

            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $coach?->user?->name) }}"
                            class="w-full px-4 py-3 bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] transition-all">
                        @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Email Login <span class="text-red-400">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $coach?->user?->email) }}"
                            class="w-full px-4 py-3 bg-gray-50 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] transition-all">
                        @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">
                            Password {{ $coach ? '(kosongkan jika tidak diubah)' : '*' }}
                        </label>
                        <input type="password" name="password" {{ $coach ? '' : 'required' }}
                            class="w-full px-4 py-3 bg-gray-50 border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] transition-all"
                            placeholder="{{ $coach ? '••••••••' : 'Min 8 karakter' }}">
                        @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">No. HP</label>
                        <input type="tel" name="phone" value="{{ old('phone', $coach?->phone) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] transition-all"
                            placeholder="08xx-xxxx-xxxx">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Rate per Sesi <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 text-sm">Rp</span>
                            <input type="number" name="rate_per_session" required min="0"
                                value="{{ old('rate_per_session', $coach?->rate_per_session) }}"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border @error('rate_per_session') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] transition-all">
                        </div>
                        @error('rate_per_session') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Bio / Deskripsi</label>
                        <textarea name="bio" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a] transition-all resize-none"
                            placeholder="Keahlian, sertifikasi, pengalaman...">{{ old('bio', $coach?->bio) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $coach?->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-[#f05a2a]">
                        <label for="is_active" class="text-sm font-medium text-gray-600">Coach aktif (dapat mengajar)</label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.coaches.index') }}" class="px-5 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#f05a2a] hover:bg-[#c8451a] text-white text-sm font-semibold rounded-xl transition-colors">
                        {{ $coach ? 'Simpan Perubahan' : 'Tambah Coach' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
