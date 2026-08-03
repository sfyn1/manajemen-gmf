@extends('layouts.coach')
@section('page-title', 'Riwayat Mengajar')
@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100"><tr>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Kelas</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tanggal</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Jam</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status</th>
            <th class="text-left px-5 py-3 text-gray-400 font-semibold">Komisi Sesi</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($verifications as $v)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $v->schedule->classType->name }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $v->session_date->format('d M Y') }}</td>
                <td class="px-5 py-3 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($v->schedule->start_time)->format('H:i') }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                        @if($v->status === 'approved') bg-green-100 text-green-700
                        @elseif($v->status === 'pending') bg-amber-100 text-amber-700
                        @elseif($v->status === 'rejected') bg-red-100 text-red-600
                        @else bg-gray-100 text-gray-500 @endif">
                        {{ ['approved' => 'Disetujui', 'pending' => 'Pending', 'rejected' => 'Ditolak', 'auto_failed' => 'Auto-Gagal'][$v->status] ?? $v->status }}
                    </span>
                </td>
                <td class="px-5 py-3 font-semibold {{ $v->status === 'approved' ? 'text-green-600' : 'text-gray-300' }}">
                    {{ $v->status === 'approved' ? 'Rp ' . number_format($coach->rate_per_session, 0, ',', '.') : '—' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat mengajar.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $verifications->links() }}</div>
</div>
@endsection
