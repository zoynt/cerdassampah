@extends('layouts.dashboard')

@section('title', 'Data Penjualan')

@section('content')
    <div class="grid grid-cols-[minmax(0,_1fr)]">
        <div class="space-y-6" x-data="riwayatData()">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Data Penjualan</h1>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-500 text-white p-4 rounded-2xl shadow-md flex items-center gap-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-medium text-green-100 uppercase tracking-wider">Produk Terjual</p>
                        <p class="text-xl md:text-2xl font-bold truncate">{{ $totalProduk }}</p>
                    </div>
                </div>

                <div class="bg-amber-400 text-amber-900 p-4 rounded-2xl shadow-md flex items-center gap-4">
                    <div class="bg-white/40 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-amber-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 1-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold uppercase tracking-wider">Total Penjualan</p>
                        <p class="text-xl md:text-2xl font-bold truncate">{{ $totalPenjualan }}</p>
                    </div>
                </div>

                <div class="bg-green-500 text-white p-4 rounded-2xl shadow-md flex items-center gap-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-medium text-green-100 uppercase tracking-wider">Total Bersih</p>
                        <p class="text-xl md:text-2xl font-bold truncate">{{ $totalBersih }}</p>
                    </div>
                </div>

                <div class="bg-red-500 text-white p-4 rounded-2xl shadow-md flex items-center gap-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-medium text-green-100 uppercase tracking-wider">Biaya Admin (5%)</p>
                        <p class="text-xl md:text-2xl font-bold truncate">{{ $totalBiayaAdmin }}</p>
                    </div>
                </div>
            </div>

            {{-- Grafik --}}
            <div class="bg-white p-4 md:p-6 rounded-2xl shadow-lg">
                <h3 class="text-base md:text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan (7 Hari Terakhir)</h3>
                <div class="relative h-64 md:h-80"><canvas id="salesChart"></canvas></div>
            </div>

            {{-- Filter & Export --}}
            <div class="flex flex-col md:flex-row gap-4 justify-between items-end w-full mb-6">

                <div class="w-full md:w-1/3">
                    <label for="kategori-filter-button" class="block text-sm font-medium text-gray-700 mb-1">
                        Filter Kategori
                    </label>

                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button @click="open = !open" id="kategori-filter-button"
                            class="w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-4 pr-10 py-2.5 text-left text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 flex items-center justify-between">
                            <span x-text="selectedCategory || 'Semua Kategori'" class="truncate"
                                :class="{ 'text-gray-500': !selectedCategory }"></span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition
                            class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-auto"
                            style="display: none;">
                            <ul class="py-1 text-sm">
                                <li>
                                    <a href="#" @click.prevent="selectedCategory = ''; open = false"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                        :class="{ 'bg-green-100 text-green-800 font-semibold': selectedCategory === '' }">
                                        Semua Kategori
                                    </a>
                                </li>
                                @foreach ($kategoriList as $kategori)
                                    <li>
                                        <a href="#"
                                            @click.prevent="selectedCategory = '{{ $kategori }}'; open = false"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                            :class="{ 'bg-green-100 text-green-800 font-semibold': selectedCategory === '{{ $kategori }}' }">
                                            {{ $kategori }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">

                    <a href="{{ route('marketplace.riwayat.export') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 text-sm w-full md:w-auto">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Export Excel</span>
                    </a>

                    <a href="https://wa.me/{{ env('WA_Admin') }}?text=Halo%20Admin%20CerdasSampah,%20saya%20ingin%20melakukan%20penarikan%20uang%20marketplace"
                        target="_blank"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 text-sm gap-2 w-full md:w-auto">
                        <svg class="w-5 h-5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 448 512">
                            <path
                                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 221.9-99.6 221.9-222 .1-59.3-23.1-115-65.7-157.5zm-157 341.6c-33.8 0-67.3-9.5-97.2-27.9l-6.8-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.4 130.4 54.1 34.8 34.7 56.2 81.1 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.8-16.2-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                        </svg>
                        <span>Penarikan Uang</span>
                    </a>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex gap-6 px-6" aria-label="Tabs">
                        <button @click="activeStatus = 'pending'" type="button"
                            :class="{ 'border-green-600 text-green-600': activeStatus === 'pending', 'border-transparent text-gray-500 hover:text-gray-700': activeStatus !== 'pending' }"
                            class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium">Pesanan Baru</button>
                        <button @click="activeStatus = 'completed'" type="button"
                            :class="{ 'border-green-600 text-green-600': activeStatus === 'completed', 'border-transparent text-gray-500 hover:text-gray-700': activeStatus !== 'completed' }"
                            class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium">Selesai</button>
                        <button @click="activeStatus = 'canceled'" type="button"
                            :class="{ 'border-red-600 text-red-600': activeStatus === 'canceled', 'border-transparent text-gray-500 hover:text-gray-700': activeStatus !== 'canceled' }"
                            class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium">Dibatalkan</button>
                    </nav>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-white uppercase bg-green-600">
                            <tr>
                                <th scope="col" class="px-6 py-4">Pembeli</th>
                                <th scope="col" class="px-6 py-4">Produk Dibeli</th>
                                <th scope="col" class="px-6 py-4 text-center">Jumlah Item</th>
                                <th scope="col" class="px-6 py-4">Total</th>
                                <th scope="col" class="px-6 py-4">Total Bersih</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in paginatedPenjualans" :key="item.order_id">
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900" x-text="item.pembeli"></td>

                                    <td class="px-6 py-4" x-text="item.produk_list"></td>

                                    <td class="px-6 py-4 text-center" x-text="item.jumlah_item"></td>

                                    <td class="px-6 py-4 font-semibold"
                                        x-text="`Rp ${item.total.toLocaleString('id-ID')}`"></td>

                                    <td class="px-6 py-4 font-bold text-green-600"
                                        x-text="`Rp ${item.total_bersih.toLocaleString('id-ID')}`"></td>

                                    <td class="px-6 py-4">
                                        <div x-data="{ tooltip: false }" class="relative flex justify-center">
                                            <div @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                                class="flex items-center justify-center w-8 h-8 rounded-full"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800': item.status === 'pending',
                                                    'bg-blue-100 text-blue-800': item.status === 'processing',
                                                    'bg-green-100 text-green-800': item.status === 'completed',
                                                    'bg-red-100 text-red-800': item.status === 'canceled'
                                                }">
                                                <template x-if="item.status === 'pending'"><svg class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg></template>
                                                <template x-if="item.status === 'processing'"><svg class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                                    </svg></template>
                                                <template x-if="item.status === 'completed'"><svg class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg></template>
                                                <template x-if="item.status === 'canceled'"><svg class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg></template>
                                            </div>
                                            <div x-show="tooltip" x-transition
                                                class="absolute -top-8 z-10 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap capitalize"
                                                x-text="item.translated_status"></div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <template x-if="item.status === 'pending' || item.status === 'processing'">
                                                <div x-data="{ tooltip: false }" class="relative flex">
                                                    <form :action="`/marketplace/orders/${item.order_id}/complete`"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?');">
                                                        @csrf
                                                        <button type="submit" @mouseenter="tooltip = true"
                                                            @mouseleave="tooltip = false"
                                                            class="p-2 text-gray-500 rounded-full hover:bg-green-100 hover:text-green-700 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div x-show="tooltip" x-transition
                                                        class="absolute -top-8 z-10 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap">
                                                        Selesaikan</div>
                                                </div>
                                            </template>
                                            <div x-data="{ tooltip: false }" class="relative flex">
                                                <a :href="item.detailUrl" @mouseenter="tooltip = true"
                                                    @mouseleave="tooltip = false"
                                                    class="p-2 text-gray-500 rounded-full hover:bg-gray-200 hover:text-blue-700 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <div x-show="tooltip" x-transition
                                                    class="absolute -top-8 z-10 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap">
                                                    Detail</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="filteredPenjualans.length === 0">
                                <tr>
                                    <td colspan="7" class="text-center p-6 text-gray-500">Tidak ada data penjualan
                                        untuk filter ini.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <template x-if="totalPages > 1">
                    <div
                        class="p-4 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <span>Baris per halaman:</span>
                            <select x-model.number="itemsPerPage" class="border border-gray-300 rounded-md p-1">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="prevPage()" :disabled="currentPage === 1"
                                class="px-2 py-1 disabled:opacity-50">Sebelumnya</button>
                            <template x-for="page in totalPages" :key="page">
                                <button @click="goToPage(page)"
                                    :class="{
                                        'bg-green-600 text-white': currentPage === page,
                                        'bg-gray-200': currentPage !==
                                            page
                                    }"
                                    class="w-8 h-8 rounded-md" x-text="page"></button>
                            </template>
                            <button @click="nextPage()" :disabled="currentPage === totalPages"
                                class="px-2 py-1 disabled:opacity-50">Selanjutnya</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);
        const ctx = document.getElementById('salesChart').getContext('2d');

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: chartData,
                    backgroundColor: 'rgba(22, 163, 74, 0.2)',
                    borderColor: 'rgba(22, 163, 74, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 10000,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        function riwayatData() {
            return {
                activeStatus: 'pending',
                selectedCategory: '',
                penjualans: @json($penjualans),
                itemsPerPage: 5,
                currentPage: 1,
                get filteredPenjualans() {
                    let byStatus;
                    if (this.activeStatus === 'pending') {
                        byStatus = this.penjualans.filter(item => item.status === 'pending' || item.status ===
                            'processing');
                    } else {
                        byStatus = this.penjualans.filter(item => item.status === this.activeStatus);
                    }
                    if (this.selectedCategory === '') {
                        return byStatus;
                    }
                    return byStatus.filter(item => item.kategori === this.selectedCategory);
                },
                get totalPages() {
                    return Math.ceil(this.filteredPenjualans.length / this.itemsPerPage)
                },
                get paginatedPenjualans() {
                    if (this.currentPage > this.totalPages && this.totalPages > 0) {
                        this.currentPage = this.totalPages;
                    }
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredPenjualans.slice(start, end);
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
                    this.$watch('activeStatus', () => this.currentPage = 1);
                    this.$watch('selectedCategory', () => this.currentPage = 1);
                    this.$watch('itemsPerPage', () => this.currentPage = 1);
                }
            }
        }
    </script>
@endpush
