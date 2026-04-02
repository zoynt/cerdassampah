@extends('layouts.dashboard')

@section('title', 'Daftar Produk')

@section('content')
    <div class="space-y-6">
        {{ Breadcrumbs::render() }}
        <h1 class="text-xl md:text-3xl font-bold text-gray-800">{{ $store->name }}</h1>

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
                            <th scope="col" class="px-6 py-4 text-center">Stok</th>
                            <th scope="col" class="px-6 py-4 text-center">Status</th> {{-- [TAMBAHAN] Kolom Status --}}
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $product->name }}</td>
                                <td class="px-6 py-4">{{ $product->category->name ?? 'Tanpa Kategori' }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">{{ (float)$product->stock }} {{ $product->selling_unit }}</td>
                                
                                {{-- [PERUBAHAN] Kolom Status dengan Ikon dan Tooltip --}}
                                <td class="px-6 py-4">
                                    <div x-data="{ tooltip: false }" class="relative flex justify-center">
                                        <div @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                            @class([
                                                'flex items-center justify-center w-8 h-8 rounded-full',
                                                'bg-green-100 text-green-800' => $product->status == 'available',
                                                'bg-red-100 text-red-800' => $product->status == 'sold',
                                                'bg-yellow-100 text-gray-800' => $product->status == 'draft',
                                            ])>
                                            @switch($product->status)
                                                @case('available')
                                                    {{-- Ikon Tersedia (Centang) --}}
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                    @break
                                                @case('sold')
                                                    {{-- Ikon Habis (X) --}}
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    @break
                                                @case('draft')
                                                    {{-- Ikon Diarsipkan (Archive Box) --}}
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                                    @break
                                            @endswitch
                                        </div>
                                        <div x-show="tooltip" x-transition class="absolute -top-8 z-10 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap capitalize">
                                            @if ($product->status == 'available')
                                                Tersedia
                                            @elseif ($product->status == 'sold')
                                                Habis
                                            @elseif ($product->status == 'draft')
                                                Diarsipkan
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($product->status == 'sold')
                                            <a href="{{ route('marketplace.products.edit', ['product_slug' => Str::slug($product->name) . '-' . $product->id]) }}" 
                                               class="w-full sm:w-auto px-3 py-1.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-xs flex items-center justify-center gap-2 transition-colors">
                                               <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                               <span>Tambah Stok</span>
                                            </a>
                                        @else
                                            <a href="{{ route('marketplace.products.show', ['store' => $store->slug, 'product_slug' => Str::slug($product->name) . '-' . $product->id]) }}" 
                                               class="p-2 text-gray-500 rounded-full hover:bg-gray-200 hover:text-blue-700 transition-colors" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </a>
                                            <a href="{{ route('marketplace.products.edit', ['product_slug' => Str::slug($product->name) . '-' . $product->id]) }}" 
                                               class="p-2 text-gray-500 rounded-full hover:bg-gray-200 hover:text-amber-700 transition-colors" title="Edit Produk">
                                               <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-6 text-gray-500">
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