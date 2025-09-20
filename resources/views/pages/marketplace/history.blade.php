@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    <div x-data="transactionHistory()">
        <div class="space-y-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Transaksi</h1>

            <div class="bg-white p-6 rounded-xl shadow-md">
                {{-- Search Bar --}}
                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Transaksi</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" x-model.debounce.300ms="searchQuery"
                            placeholder="Cari berdasarkan produk, penjual, atau toko..."
                            class="block w-full pl-10 pr-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                {{-- Tampilan Desktop (Tabel) --}}
                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-white uppercase bg-green-700">
                            <tr>
                                <th scope="col" class="px-6 py-4">No</th>
                                <th scope="col" class="px-6 py-4">Produk</th>
                                <th scope="col" class="px-6 py-4">Penjual</th>
                                <th scope="col" class="px-6 py-4">Toko</th>
                                <th scope="col" class="px-6 py-4">Harga Satuan</th>
                                <th scope="col" class="px-6 py-4">Jumlah</th>
                                <th scope="col" class="px-6 py-4">Total</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(transaction, index) in paginatedTransactions" :key="transaction.id">
                                <tr @click="window.location.href = '{{ route('marketplace.purchase.detail') }}'"
                                    class="bg-white border-b hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                                    <td class="px-6 py-4" x-text="(currentPage - 1) * itemsPerPage + index + 1"></td>
                                    <td class="px-6 py-4 font-medium text-gray-900" x-text="transaction.produk"></td>
                                    <td class="px-6 py-4" x-text="transaction.penjual"></td>
                                    {{-- [PENAMBAHAN] Kolom Toko --}}
                                    <td class="px-6 py-4" x-text="transaction.toko"></td>
                                    <td class="px-6 py-4" x-text="`Rp ${transaction.hargaSatuan.toLocaleString('id-ID')}`">
                                    </td>
                                    <td class="px-6 py-4" x-text="transaction.jumlah"></td>
                                    <td class="px-6 py-4" x-text="`Rp ${transaction.total.toLocaleString('id-ID')}`"></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                            :class="{
                                                'bg-green-100 text-green-800': transaction.status === 'Selesai',
                                                'bg-yellow-100 text-yellow-800': transaction.status === 'Pending'
                                            }"
                                            x-text="transaction.status">
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="paginatedTransactions.length === 0">
                                <tr>
                                    {{-- [MODIFIKASI] Colspan diubah menjadi 8 --}}
                                    <td colspan="8" class="text-center py-6 text-gray-500">Data tidak ditemukan.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Tampilan Mobile (Card) --}}
                <div class="md:hidden space-y-4">
                    <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                        <div @click="window.location.href = '{{ route('marketplace.purchase.detail') }}'"
                            class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm active:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-bold text-gray-800" x-text="transaction.produk"></p>
                                    <p class="text-xs text-gray-500">Oleh: <span x-text="transaction.penjual"></span></p>
                                    {{-- [PENAMBAHAN] Info Toko --}}
                                    <p class="text-xs text-gray-500 mt-1">Toko: <span x-text="transaction.toko"></span></p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                    :class="{
                                        'bg-green-100 text-green-800': transaction.status === 'Selesai',
                                        'bg-yellow-100 text-yellow-800': transaction.status === 'Pending'
                                    }"
                                    x-text="transaction.status">
                                </span>
                            </div>
                            <hr class="my-3 border-dashed">
                            <div class="text-sm space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga Satuan</span>
                                    <span class="text-gray-700"
                                        x-text="`Rp ${transaction.hargaSatuan.toLocaleString('id-ID')}`"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jumlah</span>
                                    <span class="text-gray-700" x-text="transaction.jumlah"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 font-semibold">Total</span>
                                    <span class="text-gray-800 font-bold"
                                        x-text="`Rp ${transaction.total.toLocaleString('id-ID')}`"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="paginatedTransactions.length === 0">
                        <div class="text-center py-6 text-gray-500 bg-gray-50 rounded-lg">
                            Data tidak ditemukan.
                        </div>
                    </template>
                </div>

                {{-- Paginasi --}}
                <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <span>Baris per halaman:</span>
                        <select x-model.number="itemsPerPage"
                            class="border border-gray-300 rounded-md p-1 focus:ring-green-500 focus:border-green-500">
                            <template x-for="option in perPageOptions">
                                <option :value="option" x-text="option"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-2 py-1 disabled:opacity-50">Sebelumnya</button>
                        <template x-for="page in totalPages" :key="page">
                            <button @click="goToPage(page)"
                                :class="{ 'bg-green-600 text-white': currentPage === page, 'bg-gray-200': currentPage !== page }"
                                class="w-8 h-8 rounded-md" x-text="page"></button>
                        </template>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-2 py-1 disabled:opacity-50">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function transactionHistory() {
            return {
                searchQuery: '',
                perPageOptions: [5, 10, 15, 20],
                itemsPerPage: 5,
                currentPage: 1,
                // [PENAMBAHAN] Data 'toko' ditambahkan di sini
                transactions: [{
                        id: 1,
                        penjual: 'Budi Santoso',
                        toko: 'Toko Cerdas',
                        produk: 'Kaleng Sarden',
                        hargaSatuan: 2500,
                        jumlah: 10,
                        total: 25000,
                        status: 'Selesai'
                    },
                    {
                        id: 2,
                        penjual: 'Budi Santoso',
                        toko: 'Toko Cerdas',
                        produk: 'Botol Plastik',
                        hargaSatuan: 2500,
                        jumlah: 20,
                        total: 50000,
                        status: 'Selesai'
                    },
                    {
                        id: 3,
                        penjual: 'Alex Wijaya',
                        toko: 'Warung Berkah',
                        produk: 'Kardus Tebal',
                        hargaSatuan: 2000,
                        jumlah: 15,
                        total: 30000,
                        status: 'Selesai'
                    },
                    {
                        id: 4,
                        penjual: 'Siti Aminah',
                        toko: 'Lapak Barokah',
                        produk: 'Koran Lama',
                        hargaSatuan: 1000,
                        jumlah: 50,
                        total: 50000,
                        status: 'Selesai'
                    },
                    {
                        id: 5,
                        penjual: 'Alex Wijaya',
                        toko: 'Warung Berkah',
                        produk: 'Jerigen',
                        hargaSatuan: 5000,
                        jumlah: 5,
                        total: 25000,
                        status: 'Selesai'
                    },
                    {
                        id: 6,
                        penjual: 'Rina Marlina',
                        toko: 'Toko Cerdas',
                        produk: 'Buku Tulis Bekas',
                        hargaSatuan: 1200,
                        jumlah: 30,
                        total: 36000,
                        status: 'Selesai'
                    },
                    {
                        id: 7,
                        penjual: 'Alex Wijaya',
                        toko: 'Warung Berkah',
                        produk: 'Kaleng Cat',
                        hargaSatuan: 3000,
                        jumlah: 10,
                        total: 30000,
                        status: 'Pending'
                    }
                ],
                get filteredTransactions() {
                    if (this.searchQuery === '') return this.transactions;
                    return this.transactions.filter(t =>
                        t.penjual.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        t.produk.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        // [MODIFIKASI] Pencarian berdasarkan toko ditambahkan
                        t.toko.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },
                get totalPages() {
                    return Math.ceil(this.filteredTransactions.length / this.itemsPerPage);
                },
                get paginatedTransactions() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredTransactions.slice(start, end);
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },
                goToPage(page) {
                    this.currentPage = page;
                },
                init() {
                    this.$watch('searchQuery', () => {
                        this.currentPage = 1;
                    });
                    this.$watch('itemsPerPage', () => {
                        this.currentPage = 1;
                    });
                }
            }
        }
    </script>
@endpush
