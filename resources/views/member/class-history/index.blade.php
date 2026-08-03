@extends('layouts.member')
@section('page-title', 'Riwayat Kelas')
@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100"><tr>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Kelas</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tanggal</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Jam</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Coach</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($bookings as $b)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $b->schedule->classType->name }}</td>
                <td class="px-5 py-3 text-gray-500">{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</td>
                <td class="px-5 py-3 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($b->schedule->start_time)->format('H:i') }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $b->schedule->coach->user->name }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                        @if($b->status === 'attended') bg-green-100 text-green-700
                        @elseif($b->status === 'no_show') bg-red-100 text-red-600
                        @else bg-gray-100 text-gray-500 @endif">
                        {{ ['attended' => 'Hadir', 'no_show' => 'Tidak Hadir', 'cancelled' => 'Dibatalkan'][$b->status] ?? $b->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat kelas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $bookings->links() }}</div>
</div>
@endsection
