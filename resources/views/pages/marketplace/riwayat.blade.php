@extends('layouts.dashboard')

@section('title', 'Riwayat Penjualan')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Penjualan</h1>

        {{-- FILTER --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Filter</h3>
            <form action="{{ route('marketplace.riwayat') }}" method="GET">
                <select name="kategori" onchange="this.form.submit()"
                    class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori }}" @selected(request('kategori') == $kategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- DAFTAR RIWAYAT PENJUALAN --}}
        <div class="bg-white rounded-2xl shadow-lg">
             <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Penjualan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-600">
                        <tr>
                            <th scope="col" class="px-6 py-4">Pembeli</th>
                            <th scope="col" class="px-6 py-4">Produk</th>
                            <th scope="col" class="px-6 py-4">Kategori</th>
                            <th scope="col" class="px-6 py-4">Harga</th>
                            <th scope="col" class="px-6 py-4">Terjual</th>
                            <th scope="col" class="px-6 py-4">Total</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            {{-- ======================================================= --}}
                            {{-- KOLOM BARU DITAMBAHKAN DI SINI --}}
                            {{-- ======================================================= --}}
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualans as $penjualan)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $penjualan->pembeli }}</td>
                                <td class="px-6 py-4">{{ $penjualan->produk }}</td>
                                <td class="px-6 py-4">{{ $penjualan->kategori }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($penjualan->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $penjualan->terjual }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-green-200 text-green-800 font-medium rounded-full text-xs">
                                        {{ $penjualan->status }}
                                    </span>
                                </td>
                                {{-- ======================================================= --}}
                                {{-- TOMBOL DETAIL DITAMBAHKAN DI SINI --}}
                                {{-- ======================================================= --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('marketplace.penjualan.show', $penjualan->id) }}" class="px-4 py-1.5 bg-blue-100 text-blue-800 font-semibold rounded-full hover:bg-blue-200 text-xs">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Colspan diubah menjadi 8 --}}
                                <td colspan="8" class="text-center p-6 text-gray-500">Tidak ada riwayat penjualan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">
                {{ $penjualans->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
