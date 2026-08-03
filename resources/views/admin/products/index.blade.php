@extends('layouts.admin')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Inventori & penjualan produk')
@section('content')

<div x-data="quickSell()" class="space-y-5">
    {{-- ── Quick Sell ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-bold text-gray-700 mb-4">⚡ Quick Sell</h3>
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Produk</label>
                <select x-model="productId" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                    <option value="">Pilih produk...</option>
                    @foreach($allProducts as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-name="{{ $p->name }}">{{ $p->name }} (Stok: {{ $p->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-24">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Qty</label>
                <input type="number" x-model="qty" min="1" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Metode Bayar</label>
                <select x-model="paymentMethod" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#f05a2a]/20 focus:border-[#f05a2a]">
                    <option value="cash">Cash (Tunai)</option>
                    <option value="qris">QRIS Digital</option>
                </select>
            </div>
            <button @click="sell()" :disabled="!productId || qty < 1 || loading"
                class="px-5 py-2.5 bg-[#f05a2a] hover:bg-[#c8451a] text-white text-sm font-semibold rounded-xl transition-colors disabled:opacity-40 flex items-center gap-2">
                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Jual Sekarang
            </button>
        </div>
        <div x-show="message" x-transition class="mt-3 px-4 py-2.5 rounded-xl text-sm font-medium"
            :class="success ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200'"
            x-text="message"></div>
    </div>

    {{-- ── Product table ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-700">Daftar Produk</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.products.history') }}" class="px-3 py-2 text-xs text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50">Riwayat Penjualan</a>
                <a href="{{ route('admin.products.create') }}" class="px-3 py-2 bg-[#f05a2a] text-white text-xs font-semibold rounded-xl hover:bg-[#c8451a]">+ Tambah Produk</a>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Produk</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Kategori</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Harga</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Stok</th>
                    <th class="text-left px-5 py-3 text-gray-400 font-semibold">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $p)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                            {{ ucfirst($p->category) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-700">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="{{ $p->stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                            {{ $p->stock }} {{ $p->unit }}
                            @if($p->stock <= 5 && $p->stock > 0) <span class="text-xs text-red-400">(hampir habis)</span> @endif
                            @if($p->stock === 0) <span class="text-xs text-red-400 font-semibold">HABIS</span> @endif
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.products.edit', $p) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Hapus produk?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-gray-100">{{ $products->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
function quickSell() {
    return {
        productId: '',
        qty: 1,
        paymentMethod: 'cash',
        loading: false,
        message: '',
        success: false,
        async sell() {
            if (!this.productId || this.qty < 1 || this.loading) return;
            this.loading = true; 
            this.message = '';
            try {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.content : '{{ csrf_token() }}';

                const res = await fetch('{{ route("admin.products.sell") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ 
                        _token: csrfToken,
                        product_id: parseInt(this.productId), 
                        qty: parseInt(this.qty), 
                        payment_method: this.paymentMethod 
                    }),
                });

                let json;
                try {
                    json = await res.json();
                } catch(e) {
                    json = { success: false, message: 'Respon dari server tidak valid (HTTP ' + res.status + ').' };
                }

                this.success = json.success; 
                this.message = json.message || (json.errors ? Object.values(json.errors).flat().join(', ') : 'Terjadi kesalahan saat memproses.');
                if (res.ok && json.success) { 
                    this.productId = ''; 
                    this.qty = 1; 
                    setTimeout(() => location.reload(), 1500); 
                }
            } catch (err) {
                this.success = false;
                this.message = err.message || 'Gagal memproses transaksi penjualan.';
            } finally {
                this.loading = false;
            }
        },
    };
}
</script>
@endpush
@endsection
