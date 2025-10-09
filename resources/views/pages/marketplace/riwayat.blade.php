@extends('layouts.dashboard')

@section('title', 'Data Penjualan')

@section('content')
    <div class="grid grid-cols-[minmax(0,_1fr)]">
        <div class="space-y-6" x-data="riwayatData()">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Data Penjualan</h1>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-green-500 text-white p-6 rounded-2xl shadow-md flex items-center gap-6">
                    <div class="bg-white/20 p-4 rounded-xl"><svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg></div>
                    <div>
                        <p class="text-sm font-medium text-green-100">Total Produk Terjual (Selesai)</p>
                        <p class="text-2xl md:text-4xl font-bold">{{ $totalProduk }}</p>
                    </div>
                </div>
                <div class="bg-amber-400 text-amber-900 p-6 rounded-2xl shadow-md flex items-center gap-6">
                    <div class="bg-white/40 p-4 rounded-xl"><svg class="w-8 h-8 text-amber-900" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 1-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg></div>
                    <div>
                        <p class="text-sm font-semibold">Total Penjualan (Selesai)</p>
                        <p class="text-2xl md:text-4xl font-bold">{{ $totalPenjualan }}</p>
                    </div>
                </div>
            </div>

            {{-- Grafik --}}
            <div class="bg-white p-4 md:p-6 rounded-2xl shadow-lg">
                <h3 class="text-base md:text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan (7 Hari Terakhir)</h3>
                <div class="relative h-64 md:h-80"><canvas id="salesChart"></canvas></div>
            </div>

            {{-- Filter & Export --}}
            <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
                <div class="w-full md:w-1/3">
                    <label for="kategori-filter-button" class="block text-sm font-medium text-gray-700 mb-1">Filter
                        Kategori</label>

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

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
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
                <a href="{{ route('marketplace.riwayat.export') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Export Excel</span>
                </a>
            </div>

            <div class="grid grid-cols-[minmax(0,_1fr)]">
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex gap-6 px-6" aria-label="Tabs">
                            <button @click="activeStatus = 'pending'" type="button"
                                :class="{ 'border-green-600 text-green-600': activeStatus === 'pending', 'border-transparent text-gray-500 hover:text-gray-700': activeStatus !== 'pending' }"
                                class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium">
                                Pesanan Baru
                            </button>
                            <button @click="activeStatus = 'completed'" type="button"
                                :class="{ 'border-green-600 text-green-600': activeStatus === 'completed', 'border-transparent text-gray-500 hover:text-gray-700': activeStatus !== 'completed' }"
                                class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium">
                                Selesai
                            </button>
                            <button @click="activeStatus = 'canceled'" type="button"
                                :class="{ 'border-red-600 text-red-600': activeStatus === 'canceled', 'border-transparent text-gray-500 hover:text-gray-700': activeStatus !== 'canceled' }"
                                class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium">
                                Dibatalkan
                            </button>
                        </nav>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-white uppercase bg-green-600">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Pembeli</th>
                                    <th scope="col" class="px-6 py-4">Produk Dibeli</th>
                                    <th scope="col" class="px-6 py-4">Jumlah Item</th>
                                    <th scope="col" class="px-6 py-4">Total</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                    <th scope="col" class="px-6 py-4">Aksi</th>
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
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 font-medium rounded-full text-xs capitalize"
                                                :class="{
                                                    'bg-green-100 text-green-800': item.status === 'completed',
                                                    'bg-blue-100 text-blue-800': item.status === 'processing',
                                                    'bg-yellow-100 text-yellow-800': item.status === 'pending',
                                                    'bg-red-100 text-red-800': item.status === 'canceled'
                                                }"
                                                x-text="item.status.replace('_', ' ')">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <template x-if="item.status === 'pending' || item.status === 'processing'">
                                                <div class="flex items-center gap-2">
                                                    <form :action="`/marketplace/orders/${item.order_id}/complete`"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?');">
                                                        @csrf
                                                        <button type="submit"
                                                            class="px-4 py-1.5 bg-green-200 text-green-800 font-semibold rounded-full hover:bg-green-300 text-xs whitespace-nowrap">
                                                            Selesaikan
                                                        </button>
                                                    </form>
                                                    <a :href="item.detailUrl"
                                                        class="px-4 py-1.5 bg-blue-100 text-blue-800 font-semibold rounded-full hover:bg-blue-200 text-xs">Detail</a>
                                                </div>
                                            </template>

                                            <template x-if="item.status === 'completed' || item.status === 'canceled'">
                                                <a :href="item.detailUrl"
                                                    class="px-4 py-1.5 bg-blue-100 text-blue-800 font-semibold rounded-full hover:bg-blue-200 text-xs">Detail</a>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="filteredPenjualans.length === 0">
                                    <tr>
                                        <td colspan="6" class="text-center p-6 text-gray-500">
                                            Tidak ada data penjualan untuk filter ini.
                                        </td>
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
                                            'bg-green-600 text-white': currentPage ===
                                                page,
                                            'bg-gray-200': currentPage !== page
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
                            callback: function(value, index, values) {
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
                        // Jika tab 'Pesanan Baru' aktif, tampilkan 'pending' DAN 'processing'
                        byStatus = this.penjualans.filter(item => item.status === 'pending' || item.status === 'processing');
                    } else {
                        // Untuk tab lain, perilakunya tetap sama
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
