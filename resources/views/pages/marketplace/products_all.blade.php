@extends('layouts.dashboard')

@section('title', 'Marketplace')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* [MODIFIKASI] Menetapkan rasio aspek agar tinggi konsisten */
        .mySwiper {
            aspect-ratio: 16 / 8;
            /* Rasio untuk layar lebar */
        }

        @media (min-width: 1024px) {
            .mySwiper {
                aspect-ratio: 22 / 8;
                /* Rasio lebih lebar untuk desktop besar */
                max-height: 400px;
                /* Batas tinggi maksimum */
            }
        }

        .swiper-button-next,
        .swiper-button-prev {
            background-color: rgba(255, 255, 255, 0.7);
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            color: #166534;
            transition: background-color 0.2s, opacity 0.2s;
            opacity: 0;
        }

        .mySwiper:hover .swiper-button-next,
        .mySwiper:hover .swiper-button-prev {
            opacity: 1;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div x-data="marketplace()">
        <div class="space-y-6">
            <div class="swiper mySwiper rounded-xl shadow-md overflow-hidden relative z-0 w-full">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="relative bg-green-200 p-8 h-full">
                            <div class="absolute -bottom-50 right-80 opacity-50 text-white pointer-events-none ">
                                <svg class="w-[350px] h-[497px]" viewBox="0 0 400 461" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M181.25 0C175.107 1.37772 169.131 3.41431 163.425 6.07439C63.825 52.6197 0 182.782 0 294.295C0 400.285 79.575 487.376 181.25 497V0ZM218.75 497C320.425 487.376 400 400.31 400 294.32C400 284.155 399.475 273.872 398.425 263.474L218.75 443.155V497ZM356.5 127.412C346.908 109.43 335.695 92.3612 323 76.4173L218.75 180.657V265.148L356.5 127.412ZM297.7 48.6701C279.776 31.2442 259.13 16.8569 236.575 6.07439C230.869 3.41431 224.893 1.37772 218.75 0V127.637L297.7 48.6701ZM373.4 163.509L218.75 318.168V390.111L387.5 221.378L390.8 218.078C386.4 199.471 380.583 181.228 373.4 163.509Z" />
                                </svg>
                            </div>
                            <div class="absolute -right-20 -bottom-20 opacity-50 text-white pointer-events-none">
                                <svg class="w-[400px] h-[461px]" viewBox="0 0 400 461" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M181.25 0C175.107 1.37772 169.131 3.41431 163.425 6.07439C63.825 52.6197 0 182.782 0 294.295C0 400.285 79.575 487.376 181.25 497V0ZM218.75 497C320.425 487.376 400 400.31 400 294.32C400 284.155 399.475 273.872 398.425 263.474L218.75 443.155V497ZM356.5 127.412C346.908 109.43 335.695 92.3612 323 76.4173L218.75 180.657V265.148L356.5 127.412ZM297.7 48.6701C279.776 31.2442 259.13 16.8569 236.575 6.07439C230.869 3.41431 224.893 1.37772 218.75 0V127.637L297.7 48.6701ZM373.4 163.509L218.75 318.168V390.111L387.5 221.378L390.8 218.078C386.4 199.471 380.583 181.228 373.4 163.509Z" />
                                </svg>
                            </div>
                            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 items-center h-full">
                                <div class="text-center lg:text-left">

                                    <a href="{{ route('mystore.dashboard') }}"
                                        class="hidden sm:inline-block bg-green-700 text-white px-3 py-1 rounded-md text-xs sm:text-sm md:text-2xl lg:text-2xl font-semibold">
                                        Mau Jual Sampah Terpilahmu?
                                    </a>

                                    <h2 class="text-lg sm:text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                                        Saatnya buka toko dan jadi penjual di Cerdas Sampah!
                                    </h2>

                                    <p class="hidden md:block mt-1 text-gray-600">
                                        Jual sampah terpilahmu dengan mudah, dapatkan keuntungan, dan ikut serta menciptakan
                                        lingkungan yang lebih bersih.
                                    </p>

                                    <a href="{{ route('mystore.dashboard') }}"
                                        class="inline-block mt-6 bg-white text-gray-800 font-bold py-2 px-4 text-xs sm:py-3 sm:px-6 sm:text-base rounded-lg shadow-md hover:bg-gray-100  transition-colors duration-200">
                                        Buka tokomu sekarang!
                                    </a>


                                </div>
                                <div class="hidden lg:flex justify-center items-center">
                                    <div
                                        class="w-48 h-48 lg:w-60 lg:h-60 bg-white rounded-full flex items-center justify-center shadow-md">
                                        <svg class="w-32 h-32 lg:w-32 lg:h-32" viewBox="0 0 125 150" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M30.4077 32.1429C30.4077 23.618 33.7888 15.4424 39.8073 9.41442C45.8258 3.38647 53.9886 0 62.5 0C71.0114 0 79.1742 3.38647 85.1927 9.41442C91.2112 15.4424 94.5923 23.618 94.5923 32.1429V42.8571H101.471C105.448 42.8584 109.283 44.3389 112.232 47.0114C115.181 49.6839 117.034 53.3581 117.431 57.3214L124.92 132.321C125.144 134.557 124.898 136.814 124.197 138.948C123.497 141.083 122.357 143.046 120.852 144.712C119.347 146.377 117.51 147.709 115.46 148.62C113.41 149.531 111.191 150.001 108.948 150H16.041C13.7989 149.999 11.5818 149.528 9.53276 148.616C7.48368 147.705 5.64807 146.373 4.14428 144.708C2.6405 143.042 1.50193 141.079 0.801974 138.946C0.10202 136.812 -0.143777 134.556 0.0804338 132.321L7.56864 57.3214C7.96634 53.3581 9.81937 49.6839 12.7684 47.0114C15.7174 44.3389 19.5522 42.8584 23.5292 42.8571H30.4077V32.1429ZM78.5462 32.1429V42.8571H46.4538V32.1429C46.4538 27.8804 48.1444 23.7926 51.1536 20.7786C54.1629 17.7647 58.2443 16.0714 62.5 16.0714C66.7557 16.0714 70.8371 17.7647 73.8464 20.7786C76.8556 23.7926 78.5462 27.8804 78.5462 32.1429ZM46.4538 72.3214C46.4538 70.1902 45.6086 68.1463 44.1039 66.6393C42.5993 65.1323 40.5586 64.2857 38.4308 64.2857C36.3029 64.2857 34.2622 65.1323 32.7576 66.6393C31.253 68.1463 30.4077 70.1902 30.4077 72.3214V83.0357C30.4077 91.5605 33.7888 99.7362 39.8073 105.764C45.8258 111.792 53.9886 115.179 62.5 115.179C71.0114 115.179 79.1742 111.792 85.1927 105.764C91.2112 99.7362 94.5923 91.5605 94.5923 83.0357V72.3214C94.5923 70.1902 93.747 68.1463 92.2424 66.6393C90.7378 65.1323 88.6971 64.2857 86.5692 64.2857C84.4414 64.2857 82.4007 65.1323 80.8961 66.6393C79.3915 68.1463 78.5462 70.1902 78.5462 72.3214V83.0357C78.5462 87.2981 76.8556 91.386 73.8464 94.3999C70.8371 97.4139 66.7557 99.1071 62.5 99.1071C58.2443 99.1071 54.1629 97.4139 51.1536 94.3999C48.1444 91.386 46.4538 87.2981 46.4538 83.0357V72.3214Z"
                                                fill="#006045" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide rounded-2xl overflow-hidden">
                        <a href="#" class="block h-full w-full"><img
                                src="https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop"
                                alt="Iklan Daur Ulang Plastik" class="h-full w-full object-cover"></a>
                    </div>

                    <div class="swiper-slide rounded-2xl overflow-hidden">
                        <a href="#" class="block h-full w-full"><img
                                src="https://unsplash.com/photos/a-store-front-with-a-hello-kitty-sign-lit-up-at-night-7bb6xLTzOCM"
                                alt="Iklan Jual Beli Kertas Bekas" class="h-full w-full object-cover"></a>
                    </div>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>

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
                    {{-- Tombol Semua --}}
                    <button @click="selectedCategory = ''"
                        :class="{ 'bg-green-700 text-white shadow-lg scale-105': selectedCategory === '', 'bg-gray-100 text-gray-600 hover:bg-gray-200': selectedCategory !== '' }"
                        class="p-4 rounded-lg flex flex-col items-center justify-center text-center transition-transform transform duration-200">
                        <svg class="w-8 h-8 md:w-10 md:h-10 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="font-semibold text-xs md:text-sm">Semua</span>
                    </button>

                    {{-- Tombol Kategori Lainnya --}}
                    <template x-for="category in categories" :key="category.id">
                        <button @click="selectedCategory = category.id" {{-- [PERBAIKAN] Kondisi :class harus membandingkan ID dengan ID --}}
                            :class="{ 'bg-green-700 text-white shadow-lg scale-105': selectedCategory === category
                                .id, 'bg-gray-100 text-gray-600 hover:bg-gray-200': selectedCategory !== category.id }"
                            class="p-4 rounded-lg flex flex-col items-center justify-center text-center transition-transform transform duration-200">
                            <div x-html="category.icon" class="w-8 h-8 md:w-10 md:h-10 mb-2"></div>
                            <span class="font-semibold text-xs md:text-sm" x-text="category.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Produk</h2>
                {{-- Grid untuk mengatur jumlah kolom --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-5">
                    <template x-for="product in displayProducts" :key="product.id">
                        <a :href="`/marketplace/product/${product.id}`" class="block group">

                            <div
                                class="bg-white border border-gray-100 rounded-xl overflow-hidden transform group-hover:-translate-y-1 transition-all duration-300 shadow-sm group-hover:shadow-2xl flex flex-row md:flex-col h-full">

                                <div class="w-1/2 md:w-full h-full md:h-32 lg:h-36 overflow-hidden">
                                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                                </div>

                                <div class="p-3 md:p-4 flex flex-col flex-grow w-1/2 md:w-full">
                                    <h3 class="font-bold text-gray-800 text-sm md:text-md" x-text="product.name"></h3>
                                    <p class="text-xs text-gray-500">1.35 Km</p>
                                    <div class="my-2 space-y-1 text-xs text-gray-600">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div class="flex items-center"><svg class="w-4 h-4 text-yellow-400 mr-1"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg><span x-text="product.rating > 0 ? product.rating : 'Belum dinilai'"
                                                    :class="{
                                                        'text-gray-600': product.rating >
                                                            0,
                                                        'text-gray-500 text-xs': product.rating <= 0
                                                    }">
                                                </span></div>
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
                                        x-text="`Rp ${product.price.toLocaleString('id-ID')}` "></p>
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        function marketplace() {
            return {
                searchQuery: '',
                selectedCategory: '',
                itemsPerLoad: 8,
                visibleItemsCount: 8,
                quickFilters: ['Plastik', 'Botol', 'Kardus', 'Logam', 'Besi', 'Koran'],
                products: @json($products),
                categories: @json($categories),

                get filteredProducts() {
                    if (!this.products) return [];
                    return this.products.filter(product => {
                        const searchMatch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        
                        // [PERBAIKAN] Logika filter kategori diubah untuk membandingkan ID
                        const categoryMatch = this.selectedCategory === '' || product.category_id === this.selectedCategory;
                        
                        return searchMatch && categoryMatch;
                    });
                },

                get displayProducts() {
                    return this.filteredProducts.slice(0, this.visibleItemsCount);
                }
            }
        }

        // Inisialisasi Swiper setelah halaman siap
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper(".mySwiper", {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
        });
    </script>
@endpush
