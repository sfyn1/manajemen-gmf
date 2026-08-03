@extends('layouts.admin')
@section('page-title', $schedule ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ $schedule ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}">
            @csrf
            @if($schedule) @method('PUT') @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Jenis Kelas <span class="text-red-400">*</span></label>
                    <select name="class_type_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        <option value="">Pilih jenis kelas...</option>
                        @foreach($classTypes as $ct)
                        <option value="{{ $ct->id }}" {{ old('class_type_id', $schedule?->class_type_id) == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                    @error('class_type_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Coach <span class="text-red-400">*</span></label>
                    <select name="coach_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        <option value="">Pilih coach...</option>
                        @foreach($coaches as $c)
                        <option value="{{ $c->id }}" {{ old('coach_id', $schedule?->coach_id) == $c->id ? 'selected' : '' }}>{{ $c->user->name }}</option>
                        @endforeach
                    </select>
                    @error('coach_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Hari <span class="text-red-400">*</span></label>
                    <select name="day_of_week" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        @foreach(\App\Models\ClassSchedule::DAYS as $key => $label)
                        <option value="{{ $key }}" {{ old('day_of_week', $schedule?->day_of_week) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Jam Mulai <span class="text-red-400">*</span></label>
                        <input type="time" name="start_time" required value="{{ old('start_time', $schedule?->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        @error('start_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Jam Selesai <span class="text-red-400">*</span></label>
                        <input type="time" name="end_time" required value="{{ old('end_time', $schedule?->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        @error('end_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Kapasitas Maks <span class="text-red-400">*</span></label>
                        <input type="number" name="max_capacity" required min="1" value="{{ old('max_capacity', $schedule?->max_capacity) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        @error('max_capacity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Biaya Sesi (Rp) <span class="text-red-400">*</span></label>
                        <input type="number" name="session_fee" required min="0" value="{{ old('session_fee', $schedule?->session_fee) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                        @error('session_fee') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $schedule?->is_active ?? true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded accent-[#f05a2a]">
                    <label for="is_active" class="text-sm font-medium text-gray-600">Jadwal aktif</label>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.schedules.index') }}" class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-[#f05a2a] hover:bg-[#c8451a] text-white text-sm font-semibold rounded-xl">
                        {{ $schedule ? 'Simpan Perubahan' : 'Tambah Jadwal' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
