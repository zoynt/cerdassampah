@extends('layouts.dashboard')

@section('title', 'Marketplace')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    <div x-data="marketplace()" x-init="$watch('searchQuery', () => { visibleItemsCount = itemsPerLoad });
    $watch('selectedCategory', () => { visibleItemsCount = itemsPerLoad });">
        <div class="space-y-6">
            {{-- Bagian Filter --}}
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg md:text-xl font-semibold text-gray-700 mb-1">Produk</h2>
                <p class="text-sm text-gray-500 mb-4">Cari produk daur ulang yang Anda butuhkan.</p>
                <div class="flex items-center gap-4">
                    {{-- Tombol Filter --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex-shrink-0 px-4 py-4 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="12" viewBox="0 0 19 12"
                                fill="none">
                                <path
                                    d="M0.90625 0.792969C0.90625 0.585768 0.98856 0.387054 1.13507 0.240541C1.28159 0.0940287 1.4803 0.0117188 1.6875 0.0117188H17.3125C17.5197 0.0117188 17.7184 0.0940287 17.8649 0.240541C18.0114 0.387054 18.0937 0.585768 18.0937 0.792969C18.0937 1.00017 18.0114 1.19888 17.8649 1.3454C17.7184 1.49191 17.5197 1.57422 17.3125 1.57422H1.6875C1.4803 1.57422 1.28159 1.49191 1.13507 1.3454C0.98856 1.19888 0.90625 1.00017 0.90625 0.792969ZM3.51042 6.0013C3.51042 5.7941 3.59273 5.59539 3.73924 5.44888C3.88575 5.30236 4.08447 5.22005 4.29167 5.22005H14.7083C14.9155 5.22005 15.1142 5.30236 15.2608 5.44888C15.4073 5.59539 15.4896 5.7941 15.4896 6.0013C15.4896 6.2085 15.4073 6.40722 15.2608 6.55373C15.1142 6.70024 14.9155 6.78255 14.7083 6.78255H4.29167C4.08447 6.78255 3.88575 6.70024 3.73924 6.55373C3.59273 6.40722 3.51042 6.2085 3.51042 6.0013ZM6.63542 11.2096C6.63542 11.0024 6.71773 10.8037 6.86424 10.6572C7.01075 10.5107 7.20947 10.4284 7.41667 10.4284H11.5833C11.7905 10.4284 11.9892 10.5107 12.1358 10.6572C12.2823 10.8037 12.3646 11.0024 12.3646 11.2096C12.3646 11.4168 12.2823 11.6156 12.1358 11.7621C11.9892 11.9086 11.7905 11.9909 11.5833 11.9909H7.41667C7.20947 11.9909 7.01075 11.9086 6.86424 11.7621C6.71773 11.6156 6.63542 11.4168 6.63542 11.2096Z"
                                    fill="#62748E" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-2 w-72 origin-top-left bg-white border border-gray-200 rounded-lg shadow-lg"
                            style="display: none;">
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-800 mb-2 text-sm">Paling Sering Dicari</h4>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="tag in quickFilters" :key="tag">
                                        <button @click="searchQuery = tag; open = false"
                                            class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded-full hover:bg-green-600 hover:text-white">
                                            <span x-text="tag"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" x-model.debounce.300ms="searchQuery"
                            placeholder="Cari berdasarkan nama produk..."
                            class="block w-full pl-10 pr-4 py-2.5 text-sm md:text-base text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg md:text-xl font-semibold text-gray-700 mb-4">Kategori Pilihan</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button @click="selectedCategory = ''"
                        :class="{ 'bg-green-700 text-white shadow-lg scale-105': selectedCategory === '', 'bg-gray-100 text-gray-600 hover:bg-gray-200': selectedCategory !== '' }"
                        class="p-4 rounded-lg flex flex-col items-center justify-center text-center transition-transform transform duration-200">
                        <svg class="w-8 h-8 md:w-10 md:h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="font-semibold text-xs md:text-sm">Semua</span>
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button @click="selectedCategory = category.id"
                            :class="{
                                'bg-green-700 text-white shadow-lg scale-105': selectedCategory === category
                                    .id,
                                'bg-gray-100 text-gray-600 hover:bg-gray-200': selectedCategory !== category.id
                            }"
                            class="p-4 rounded-lg flex flex-col items-center justify-center text-center transition-transform transform duration-200">
                            <div x-html="category.icon" class="w-8 h-8 md:w-10 md:h-10 mb-2"></div>
                            <span class="font-semibold text-xs md:text-sm" x-text="category.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Produk</h2>
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5">
                    <template x-for="product in displayProducts" :key="product.id">
                        <a :href="`/marketplace/product/${product.id}`" class="block group">
                            <div
                                class="bg-white border border-gray-100 rounded-xl overflow-hidden transform group-hover:-translate-y-1 transition-all duration-300 shadow-sm group-hover:shadow-2xl flex flex-col h-full">
                                <div class="bg-green-100 w-full h-32 md:h-36 flex items-center justify-center">
                                    <div x-html="categories.find(c => c.id === product.category).icon"
                                        class="w-16 h-16 md:w-20 md:h-20 text-green-400"></div>
                                </div>
                                <div class="p-3 md:p-4 flex flex-col flex-grow">
                                    <h3 class="font-bold text-gray-800 text-sm md:text-md" x-text="product.name"></h3>
                                    <p class="text-xs text-gray-500">1.35 Km</p>
                                    <div class="my-2 space-y-1 text-xs text-gray-600">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div class="flex items-center"><svg class="w-4 h-4 text-yellow-400 mr-1"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg><span x-text="product.rating.toFixed(1)"></span></div>
                                            <div class="flex items-center"><svg class="w-4 h-4 text-green-700 mr-1"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg><span x-text="`${product.sold} Terjual`"></span></div>
                                        </div>
                                        <div class="flex items-center pt-1"><svg class="w-4 h-4 text-gray-400 mr-1.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M1 10c.41.29.96.43 1.5.43c.55 0 1.09-.14 1.5-.43c.62-.46 1-1.17 1-2c0 .83.37 1.54 1 2c.41.29.96.43 1.5.43c.55 0 1.09-.14 1.5-.43c.62-.46 1-1.17 1-2c0 .83.37 1.54 1 2c.41.29.96.43 1.51.43c.54 0 1.08-.14 1.49-.43c.62-.46 1-1.17 1-2c0 .83.37 1.54 1 2c.41.29.96.43 1.5.43c.55 0 1.09-.14 1.5-.43c.63-.46 1-1.17 1-2V7l-3-7H4L0 7v1c0 .83.37 1.54 1 2zm2 8.99h5v-5h4v5h5v-7c-.37-.05-.72-.22-1-.43c-.63-.45-1-.73-1-1.56c0 .83-.38 1.11-1 1.56c-.41.3-.95.43-1.49.44c-.55 0-1.1-.14-1.51-.44c-.63-.45-1-.73-1-1.56c0 .83-.38 1.11-1 1.56c-.41.3-.95.43-1.5.44c-.54 0-1.09-.14-1.5-.44c-.63-.45-1-.73-1-1.57c0 .84-.38 1.12-1 1.57c-.29.21-.63.38-1 .44v6.99z" />
                                            </svg><span x-text="product.store"></span></div>
                                    </div>
                                    <p class="font-black text-gray-800 text-lg md:text-xl mt-auto"
                                        x-text="`Rp ${product.price.toLocaleString('id-ID')}`"></p>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
                <template x-if="filteredProducts.length === 0">
                    <div class="text-center py-10 px-6 bg-gray-50 rounded-lg mt-5"><svg
                            class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <p class="text-gray-600 font-semibold">Produk tidak ditemukan</p>
                        <p class="text-sm text-gray-500">Coba kata kunci atau kategori lain.</p>
                    </div>
                </template>
                <div x-show="visibleItemsCount < filteredProducts.length" class="mt-8 text-center">
                    <button @click="visibleItemsCount += itemsPerLoad"
                        class="px-6 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-75 transition-colors">Tampilkan
                        Lebih Banyak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function marketplace() {
            return {
                searchQuery: '',
                selectedCategory: '',
                itemsPerLoad: 8,
                visibleItemsCount: 8,
                quickFilters: ['Plastik', 'Botol', 'Kardus', 'Logam', 'Besi', 'Koran'],
                categories: [{
                        id: 'kertas',
                        name: 'Kertas & Kardus',
                        icon: `<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>`
                    },
                    {
                        id: 'plastik',
                        name: 'Plastik',
                        icon: `<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6"></path></svg>`
                    },
                    {
                        id: 'logam',
                        name: 'Logam',
                        icon: `<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.63 8.37l-5.63-5.63a2.98 2.98 0 00-4.24 0L3.37 10.13a2.98 2.98 0 000 4.24l5.63 5.63a2.98 2.98 0 004.24 0l7.39-7.39a2.98 2.98 0 000-4.24zM10 14l-4-4m4 4l-2-6"></path></svg>`
                    }
                ],

                // [PERBAIKAN] Data produk dikembalikan ke versi lengkap (semua toko)
                products: [{
                        id: 1,
                        name: 'Kardus Tebal',
                        category: 'kertas',
                        price: 2000,
                        rating: 4.8,
                        sold: 152,
                        store: 'Toko Daur Ulang'
                    },
                    {
                        id: 2,
                        name: 'Kertas HVS Bekas',
                        category: 'kertas',
                        price: 1500,
                        rating: 4.9,
                        sold: 210,
                        store: 'Hijau Market'
                    },
                    {
                        id: 3,
                        name: 'Koran Lama',
                        category: 'kertas',
                        price: 1000,
                        rating: 4.7,
                        sold: 88,
                        store: 'Kreasi Bekas'
                    },
                    {
                        id: 4,
                        name: 'Buku Tulis Bekas',
                        category: 'kertas',
                        price: 1200,
                        rating: 5.0,
                        sold: 50,
                        store: 'Toko Daur Ulang'
                    },
                    {
                        id: 5,
                        name: 'Majalah',
                        category: 'kertas',
                        price: 1300,
                        rating: 4.6,
                        sold: 75,
                        store: 'Hijau Market'
                    },
                    {
                        id: 6,
                        name: 'Botol Air Mineral',
                        category: 'plastik',
                        price: 2500,
                        rating: 4.9,
                        sold: 500,
                        store: 'Hijau Market'
                    },
                    {
                        id: 7,
                        name: 'Gelas Plastik',
                        category: 'plastik',
                        price: 1000,
                        rating: 4.8,
                        sold: 320,
                        store: 'Kreasi Bekas'
                    },
                    {
                        id: 8,
                        name: 'Jerigen',
                        category: 'plastik',
                        price: 5000,
                        rating: 5.0,
                        sold: 95,
                        store: 'Toko Daur Ulang'
                    },
                    {
                        id: 9,
                        name: 'Ember Pecah',
                        category: 'plastik',
                        price: 3000,
                        rating: 4.5,
                        sold: 40,
                        store: 'Kreasi Bekas'
                    },
                    {
                        id: 10,
                        name: 'Tutup Botol',
                        category: 'plastik',
                        price: 500,
                        rating: 4.7,
                        sold: 500,
                        store: 'Hijau Market'
                    },
                    {
                        id: 11,
                        name: 'Kaleng Cat',
                        category: 'logam',
                        price: 3000,
                        rating: 5.0,
                        sold: 100,
                        store: 'smartcity.id'
                    },
                    {
                        id: 12,
                        name: 'Kaleng Sarden',
                        category: 'logam',
                        price: 2500,
                        rating: 4.8,
                        sold: 180,
                        store: 'Kreasi Bekas'
                    },
                    {
                        id: 13,
                        name: 'Seng Bekas',
                        category: 'logam',
                        price: 7000,
                        rating: 4.9,
                        sold: 60,
                        store: 'Toko Daur Ulang'
                    },
                    {
                        id: 14,
                        name: 'Paku Karatan',
                        category: 'logam',
                        price: 1000,
                        rating: 4.5,
                        sold: 30,
                        store: 'Hijau Market'
                    },
                    {
                        id: 15,
                        name: 'Tutup Panci',
                        category: 'logam',
                        price: 4000,
                        rating: 4.7,
                        sold: 85,
                        store: 'Toko Daur Ulang'
                    },
                    {
                        id: 16,
                        name: 'Karton Box',
                        category: 'kertas',
                        price: 2200,
                        rating: 4.8,
                        sold: 112,
                        store: 'Hijau Market'
                    },
                    {
                        id: 17,
                        name: 'Kresek Bening',
                        category: 'plastik',
                        price: 800,
                        rating: 4.6,
                        sold: 450,
                        store: 'Kreasi Bekas'
                    },
                ],

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
