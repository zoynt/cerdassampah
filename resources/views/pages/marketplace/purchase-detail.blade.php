@extends('layouts.dashboard')

@section('title', 'Detail Pembelian')

@push('head')
    {{-- Leaflet CSS & Plugin --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        #map-container {
            height: 450px;
            z-index: 10;
        }

        @media (min-width: 768px) {
            #map-container {
                height: 450px;
            }
        }

        .leaflet-routing-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        {{-- [MODIFIKASI] Ukuran font judul utama --}}
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pembelian</h1>

        {{-- [MODIFIKASI] Ukuran font No Order --}}
        <div
            class="bg-green-700 text-white p-4 rounded-xl shadow-md flex justify-between items-center text-sm md:text-base font-semibold">
            <span>No Order</span>
            <span class="tracking-wider">123456789</span>
        </div>

        {{-- Bagian Ulasan --}}
        <div x-data="{ rating: 0, hoverRating: 0 }" class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-semibold text-gray-800 text-base md:text-lg">Beri Ulasan & Rating</h3>
            {{-- [MODIFIKASI] Ukuran bintang rating dikecilkan di mobile --}}
            <div class="flex items-center justify-center space-x-1 md:space-x-2 my-6">
                <template x-for="star in 5" :key="star">
                    <button @click="rating = star" @mouseenter="hoverRating = star" @mouseleave="hoverRating = 0"
                        class="text-4xl md:text-5xl text-gray-300 focus:outline-none transition-transform transform hover:scale-110"
                        :class="{ '!text-yellow-400': (hoverRating >= star || rating >= star) }">
                        &#9733;
                    </button>
                </template>
            </div>
            <div>
                <label for="ulasan" class="block text-sm font-medium text-gray-700 mb-1">Ulasan (Opsional)</label>
                <textarea id="ulasan" rows="4" placeholder="Bagaimana kualitas produk ini?"
                    class="w-full border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm p-3 md:p-4"></textarea>
            </div>
            <div class="mt-4 flex justify-end">
                <button
                    class="px-6 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-green-600">Kirim
                    Ulasan</button>
            </div>
        </div>

        {{-- Bagian Informasi Tagihan --}}
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <h3 class="font-semibold text-gray-800 text-base md:text-lg">Informasi Tagihan</h3>
            <div class="pt-2 space-y-4 text-sm">
                <div class="flex flex-col md:flex-row justify-between md:items-center">
                    <span class="text-gray-600 mb-2 md:mb-0">Status Transaksi</span>
                    <span
                        class="text-sm font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md self-start md:self-auto">
                        Menunggu Pengambilan
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Jumlah Pembelian</span>
                    <span class="font-semibold text-gray-800 text-sm md:text-base">10 Kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Order</span>
                    <span class="font-semibold text-gray-800 text-sm md:text-base">Rp 30.000</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-semibold text-gray-800 text-sm md:text-base text-right">BNI Virtual Account</span>
                </div>
                <div class="font-semibold text-gray-800 text-sm md:text-base border-t border-gray-200 pt-4 mt-4">
                    Batas Waktu Pengambilan: Minggu, 14 September 2025
                </div>
            </div>
        </div>

        {{-- Bagian Informasi Pembelian --}}
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <h3 class="font-semibold text-gray-800 text-base md:text-lg">Informasi Pembelian</h3>
            <div class="pt-2 text-sm space-y-4">
                <div>
                    <p class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Pembeli</p>
                    <div class="flex items-center gap-3 mt-2">
                        <img class="w-10 h-10 rounded-full bg-gray-200"
                            src="https://ui-avatars.com/api/?name=Asih&color=7F9CF5&background=EBF4FF" alt="Avatar Pembeli">
                        <p class="font-semibold text-gray-800">Asih</p>
                    </div>
                </div>
                <hr>
                <div>
                    <p class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Toko</p>
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mt-2">
                        <a href="{{ route('marketplace.store') }}"
                            class="flex items-center gap-3 hover:opacity-80 transition-opacity duration-200">
                            <img class="w-10 h-10 rounded-full bg-gray-200"
                                src="https://ui-avatars.com/api/?name=Alex&color=34D399&background=ECFDF5"
                                alt="Avatar Toko">
                            <div>
                                <p class="font-semibold text-gray-800">Hijau Market</p>
                                <p class="text-xs text-gray-500">Alex</p>
                            </div>
                        </a>
                        <button
                            class="w-full md:w-auto inline-flex items-center justify-center text-sm font-semibold bg-green-700 text-white rounded-lg px-4 py-2 hover:bg-green-600 flex-shrink-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            Chat Penjual
                        </button>
                    </div>
                </div>
                <hr class="border-dashed my-4">
                <p class="font-semibold text-sm md:text-base text-gray-800">Kaleng Cat</p>
                <p><span class="text-gray-500">Kategori:</span> Logam</p>
                <p><span class="text-gray-500">Berat/Bobot:</span> 1 Kilogram</p>
                <p><span class="text-gray-500">Harga Satuan:</span> Rp 3.000</p>
            </div>
        </div>

        {{-- Bagian Peta --}}
        <div class="bg-white p-6 rounded-xl shadow-md space-y-6">
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-700">Peta Pengambilan</h3>
                <div id="map-container" class="w-full rounded-xl overflow-hidden shadow-lg mt-2 border border-gray-200">
                </div>
            </div>
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-700">Alamat</h3>
                <p class="p-3 bg-gray-50 rounded-md mt-2 text-gray-800 text-sm">Jl. Kayu Tangi, Banjarmasin Utara, Kota
                    Banjarmasin, Kalimantan Selatan</p>
            </div>
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-700">Jarak Lokasi</h3>
                <p id="distance-info" class="p-3 bg-gray-50 rounded-md mt-2 text-gray-800 text-sm">Menghitung jarak...</p>
            </div>
            <button
                class="w-full px-6 py-2.5 bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:bg-green-600">
                Item Telah di Ambil
            </button>
        </div>

        <div class="pt-6 flex flex-col md:flex-row justify-end items-center gap-4">
            <button onclick="window.history.back()"
                class="w-full md:w-auto px-6 py-2.5 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                Kembali
            </button>
            <a href="{{ route('marketplace.invoice') }}"
                class="w-full md:w-auto text-center px-6 py-2.5 bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:bg-green-600">
                Cetak Bukti Pembayaran
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Leaflet JS & Plugin --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userLocation = L.latLng(-3.3005, 114.5912);
            const storeLocation = L.latLng(-3.2915, 114.5985);
            const map = L.map('map-container');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var routingControl = L.Routing.control({
                waypoints: [userLocation, storeLocation],
                // [PERBAIKAN] Pastikan "show: false" TIDAK ADA di sini agar panel muncul
                collapsible: true, // Ini yang membuat panel bisa dibuka-tutup
                addWaypoints: false,
                draggableWaypoints: false,
                lineOptions: {
                    styles: [{
                        color: '#15803d',
                        weight: 6
                    }]
                },
                createMarker: function(i, waypoint, n) {
                    const iconUrl = i === 0 ?
                        'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png' :
                        'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
                    const markerIcon = new L.Icon({
                        iconUrl: iconUrl,
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });
                    return L.marker(waypoint.latLng, {
                        icon: markerIcon
                    }).bindPopup(i === 0 ? '<b>Lokasi Anda</b>' : '<b>Lokasi Toko</b>');
                }
            }).addTo(map);

            routingControl.on('routesfound', function(e) {
                var summary = e.routes[0].summary;
                var distance = (summary.totalDistance / 1000).toFixed(2);
                document.getElementById('distance-info').innerText = `Jarak Pengambilan: ${distance} Km`;
            });
        });
    </script>
@endpush
