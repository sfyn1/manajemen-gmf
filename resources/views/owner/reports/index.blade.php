@extends('layouts.owner')

@section('title', 'Laporan Lanjutan — Gintung Master Fitness')
@section('page-title', 'Laporan Operasional & Keuangan')
@section('page-subtitle', 'Filter dan unduh data audit bisnis SIM GMF')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Filter Panel --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
        <h3 class="font-bold text-slate-900 font-display text-base mb-5 border-b border-slate-100 pb-3">Filter Parameter Laporan</h3>
        <form method="GET" action="{{ route('owner.reports.index') }}" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Periode Awal</label>
                <input type="date" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:border-[#f05a2a]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Periode Akhir</label>
                <input type="date" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:border-[#f05a2a]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kategori Data Laporan</label>
                <select name="report_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:border-[#f05a2a]">
                    <option value="member" {{ request('report_type') === 'member' ? 'selected' : '' }}>Laporan Member Baru</option>
                    <option value="produk" {{ request('report_type') === 'produk' ? 'selected' : '' }}>Laporan Penjualan Produk</option>
                    <option value="kunjungan" {{ request('report_type') === 'kunjungan' ? 'selected' : '' }}>Laporan Kunjungan Gym</option>
                    <option value="payroll" {{ request('report_type') === 'payroll' ? 'selected' : '' }}>Laporan Rekapitulasi Payroll</option>
                </select>
            </div>
            <button type="submit" class="w-full py-3 bg-[#f05a2a] hover:bg-[#e13b12] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-[#f05a2a]/20">Tampilkan Preview</button>
            <a href="{{ route('owner.reports.download', request()->query()) }}" target="_blank"
                class="block text-center py-2.5 border border-slate-800 text-slate-800 text-xs font-bold rounded-xl hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh Dokumen Laporan</span>
            </a>
        </form>
    </div>

    {{-- Data Table Preview --}}
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 font-display text-base">
                    Preview Data: {{ ucfirst(request('report_type', 'member')) }}
                </h3>
                <p class="text-xs text-slate-500 font-medium">Periode: {{ request('date_from', now()->startOfMonth()->format('d M Y')) }} s/d {{ request('date_to', now()->format('d M Y')) }}</p>
            </div>
            <div class="overflow-x-auto scrollbar-thin">
                @if(!empty($data) && count($data) > 0)
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider border-b border-slate-100 font-bold">
                        <tr>
                            @foreach(array_keys($data->first() ?? []) as $col)
                            <th class="px-5 py-3.5 capitalize">{{ str_replace('_', ' ', $col) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($data as $row)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            @foreach($row as $val)
                            <td class="px-5 py-3 text-slate-700 text-xs">{{ $val }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-12 text-center text-slate-400">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="font-bold text-slate-800 text-sm">Preview Belum Memuat Data</p>
                    <p class="text-xs text-slate-500 mt-1">Pilih filter parameter di sebelah kiri lalu klik Tampilkan Preview.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
