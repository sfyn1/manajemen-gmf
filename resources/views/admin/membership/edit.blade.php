@extends('layouts.admin')

@section('title', 'Edit Member — ' . $member->full_name)
@section('page-title', 'Edit Data Member')
@section('page-subtitle', $member->full_name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.membership.index') }}" class="hover:text-[#f05a2a] transition-colors">Membership</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.membership.show', $member) }}" class="hover:text-[#f05a2a] transition-colors">{{ $member->full_name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Edit</span>
    </nav>

    <form method="POST" action="{{ route('admin.membership.update', $member) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
            <h2 class="font-bold text-gray-800 font-display text-base">Informasi Keanggotaan</h2>

            {{-- Paket --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Paket Membership</label>
                <select name="membership_package_id"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all @error('membership_package_id') border-red-400 @enderror">
                    @foreach($packages as $pkg)
                    <option value="{{ $pkg->id }}" {{ old('membership_package_id', $member->membership_package_id) == $pkg->id ? 'selected' : '' }}>
                        {{ $pkg->name }} — {{ $pkg->formatted_price }}
                    </option>
                    @endforeach
                </select>
                @error('membership_package_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Keanggotaan</label>
                <select name="status"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all @error('status') border-red-400 @enderror">
                    <option value="pending_verification" {{ old('status', $member->status) === 'pending_verification' ? 'selected' : '' }}>Pending Verifikasi</option>
                    <option value="active"               {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="rejected"             {{ old('status', $member->status) === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="expired"              {{ old('status', $member->status) === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alasan penolakan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alasan Penolakan <span class="text-gray-400 font-normal">(jika ditolak)</span></label>
                <textarea name="rejection_reason" rows="3"
                          placeholder="Tuliskan alasan jika status ditolak..."
                          class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all @error('rejection_reason') border-red-400 @enderror">{{ old('rejection_reason', $member->rejection_reason) }}</textarea>
                @error('rejection_reason')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="membership_start_date"
                           value="{{ old('membership_start_date', $member->membership_start_date?->format('Y-m-d')) }}"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all @error('membership_start_date') border-red-400 @enderror">
                    @error('membership_start_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Berakhir</label>
                    <input type="date" name="membership_end_date"
                           value="{{ old('membership_end_date', $member->membership_end_date?->format('Y-m-d')) }}"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/30 focus:border-[#f05a2a] transition-all @error('membership_end_date') border-red-400 @enderror">
                    @error('membership_end_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex items-center gap-3 justify-end">
            <a href="{{ route('admin.membership.show', $member) }}"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="bg-[#f05a2a] hover:bg-[#c8451a] text-white rounded-xl px-6 py-2.5 text-sm font-semibold transition-colors shadow-sm shadow-[#f05a2a]/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
