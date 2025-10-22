@extends('layouts.dashboard')

{{-- Judul halaman dinamis --}}
@section('title', 'Bank Sampah - ' . $bank->bank_name)

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    {{-- ====================================================== --}}
    {{-- Logika PHP untuk Cek Status Pendaftaran Nasabah --}}
    {{-- ====================================================== --}}
    @php
        $user = Auth::user(); // Ambil user yang login
        $rekeningDiBankIni = null; // Default null
        // Cek hanya jika user benar-benar login
        if ($user) {
            // Cari rekening user ini di bank ($bank) yang sedang ditampilkan
            $rekeningDiBankIni = $user->rekeningBankSampah()->where('bank_id', $bank->id)->first();
        }
        // Tentukan apakah sudah terdaftar (punya rekening, status apapun)
        $sudahTerdaftar = $rekeningDiBankIni !== null;
        // Ambil status pendaftaran jika sudah terdaftar
        $statusPendaftaran = $rekeningDiBankIni?->status; // Akan null jika belum terdaftar
    @endphp
    {{-- ====================================================== --}}

    <div x-data="marketplaceData()" x-init="$watch('searchQuery', () => { visibleItemsCount = itemsPerLoad });
    $watch('selectedCategory', () => { visibleItemsCount = itemsPerLoad });" x-cloak>
        <div class="space-y-6">
            {{-- Header Halaman --}}
            <div class="relative h-64 rounded-xl overflow-hidden shadow-lg">
                <img src="{{ $bank->image_path ? asset('storage/' . $bank->image_path) : asset('img/placeholder.png') }}"
                     alt="Foto Bank {{ $bank->bank_name }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="relative h-full flex flex-col items-center justify-center text-center text-white p-4">
                    <h1 class="text-3xl md:text-4xl font-bold">{{ $bank->bank_name }}</h1>

                    <div class="mt-3">
                        @if ($bank->is_active)
                            <span class="px-4 py-1.5 text-xs font-semibold rounded-full bg-green-500/80 text-white backdrop-blur-sm"> Bank Sampah Aktif </span>
                        @else
                            <span class="px-4 py-1.5 text-xs font-semibold rounded-full bg-red-500/80 text-white backdrop-blur-sm"> Bank Sampah Non-Aktif </span>
                        @endif
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-center mt-2 space-y-2 md:space-y-0 md:space-x-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Buka {{ $bank->opening_hour }} - {{ $bank->closing_hour }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <span>{{ $bank->district }}, {{ $bank->sub_district }}</span>
                        </div>
                    </div>

                    {{-- ====================================================== --}}
                    {{-- Tombol Aksi di Header (Termasuk Tombol Daftar) --}}
                    {{-- ====================================================== --}}
                    <div class="mt-4 flex flex-col sm:flex-row gap-3 items-center justify-center">

                        {{-- Tombol "Ajukan Pendaftaran" (Hanya untuk User Login & Belum Terdaftar) --}}
                        @auth {{-- Cek apakah user login --}}
                            @if (!$sudahTerdaftar) {{-- Cek apakah BELUM punya rekening di bank ini --}}
                                <form action="{{ route('digital.nasabah.daftar', $bank->slug) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit"
                                            class="w-full px-5 py-2.5 bg-yellow-500 text-white font-semibold text-sm rounded-lg backdrop-blur-sm hover:bg-yellow-600 transition-colors shadow-md">
                                        Ajukan Pendaftaran Nasabah
                                    </button>
                                </form>
                            {{-- Tampilkan Status jika sudah mengajukan tapi belum 'Aktif' --}}
                            @elseif ($statusPendaftaran == 'Tidak Aktif')
                                <span class="w-full sm:w-auto px-5 py-2.5 bg-gray-400 text-white font-semibold text-sm rounded-lg backdrop-blur-sm shadow-md cursor-not-allowed">
                                    Menunggu Persetujuan
                                </span>
                            {{-- Jika sudah 'Aktif', tidak perlu tampilkan apa-apa di sini --}}
                            @endif
                        @endauth
                        {{-- Jika tidak login (@guest), tombol tidak akan tampil --}}

                        {{-- Tombol Lihat Info Bank Sampah (Tetap Ada) --}}
                        <a href="{{ route('bank-sampah.profil.show', $bank->slug) }}"
                           class="w-full sm:w-auto px-5 py-2.5 bg-white bg-opacity-20 text-white font-semibold text-sm rounded-lg backdrop-blur-sm hover:bg-opacity-30 transition-colors shadow-md">
                            Lihat Info Bank Sampah
                        </a>
                    </div>
                    {{-- ====================================================== --}}
                    {{-- Akhir Tombol Aksi --}}
                    {{-- ====================================================== --}}

                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg md:text-xl font-semibold text-gray-700 mb-4">Item Sampah yang Diterima</h2>
                {{-- Gunakan grid 2 kolom lagi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Input Pencarian Teks --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Item</label> {{-- Tambah mb-1 --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path> </svg>
                            </div>
                            <input type="text" id="search" name="search" x-model.debounce.300ms="searchQuery"
                                   placeholder="Cari berdasarkan nama item..."
                                   class="block w-full h-11 pl-10 pr-4 py-2.5 text-sm md:text-base text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"> {{-- Tambah h-11 --}}
                        </div>
                    </div>

                    {{-- Filter Kategori --}}
                    <div>
                        <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label> {{-- Tambah mb-1 --}}
                        <div class="relative">
                            <select id="category-filter" name="category-filter" x-model="selectedCategory"
                                    class="appearance-none block w-full h-11 pl-3 pr-10 py-2.5 text-sm md:text-base bg-gray-50 border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 rounded-lg"> {{-- Tambah h-11 --}}
                                <option value="">Semua Kategori</option>
                                <template x-for="category in categories" :key="category.id"> <option :value="category.id" x-text="category.name"></option> </template>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-700">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" /> </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Judul Daftar Item (Bar Hijau) --}}
            <div class="bg-green-700 text-white p-4 rounded-xl shadow-md">
                <h2 class="text-lg md:text-xl font-semibold">Item Sampah</h2>
            </div>

            {{-- Daftar Item --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <template x-for="product in displayProducts" :key="product.id">
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm flex flex-col w-full h-full transform transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="p-4 flex flex-col flex-grow">
                            <span class="text-xs font-semibold text-green-600" x-text="product.category_name"></span>
                            <h3 class="font-bold text-gray-800 text-md mt-1" x-text="product.name"></h3>
                            <div class="flex-grow"></div>
                            <p class="text-lg mt-3 pt-3 border-t border-gray-100">
                                <span class="font-black text-green-600" x-text="`Rp ${parseInt(product.price).toLocaleString('id-ID')}`"></span><span class="font-medium text-gray-500">/kg</span>
                            </p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Tombol Load More --}}
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
    </script>
@endpush