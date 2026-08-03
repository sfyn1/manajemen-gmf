@extends('layouts.admin')
@section('page-title', 'Riwayat Payroll')
@section('content')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Riwayat Pembayaran Coach</h3>
        <a href="{{ route('admin.payroll.index') }}" class="text-sm text-[#f05a2a] hover:underline">← Kembali</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Coach</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Periode</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Sesi</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Total</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tgl Bayar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($payrolls as $p)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $p->coach->user->name }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">
                    {{ \Carbon\Carbon::create()->month($p->month)->format('F') }} {{ $p->year }}
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $p->total_sessions }}</td>
                <td class="px-5 py-3 font-bold">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $p->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $p->status === 'paid' ? 'Lunas' : 'Pending' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-400 text-xs">{{ $p->paid_at ? $p->paid_at->format('d M Y') : '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat payroll.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $payrolls->links() }}</div>
</div>
@endsection
