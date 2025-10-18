@extends('layouts.dashboard')

@section('title', 'Item Bank Sampah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
    <div x-data="marketplaceData()">
        <div class="space-y-6">
            {{-- Header Halaman --}}
            <div class="relative h-64 rounded-xl overflow-hidden shadow-lg">
                <img src="{{ $bank->image_path ? asset('storage/' . $bank->image_path) : asset('img/placeholder.png') }}"
                     alt="Foto Toko {{ $bank->bank_name }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="relative h-full flex flex-col items-center justify-center text-center text-white p-4">
                    <h1 class="text-3xl md:text-4xl font-bold">{{ $bank->bank_name }}</h1>
                    <div class="flex flex-col md:flex-row items-center justify-center mt-2 space-y-2 md:space-y-0 md:space-x-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Buka {{ $bank->opening_hour }} - {{ $bank->closing_hour }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <span>{{ $bank->address }}</span>
                        </div>
                    </div>
                    {{-- Tombol untuk pindah ke halaman info detail --}}
                    <a href="{{ route('bank-sampah.profil.show', $bank->slug) }}"
                       class="mt-4 px-4 py-2 bg-white bg-opacity-20 text-white font-semibold text-sm rounded-lg backdrop-blur-sm hover:bg-opacity-30 transition-colors">
                        Lihat Info Bank Sampah
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg md:text-xl font-semibold text-gray-700 mb-4">Item Sampah yang Diterima</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" x-model.debounce.300ms="searchQuery" placeholder="Cari nama item..."
                               class="block w-full pl-10 pr-4 py-2.5 text-sm ... rounded-lg">
                    </div>
                    <div class="relative mt-1">
                        <select x-model="selectedCategory"
                                class="appearance-none mt-1 block w-full pl-3 pr-10 py-2.5 text-sm ... rounded-lg">
                            <option value="">Semua Kategori</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-700">
                            <svg class="h-5 w-5 text-gray-400" ...><path ... /></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Item --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <template x-for="product in displayProducts" :key="product.id">
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm flex flex-col">
                        <div class="h-36 overflow-hidden">
                            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <span class="text-xs font-semibold text-green-600" x-text="product.category_name"></span>
                            <h3 class="font-bold text-gray-800 text-md" x-text="product.name"></h3>
                            <p class="font-black text-gray-800 text-lg mt-auto pt-2"
                               x-text="`Rp ${parseInt(product.price).toLocaleString('id-ID')}` + ' /kg'">
                            </p>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="visibleItemsCount < filteredProducts.length" class="mt-8 text-center">
                <button @click="visibleItemsCount += itemsPerLoad" class="px-6 py-3 bg-green-700 ...">
                    Tampilkan Lebih Banyak
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function marketplaceData() {
            return {
                searchQuery: '',
                selectedCategory: '',
                itemsPerLoad: 8,
                visibleItemsCount: 8,
                products: @json($products),
                categories: @json($categories),
                get filteredProducts() {
                    const searchMatch = (p) => p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const categoryMatch = (p) => !this.selectedCategory || p.category_id == this.selectedCategory;
                    return this.products.filter(p => searchMatch(p) && categoryMatch(p));
                },
                get displayProducts() {
                    return this.filteredProducts.slice(0, this.visibleItemsCount);
                }
            }
        }
    </Ganti>
@endpush