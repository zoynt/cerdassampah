@extends('layouts.dashboard')

@section('title', 'Data Penjualan')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Data Penjualan</h1>

        {{-- ======================================================= --}}
        {{-- BAGIAN RINGKASAN (RE-DESIGNED) --}}
        {{-- ======================================================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kartu Produk --}}
            <div class="bg-green-500 text-white p-6 rounded-2xl shadow-md flex items-center gap-6">
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-100">Total Produk</p>
                    <p class="text-4xl font-bold">{{ $totalProduk }}</p>
                </div>
            </div>
            {{-- Kartu Penjualan --}}
            <div class="bg-amber-400 text-amber-900 p-6 rounded-2xl shadow-md flex items-center gap-6">
                <div class="bg-white/40 p-4 rounded-xl">
                     <svg class="w-8 h-8 text-amber-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 1-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold">Total Penjualan</p>
                    <p class="text-4xl font-bold">{{ $totalPenjualan }}</p>
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Filter Kategori</h3>
            <form action="{{ route('marketplace.penjualan') }}" method="GET">
                <select name="kategori" onchange="this.form.submit()"
                    class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori }}" @selected(request('kategori') == $kategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- DAFTAR PRODUK --}}
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">Daftar Produk</h2>
                <a href="{{ route('products.create') }}" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    <span>Produk</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-600">
                        <tr>
                            <th scope="col" class="px-6 py-4">Produk</th>
                            <th scope="col" class="px-6 py-4">Kategori</th>
                            <th scope="col" class="px-6 py-4">Harga</th>
                            <th scope="col" class="px-6 py-4">Terjual</th>
                            <th scope="col" class="px-6 py-4">Bobot per Produk</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produks as $produk)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $produk->nama }}</td>
                                <td class="px-6 py-4">{{ $produk->kategori }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $produk->terjual }}</td>
                                <td class="px-6 py-4">{{ $produk->satuan_berat }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('products.edit', $produk->id) }}" class="px-4 py-1.5 bg-amber-400 text-amber-900 font-semibold rounded-full hover:bg-amber-500 text-xs">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-6 text-gray-500">Tidak ada produk ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $produks->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
