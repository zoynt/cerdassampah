@extends('layouts.dashboard')

@section('title', 'Halaman Toko')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    <div x-data="marketplace()" x-init="$watch('searchQuery', () => { visibleItemsCount = itemsPerLoad });
    $watch('selectedCategory', () => { visibleItemsCount = itemsPerLoad });">
        <div class="space-y-6">
            <div class="relative h-64 rounded-xl overflow-hidden shadow-lg">
                <img src="{{ $store->image_path ? asset('storage/' . $store->image_path) : asset('img/placeholder.png') }}"
                    alt="Foto Toko {{ $store->name }}" class="absolute inset-0 w-full h-full object-cover">

                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="relative h-full flex flex-col items-center justify-center text-center text-white p-4">
                    <h1 class="text-3xl md:text-4xl font-bold">{{ $store->name }}</h1>
                    <div class="mt-3">
                        @if ($isStoreOpen)
                            <span
                                class="px-4 py-1.5 text-xs font-semibold rounded-full bg-green-500/80 text-white backdrop-blur-sm">
                                Toko Buka
                            </span>
                        @else
                            <span
                                class="px-4 py-1.5 text-xs font-semibold rounded-full bg-red-500/80 text-white backdrop-blur-sm">
                                Toko Tutup
                            </span>
                        @endif
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-center mt-2 space-y-2 md:space-y-0 md:space-x-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            @if ($store->reviews->count() > 0)
                                <span>{{ $storeRating }}</span>
                            @else
                                <span class="font-normal text-gray-300">Belum dinilai</span>
                            @endif
                        </div>

                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                            <span>{{ $totalSold }} Produk Terjual</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $store->address }}</span>
                        </div>
                    </div>
                    <a href="{{ route('store.profile.show', $store) }}"
                        class="mt-4 px-4 py-2 bg-white bg-opacity-20 text-white font-semibold text-sm rounded-lg backdrop-blur-sm hover:bg-opacity-30 transition-colors">
                        Lihat Info Toko
                    </a>
                </div>
            </div>


            {{-- Filter --}}
            <div class="bg-white p-6 rounded-xl shadow-md">
                {{-- [MODIFIKASI] Ukuran font judul filter --}}
                <h2 class="text-lg md:text-xl font-semibold text-gray-700 mb-4">Produk</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Input Pencarian Teks --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari Produk</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            {{-- [MODIFIKASI] Ukuran font input --}}
                            <input type="text" id="search" name="search" x-model.debounce.300ms="searchQuery"
                                placeholder="Cari berdasarkan nama produk..."
                                class="block w-full pl-10 pr-4 py-2.5 text-sm md:text-base text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div>
                        <label for="category-filter" class="block text-sm font-medium text-gray-700">Kategori</label>

                        {{-- [KUNCI 1] Bungkus <select> dengan div.relative --}}
                        <div class="relative mt-1">
                            <select id="category-filter" name="category-filter" x-model="selectedCategory"
                                {{-- [KUNCI 2] Tambahkan `appearance-none` untuk menghilangkan panah default --}}
                                class="appearance-none mt-1 block w-full pl-3 pr-10 py-2.5 text-sm md:text-base bg-gray-50 border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 rounded-lg">
                                <option value="">Semua Kategori</option>
                                <template x-for="category in categories" :key="category.id">
                                    <option :value="category.id" x-text="category.name"></option>
                                </template>
                            </select>

                            {{-- [KUNCI 3] Pindahkan ikon ke sini, sebagai sibling dari <select> --}}
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-700">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Judul Daftar Produk --}}
            <div class="bg-green-700 text-white p-4 rounded-xl shadow-md">
                {{-- [MODIFIKASI] Ukuran font judul daftar produk --}}
                <h2 class="text-lg md:text-xl font-semibold">Produk</h2>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-5">
                <template x-for="product in displayProducts" :key="product.id">
                    <a :href="`/marketplace/product/${product.id}`" class="block group">
                        <div
                            class="bg-white border border-gray-100 rounded-xl overflow-hidden transform group-hover:-translate-y-1 transition-all duration-300 shadow-sm group-hover:shadow-2xl flex flex-row md:flex-col h-full">

                            {{-- Bagian Gambar --}}
                            <div class="w-1/2 md:w-full h-full md:h-36 lg:h-36 overflow-hidden">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                            </div>

                            <div class="p-3 md:p-4 flex flex-col flex-grow w-1/2 md:w-full">
                                <h3 class="font-bold text-gray-800 text-sm md:text-md" x-text="product.name"></h3>
                                <p class="text-xs text-gray-500">1.35 Km</p>
                                <div class="my-2 space-y-1 text-xs text-gray-600">
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <span x-text="product.rating > 0 ? product.rating.toFixed(1) : 'Belum dinilai'"
                                                :class="{
                                                    'text-gray-600': product.rating > 0,
                                                    'text-gray-500 text-xs': product
                                                        .rating <= 0
                                                }">
                                            </span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-green-700 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            <span x-text="`${product.sold} Terjual`"></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center pt-1">
                                        <svg class="w-4 h-4 text-gray-400 mr-1.5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M1 10c.41.29.96.43 1.5.43c.55 0 1.09-.14 1.5-.43c.62-.46 1-1.17 1-2c0 .83.37 1.54 1 2c.41.29.96.43 1.5.43c.55 0 1.09-.14 1.5-.43c.62-.46 1-1.17 1-2c0 .83.37 1.54 1 2c.41.29.96.43 1.51.43c.54 0 1.08-.14 1.49-.43c.62-.46 1-1.17 1-2c0 .83.37 1.54 1 2c.41.29.96.43 1.5.43c.55 0 1.09-.14 1.5-.43c.63-.46 1-1.17 1-2V7l-3-7H4L0 7v1c0 .83.37 1.54 1 2zm2 8.99h5v-5h4v5h5v-7c-.37-.05-.72-.22-1-.43c-.63-.45-1-.73-1-1.56c0 .83-.38 1.11-1 1.56c-.41.3-.95.43-1.49.44c-.55 0-1.1-.14-1.51-.44c-.63-.45-1-.73-1-1.56c0 .83-.38 1.11-1 1.56c-.41.3-.95.43-1.5.44c-.54 0-1.09-.14-1.5-.44c-.63-.45-1-.73-1-1.57c0 .84-.38 1.12-1 1.57c-.29.21-.63.38-1 .44v6.99z" />
                                        </svg><span x-text="product.store"></span>
                                    </div>
                                </div>
                                <p class="font-black text-gray-800 text-lg md:text-xl mt-auto"
                                    x-text="`Rp ${parseInt(product.price).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`">
                                </p>
                            </div>
                        </div>
                    </a>
                </template>
            </div>

            <div x-show="visibleItemsCount < filteredProducts.length" class="mt-8 text-center">
                <button @click="visibleItemsCount += itemsPerLoad"
                    class="px-6 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-75 transition-colors">
                    Tampilkan Lebih Banyak
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function marketplace() {
            return {
                isStoreInfoModalOpen: false,
                searchQuery: '',
                selectedCategory: '',
                itemsPerLoad: 8,
                visibleItemsCount: 8,

                products: @json($products),
                categories: @json($categories),

                get filteredProducts() {
                    const searchMatch = (product) => product.name.toLowerCase().includes(this.searchQuery
                        .toLowerCase());
                    const categoryMatch = (product) => this.selectedCategory === '' || product.category === this
                        .selectedCategory;
                    return this.products.filter(p => searchMatch(p) && categoryMatch(p));
                },
                get displayProducts() {
                    return this.filteredProducts.slice(0, this.visibleItemsCount);
                }
            }
        }
    </script>
@endpush
