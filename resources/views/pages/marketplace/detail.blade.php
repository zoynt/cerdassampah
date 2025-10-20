@extends('layouts.dashboard')

@section('title', 'Detail Produk')
@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    <div class="space-y-6 md:space-y-8" x-data="productPage()">
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
            {{-- Kolom Kiri: Gambar Produk --}}
            <div class="lg:col-span-1 space-y-4">
                <button @click="isModalOpen = true" class="w-full block group">
                    <div
                        class="bg-gray-100 rounded-xl p-4 flex items-center justify-center aspect-square shadow-sm overflow-hidden">
                        <img :src="mainImage ? mainImage.url : '{{ asset('img/placeholder.png') }}'"
                            :alt="`{{ $product->name }}`"
                            class="w-full h-full object-cover rounded-xl transition-transform duration-300 ease-in-out group-hover:scale-110">
                    </div>
                </button>
                <div class="flex items-center gap-4">
                    <template x-for="(image, index) in images.slice(0, 3)" :key="index">
                        <button @click="mainImage = image"
                            class="w-1/4 aspect-square bg-gray-100 rounded-lg p-2 transition-all duration-200"
                            :class="{ 'ring-2 ring-green-600 ring-offset-2': mainImage === image }">
                            <img :src="image.url" alt="Thumbnail Produk"
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

            <div class="lg:col-span-1 bg-white p-6 md:p-8 rounded-xl shadow-md h-full">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">{{ $product->name }}</h1>
                <div class="flex items-center text-sm text-gray-500 mt-2">
                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    @if ($product->store->reviews->count() > 0)
                        <span>{{ number_format($product->store->reviews->avg('rating'), 1) }}</span>
                    @else
                        <span class="text-xs">Belum dinilai</span>
                    @endif
                    <span class="mx-2 text-gray-300">|</span>
                    @php
                        $soldCount = $product->orderItems->where('order.status', 'completed')->sum('quantity');
                    @endphp
                    <span>Terjual {{ $soldCount }}+</span>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-green-700 mt-4 tracking-tight">Rp
                    {{ number_format($product->price, 0, ',', '.') }}</p>
                <hr class="my-6 border-gray-200">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Informasi</h3>
                    <div class="space-y-5 text-sm">
                        <a href="{{ route('marketplace.store.show', $product->store->slug) }}" {{-- <a href="{{ route('marketplace.store.show', ['store' => $product->store->id]) }}" --}}
                            class="flex items-start gap-4 hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors duration-200">
                            <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Toko</p>
                                <p class="font-semibold text-gray-700">{{ $product->store->name }}</p>
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
                                <p class="font-semibold text-gray-700">{{ $product->store->address }}</p>
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
                                <p class="font-semibold text-gray-700">{{ $product->selling_unit }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-gray-200">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                    <div class="relative max-h-24 overflow-hidden" x-ref="descriptionContainer">
                        <div class="prose prose-sm max-w-none text-gray-600">{!! $product->description !!}</div>
                        <template x-if="isDescriptionOverflowing">
                            <div
                                class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent pointer-events-none">
                            </div>
                        </template>
                    </div>
                    <template x-if="isDescriptionOverflowing">
                        <button @click="isDescriptionModalOpen = true"
                            class="mt-2 text-sm font-bold text-green-700 hover:text-green-600">Baca Selengkapnya</button>
                    </template>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-start gap-4">
                <div
                    class="w-16 h-16 md:w-20 md:h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <img :src="mainImage ? mainImage.url : '{{ asset('img/placeholder.png') }}'" alt="Produk Kecil"
                        class="w-full h-full object-contain rounded-lg">
                </div>
                <div class="flex-1">
                    <h2 class="text-lg md:text-xl font-bold text-gray-800 leading-tight">{{ $product->name }}</h2>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <p x-text="`Stok tersedia: ${stock}`"></p>
                    </div>
                </div>
            </div>
            <div class="border-t my-4"></div>

            @if ($product->status === 'draft')
                {{-- Tampilkan pesan JIKA PRODUK DIARSIPKAN --}}
                <div class="text-center p-4 bg-gray-100 border border-gray-300 text-gray-600 rounded-lg">
                    <p class="font-bold">Produk Diarsipkan</p>
                    <p class="text-sm mt-1">Produk ini tidak lagi ditampilkan di toko dan tidak dapat dibeli.</p>
                </div>
            @else
                {{-- Jika tidak diarsipkan, tampilkan logika pembelian seperti biasa --}}
                <template x-if="stock > 0 && {{ Js::from($isStoreOpen) }}">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Atur Jumlah</label>
                            <div class="flex items-center border border-gray-300 rounded-lg w-32 flex-shrink-0">
                                <button @click="decrement()" type="button"
                                    class="px-3 py-2 text-gray-600 hover:bg-gray-100 focus:outline-none rounded-l-lg">-</button>
                                <input type="text" :value="formattedQuantity()" @change="handleQuantityInput($event)"
                                    class="w-full text-center font-semibold text-gray-800 text-sm py-2 border-l border-r border-gray-300 focus:outline-none focus:ring-0">
                                <button @click="increment()" type="button"
                                    class="px-3 py-2 text-gray-600 hover:bg-gray-100 focus:outline-none rounded-r-lg">+</button>
                            </div>
                        </div>
                        <div class="flex-1 md:text-right">
                            <p class="text-sm text-gray-500">Subtotal</p>
                            <p class="text-xl md:text-2xl font-bold text-gray-800"
                                x-text="`Rp ${((price / step) * quantity).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}`">
                            </p>
                        </div>
                        <div class="w-full md:w-auto flex flex-col sm:flex-row md:flex-col gap-2 flex-shrink-0">
                            <form
                                @submit.prevent="window.location.href = `{{ route('marketplace.checkout', ['store' => $product->store, 'product_slug' => Str::slug($product->name) . '-' . $product->id]) }}?quantity=${quantity}&image_name=${mainImage ? mainImage.url.split('/').pop() : ''}`"
                                method="GET" class="w-full">
                                <button type="submit"
                                    class="w-full md:w-48 text-center px-4 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-600">Beli
                                    Langsung</button>
                            </form>
                            <a href="https://wa.me/{{ $product->store->formatted_phone_number ?? preg_replace('/^0/', '62', $product->store->phone_number) }}"
                                target="_blank"
                                class="w-full md:w-48 text-center px-4 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 448 512">
                                    <path
                                        d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 221.9-99.6 221.9-222 .1-59.3-23.1-115-65.7-157.5zm-157 341.6c-33.8 0-67.3-9.5-97.2-27.9l-6.8-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.4 130.4 54.1 34.8 34.7 56.2 81.1 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.8-16.2-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                </svg>
                                <span>Chat Penjual</span>
                            </a>
                        </div>
                    </div>
                </template>

                <template x-if="stock <= 0">
                    <div class="text-center p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                        <p class="font-bold">Stok Produk Telah Habis</p>
                        <p class="text-sm mt-1">Produk ini tidak dapat dibeli untuk saat ini.</p>
                    </div>
                </template>

                <template x-if="stock > 0 && !{{ Js::from($isStoreOpen) }}">
                    <div class="text-center p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg">
                        <p class="font-bold">Toko Sedang Tutup Hari Ini</p>
                        <p class="text-sm mt-1">Produk ini tidak dapat dibeli karena toko tidak beroperasi.</p>
                    </div>
                </template>
            @endif
        </div>
        <div x-data="reviewsSection()" class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Ulasan Pembeli</h2>
            <div class="flex items-center mt-2 text-sm text-gray-600"><svg class="w-5 h-5 text-yellow-400 mr-1"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                    </path>
                </svg>
                @if ($product->store->reviews->count() > 0)
                    <span class="font-semibold">{{ number_format($product->store->reviews->avg('rating'), 1) }}</span>
                @else
                    <span class="font-semibold text-gray-500">Belum dinilai</span>
                @endif
                <span class="mx-2 text-gray-300">|</span><span x-text="`${reviews.length} ulasan`"></span>
            </div>
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
                                :src="image.url" alt="Galeri Produk"
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
                        {!! $product->description !!}
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
                images: @json($product->images->map(fn($img) => ['id' => $img->id, 'url' => asset('storage/' . $img->image_path)])),
                mainImage: null,
                isModalOpen: false,
                isDescriptionModalOpen: false,
                isDescriptionOverflowing: false,

                isDecimalUnit: '{{ $product->selling_unit }}' !== 'Buah',
                step: {{ $product->selling_unit === 'Buah' ? 1 : (float) $product->weight_per_item }},
                quantity: {{ $product->selling_unit === 'Buah' ? 1 : (float) $product->weight_per_item }},

                price: {{ $product->price }},
                stock: {{ $product->stock }},

                increment() {
                    let newQuantity = parseFloat(this.quantity) + this.step;

                    // Hanya perbarui kuantitas jika nilai baru TIDAK MELEBIHI stok.
                    if (newQuantity <= this.stock) {
                        // Menggunakan toFixed untuk mengatasi masalah presisi angka desimal di JavaScript
                        this.quantity = parseFloat(newQuantity.toFixed(10));
                    }
                },

                decrement() {
                    // Gunakan this.step yang dinamis
                    let min = this.step;
                    let newQuantity = parseFloat(this.quantity) - this.step;
                    this.quantity = Math.max(min, newQuantity);
                },

                handleQuantityInput(event) {
                    let value = event.target.value.replace(',', '.');
                    let numValue = parseFloat(value);

                    // Jika input tidak valid atau nol, kembalikan ke nilai minimum
                    if (isNaN(numValue) || numValue <= 0) {
                        this.quantity = this.step;
                        return;
                    }

                    // Hitung kelipatan 'step' terdekat dari input
                    let roundedValue = Math.round(numValue / this.step) * this.step;

                    // Cek apakah hasil pembulatan melebihi stok
                    if (roundedValue > this.stock) {
                        // Jika ya, atur ke nilai kelipatan MAKSIMUM yang diizinkan
                        this.quantity = Math.floor(this.stock / this.step) * this.step;
                    } else {
                        // Jika tidak, pastikan nilainya tidak kurang dari step minimum
                        this.quantity = Math.max(this.step, roundedValue);
                    }
                    
                    // Atasi masalah presisi angka desimal
                    this.quantity = parseFloat(this.quantity.toFixed(10));
                },

                formattedQuantity() {
                    // Menentukan jumlah angka di belakang koma berdasarkan step
                    const decimalPlaces = this.isDecimalUnit && this.step % 1 !== 0 ?
                        (this.step.toString().split('.')[1] || '').length :
                        0;

                    return parseFloat(this.quantity).toLocaleString('id-ID', {
                        minimumFractionDigits: decimalPlaces,
                        maximumFractionDigits: decimalPlaces
                    });
                },

                init() {
                    this.mainImage = this.images.length > 0 ? this.images[0] : null;
                    this.$nextTick(() => {
                        if (this.$refs.descriptionContainer) {
                            this.isDescriptionOverflowing = this.$refs.descriptionContainer.scrollHeight > this
                                .$refs.descriptionContainer.clientHeight;
                        }
                    });
                }
            }
        }


        function reviewsSection() {
            return {
                reviewsPerLoad: 5,
                visibleReviewsCount: 5,
                reviews: @json($reviewsFormatted),
                get displayReviews() {
                    return this.reviews.slice(0, this.visibleReviewsCount);
                }
            }
        }
    </script>
@endpush
