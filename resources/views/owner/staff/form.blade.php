@extends('layouts.owner')
@section('page-title', $user ? 'Edit Staff' : 'Tambah Staff')
@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ $user ? route('owner.staff.update', $user) : route('owner.staff.store') }}">
            @csrf @if($user) @method('PUT') @endif
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $user?->name) }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" required value="{{ old('email', $user?->email) }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Password {{ $user ? '(kosong = tidak diubah)' : '*' }}</label>
                    <input type="password" name="password" {{ $user ? '' : 'required' }}
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Role <span class="text-red-400">*</span></label>
                    <select name="role" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                        <option value="admin" {{ old('role', $user?->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="coach" {{ old('role', $user?->role) === 'coach' ? 'selected' : '' }}>Coach</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">No HP</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user?->phone ?? $user?->coach?->phone) }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-amber-500">
                    <label for="is_active" class="text-sm font-medium text-gray-600">Akun aktif</label>
                </div>
                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('owner.staff.index') }}" class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
