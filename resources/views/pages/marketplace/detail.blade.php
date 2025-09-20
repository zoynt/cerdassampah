@extends('layouts.dashboard')

@section('title', 'Detail Produk')
@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    <div class="space-y-6 md:space-y-8" x-data="productPage()">
        {{-- Tombol Kembali --}}
        <div class="flex items-center gap-4">
            <div x-data="{ tooltip: false }" class="relative inline-block">
                <button onclick="window.history.back()" @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                    class="flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <div x-show="tooltip" x-transition
                    class="absolute z-10 top-full mt-2 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap">
                    Kembali
                </div>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Produk</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 items-start">
            {{-- Bagian Galeri Gambar --}}
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-green-50 rounded-xl p-4 flex items-center justify-center aspect-square">
                    <img :src="mainImage" alt="Produk Utama" class="w-full h-full object-contain rounded-xl">
                </div>
                <div class="flex items-center gap-4">
                    <template x-for="(image, index) in images.slice(0, 3)" :key="index">
                        <button @click="mainImage = image"
                            class="w-1/4 aspect-square bg-green-50 rounded-lg p-2 transition-all duration-200"
                            :class="{ 'ring-2 ring-green-600 ring-offset-2': mainImage === image }">
                            <img :src="image" alt="Thumbnail Produk"
                                class="w-full h-full object-contain rounded-lg">
                        </button>
                    </template>
                    <template x-if="images.length > 3">
                        <button @click="isModalOpen = true"
                            class="w-1/4 aspect-square bg-green-100 rounded-lg flex items-center justify-center text-green-700 font-bold text-xl md:text-2xl hover:bg-green-200 transition-colors duration-200">
                            <span x-text="`+${images.length - 3}`"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Bagian Informasi Produk --}}
            <div class="lg:col-span-1 bg-white p-6 md:p-8 rounded-xl shadow-md h-full">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Kaleng Cat</h1>
                <div class="flex items-center text-sm text-gray-500 mt-2">
                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span>5.0</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span>Terjual 500+</span>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-green-700 mt-4 tracking-tight">Rp 3.000</p>
                <hr class="my-6 border-gray-200">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Informasi</h3>
                    <div class="space-y-5 text-sm">
                        <a href="{{ route('marketplace.store') }}"
                            class="flex items-start gap-4 hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors duration-200">
                            <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Toko</p>
                                <p class="font-semibold text-gray-700">Hijau Market, Alex</p>
                            </div>
                        </a>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Lokasi</p>
                                <p class="font-semibold text-gray-700">Kayu Tangi, Banjarmasin</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Berat/Bobot</p>
                                <p class="font-semibold text-gray-700">Kilogram</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-gray-200">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                    <div class="relative max-h-24 overflow-hidden">
                        <div class="prose prose-sm max-w-none text-gray-600">
                            <p>Ini adalah deskripsi produk yang cukup panjang untuk tujuan demonstrasi. Kaleng cat ini
                                dibuat dari bahan berkualitas tinggi yang tahan terhadap karat dan cuaca ekstrem. Sangat
                                cocok untuk penggunaan di luar ruangan maupun di dalam ruangan. Dengan teknologi pengecatan
                                terbaru, warna tidak akan mudah pudar dan akan tetap cerah selama bertahun-tahun. Produk ini
                                juga ramah lingkungan karena menggunakan bahan daur ulang.</p>
                            <p>Spesifikasi tambahan:
                            <ul>
                                <li>Volume: 5 Liter</li>
                                <li>Material: Baja Galvanis</li>
                                <li>Ketebalan: 0.5mm</li>
                                <li>Warna Tersedia: Merah, Biru, Hijau</li>
                            </ul>
                            </p>
                        </div>
                        <div
                            class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent pointer-events-none">
                        </div>
                    </div>
                    <button @click="isDescriptionModalOpen = true"
                        class="mt-2 text-sm font-bold text-green-700 hover:text-green-600">Baca Selengkapnya</button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-bold text-base md:text-lg text-gray-800 mb-4">Atur Jumlah</h3>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 md:w-20 md:h-20 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <img :src="mainImage" alt="Produk Kecil" class="w-full h-full object-contain rounded-lg">
                        </div>
                        <div>
                            <p class="text-2xl md:text-3xl font-bold text-gray-800">Kaleng Cat</p>
                            <div class="flex items-center text-sm text-gray-500 mt-1"><svg class="w-4 h-4 mr-1.5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <p x-text="`Stok ${stock}`"></p>
                            </div>
                            <div class="flex items-center border border-gray-200 rounded-lg mt-2"><button
                                    @click="quantity = Math.max(1, quantity - 1)"
                                    class="px-3 py-1 text-gray-500 hover:text-gray-800 focus:outline-none">-</button><span
                                    x-text="quantity" class="px-4 py-1 font-semibold text-gray-800 text-sm"></span><button
                                    @click="quantity = Math.min(stock, quantity + 1)"
                                    class="px-3 py-1 text-gray-500 hover:text-gray-800 focus:outline-none">+</button></div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Subtotal</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"
                            x-text="`Rp ${(quantity * price).toLocaleString('id-ID')}`"></p>
                    </div>
                </div>
                <div class="w-full md:w-auto flex-shrink-0">
                    <div class="flex flex-col gap-2"><a href="{{ route('marketplace.checkout') }}"
                            class="block w-full md:w-48 text-center px-4 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none">Beli
                            Langsung</a><button
                            class="w-full md:w-48 text-center px-4 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 focus:outline-none inline-flex items-center justify-center gap-2"><svg
                                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>Chat Penjual</button></div>
                </div>
            </div>
        </div>

        <div x-data="reviewsSection()" class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Ulasan Pembeli</h2>
            <div class="flex items-center mt-2 text-sm text-gray-600"><svg class="w-5 h-5 text-yellow-400 mr-1"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                    </path>
                </svg><span class="font-semibold">5.0</span><span class="mx-2 text-gray-300">|</span><span
                    x-text="`${reviews.length} ulasan`"></span></div>
            <div class="space-y-4 mt-6">
                <template x-for="review in displayReviews" :key="review.id">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-1">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3"><svg
                                    class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg></div><span class="font-semibold text-gray-800" x-text="review.name"></span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="flex items-center"><template x-for="star in 5" :key="star"><svg
                                        class="w-4 h-4"
                                        :class="star <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg></template></div><span class="ml-3" x-text="review.date"></span>
                        </div>
                        <p class="mt-2 text-sm text-gray-800" x-text="review.comment"></p>
                    </div>
                </template>
            </div>
            <div x-show="visibleReviewsCount < reviews.length" class="mt-6 text-center">
                <button @click="visibleReviewsCount += reviewsPerLoad"
                    class="px-4 py-2 bg-gray-100 text-sm text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none">Tampilkan
                    ulasan lebih banyak</button>
            </div>
        </div>
        <div class="mt-8">
            <button onclick="window.history.back()"
                class="block w-full text-center px-4 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                Kembali
            </button>
        </div>
        {{-- Modal untuk Galeri Gambar --}}
        <div x-show="isModalOpen" @click.away="isModalOpen = false" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4" style="display: none;">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Galeri Gambar</h3><button @click="isModalOpen = false"
                        class="text-gray-500 hover:text-gray-800"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="mainImage = image; isModalOpen = false"
                            class="aspect-square bg-gray-100 rounded-lg p-2 transition-all duration-200 focus:outline-none"
                            :class="{ 'ring-2 ring-green-600 ring-offset-2': mainImage === image }"><img
                                :src="image" alt="Galeri Produk"
                                class="w-full h-full object-contain rounded-lg"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Modal untuk Deskripsi --}}
        <div x-show="isDescriptionModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4" style="display: none;">
            <div @click.away="isDescriptionModalOpen = false" x-show="isDescriptionModalOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-bold text-gray-800">Deskripsi Lengkap</h3><button
                        @click="isDescriptionModalOpen = false" class="text-gray-500 hover:text-gray-800"><svg
                            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></button>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div class="prose prose-sm max-w-none text-gray-600">
                        <p>Ini adalah deskripsi produk yang cukup panjang untuk tujuan demonstrasi. Kaleng cat ini dibuat
                            dari bahan berkualitas tinggi yang tahan terhadap karat dan cuaca ekstrem. Sangat cocok untuk
                            penggunaan di luar ruangan maupun di dalam ruangan. Dengan teknologi pengecatan terbaru, warna
                            tidak akan mudah pudar dan akan tetap cerah selama bertahun-tahun. Produk ini juga ramah
                            lingkungan karena menggunakan bahan daur ulang.</p>
                        <p>Spesifikasi tambahan:
                        <ul>
                            <li>Volume: 5 Liter</li>
                            <li>Material: Baja Galvanis</li>
                            <li>Ketebalan: 0.5mm</li>
                            <li>Warna Tersedia: Merah, Biru, Hijau</li>
                        </ul>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function productPage() {
            return {
                images: ["{{ asset('img/cat1.jpeg') }}", "{{ asset('img/cat2.jpg') }}", "{{ asset('img/cat3.jpg') }}",
                    "{{ asset('img/cat4.jpg') }}", "{{ asset('img/cat5.jpg') }}",
                ],
                mainImage: null,
                isModalOpen: false,
                isDescriptionModalOpen: false,
                quantity: 1,
                price: 3000,
                stock: 50,
                init() {
                    this.mainImage = this.images.length > 0 ? this.images[0] : '';
                }
            }
        }

        function reviewsSection() {
            return {
                reviewsPerLoad: 5,
                visibleReviewsCount: 5,
                reviews: [{
                        id: 1,
                        name: 'Siti',
                        rating: 5,
                        date: '1 Bulan lalu',
                        comment: 'Bagus Bagus'
                    },
                    {
                        id: 2,
                        name: 'Andi',
                        rating: 5,
                        date: '1 Bulan lalu',
                        comment: 'Sangat rekomen, mantap'
                    },
                    {
                        id: 3,
                        name: 'Budi',
                        rating: 4,
                        date: '2 Bulan lalu',
                        comment: 'Kualitasnya oke, pengiriman cepat.'
                    },
                    {
                        id: 4,
                        name: 'Rina',
                        rating: 5,
                        date: '2 Bulan lalu',
                        comment: 'Sesuai dengan deskripsi. Terima kasih!'
                    },
                    {
                        id: 5,
                        name: 'Joko',
                        rating: 5,
                        date: '3 Bulan lalu',
                        comment: 'Barang bagus, penjual ramah.'
                    },
                    {
                        id: 6,
                        name: 'Dewi',
                        rating: 4,
                        date: '3 Bulan lalu',
                        comment: 'Lumayan, ada sedikit lecet tapi tidak masalah.'
                    },
                ],
                get displayReviews() {
                    return this.reviews.slice(0, this.visibleReviewsCount);
                }
            }
        }
    </script>
@endpush
