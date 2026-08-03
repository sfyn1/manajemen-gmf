@extends('layouts.admin')
@section('page-title', 'Riwayat Penjualan Produk')
@section('content')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Transaksi Penjualan</h3>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-[#f05a2a] hover:underline">← Kembali</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Produk</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Qty</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Total</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Metode</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Member</th>
                <th class="text-left px-5 py-3 text-gray-400 font-semibold">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($sales as $sale)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $sale->product?->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-500 font-semibold">{{ $sale->quantity }}</td>
                <td class="px-5 py-3 font-semibold text-gray-700">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                        @if($sale->payment_method === 'cash') bg-emerald-50 text-emerald-700 border border-emerald-200/60
                        @elseif($sale->payment_method === 'qris') bg-sky-50 text-sky-700 border border-sky-200/60
                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                        {{ strtoupper($sale->payment_method) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs font-medium">{{ $sale->buyer_name ?? 'Pelanggan Umum' }}</td>
                <td class="px-5 py-3 text-gray-400 text-xs">{{ $sale->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada transaksi penjualan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $sales->links() }}</div>
</div>
@endsection
