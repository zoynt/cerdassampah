@extends('layouts.dashboard')

@section('title', 'Daftar Produk')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $store->name }}</h1>

        <div class="bg-white rounded-2xl shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-gray-200 gap-4">
                <h2 class="text-xl font-bold text-gray-800">Daftar produk saya</h2>
                
                <a href="{{ route('marketplace.products.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    <span>Tambah Produk Baru</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-600">
                        <tr>
                            <th scope="col" class="px-6 py-4">Produk</th>
                            <th scope="col" class="px-6 py-4">Kategori</th>
                            <th scope="col" class="px-6 py-4">Harga</th>
                            <th scope="col" class="px-6 py-4">Stok</th>
                            <th scope="col" class="px-6 py-4">Satuan</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $product->name }}</td>
                                <td class="px-6 py-4">{{ $product->category->name ?? 'Tanpa Kategori' }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ (float)$product->stock }}</td>
                                <td class="px-6 py-4">{{ $product->selling_unit }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('marketplace.products.edit', $product->id) }}" class="px-4 py-1.5 bg-amber-400 text-amber-900 font-semibold rounded-full hover:bg-amber-500 text-xs">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-6 text-gray-500">
                                    Anda belum memiliki produk. Silakan klik "Tambah Produk Baru".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection