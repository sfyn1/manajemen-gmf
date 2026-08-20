@extends('layouts.admin')
@section('page-title', 'Edit Rate & Data Coach')
@section('page-subtitle', $coach->user->name)
@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.coaches.update', $coach) }}">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div class="p-4 bg-slate-900 border border-slate-800 rounded-2xl text-xs text-slate-300 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#f05a2a]/20 text-[#f05a2a] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">{{ $coach->user->name }}</p>
                        <p class="text-slate-400 text-xs">{{ $coach->user->email }} (Akun terdaftar via Undangan Staff Owner)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <input type="hidden" name="name" value="{{ $coach->user->name }}">
                    <input type="hidden" name="email" value="{{ $coach->user->email }}">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Rate Komisi per Sesi Mengajar (Rp) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold text-sm">Rp</span>
                            <input type="number" name="rate_per_session" required min="0"
                                value="{{ old('rate_per_session', $coach->rate_per_session) }}"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border @error('rate_per_session') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-[#f05a2a] transition-all">
                        </div>
                        @error('rate_per_session') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">No. Telepon / WhatsApp</label>
                        <input type="tel" name="phone" value="{{ old('phone', $coach->phone) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#f05a2a] transition-all"
                            placeholder="08xx-xxxx-xxxx">
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $coach->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-[#f05a2a]">
                        <label for="is_active" class="text-xs font-bold text-slate-700">Coach Aktif (Dapat Mengajar Kelas)</label>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Bio / Spesialisasi Pelatih</label>
                        <textarea name="bio" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-[#f05a2a] transition-all resize-none"
                            placeholder="Keahlian, sertifikasi, pengalaman mengajar...">{{ old('bio', $coach->bio) }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.coaches.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 border border-slate-200 rounded-xl transition-colors">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-[#f05a2a]/20">
                        Simpan Perubahan Rate & Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
