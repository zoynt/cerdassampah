@extends('layouts.dashboard')

@section('title', 'Atur Pesanan')

@push('head')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        #map {
            height: 450px;
        }

        @media (min-width: 768px) {
            #map {
                height: 450px;
            }
        }

        /* [PERBAIKAN] Blok CSS yang menyembunyikan panel rute dihapus */
        .leaflet-routing-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #eee;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <div x-data="{ tooltip: false }" class="relative inline-block">
                <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" onclick="window.history.back()"
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
            {{-- [MODIFIKASI] Ukuran font judul halaman --}}
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Atur Pesanan</h1>
        </div>

        {{-- Detail Pesanan --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                {{-- [MODIFIKASI] Di mobile, gambar produk lebih kecil --}}
                <div class="md:col-span-1 bg-green-100 rounded-lg flex items-center justify-center p-4 h-32 md:h-40">
                    <svg class="w-20 h-20 md:w-24 md:h-24 text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M20.63 8.37l-5.63-5.63a2.98 2.98 0 00-4.24 0L3.37 10.13a2.98 2.98 0 000 4.24l5.63 5.63a2.98 2.98 0 004.24 0l7.39-7.39a2.98 2.98 0 000-4.24zM10 14l-4-4m4 4l-2-6">
                        </path>
                    </svg>
                </div>
                <div class="md:col-span-3">
                    {{-- [MODIFIKASI] Ukuran font judul produk dan total order --}}
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Kaleng Cat</h2>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-base text-gray-500">Total Order</span>
                        <span class="font-bold text-base md:text-lg text-gray-800">Rp 30.000</span>
                    </div>
                    <hr class="my-4">
                    <h3 class="font-semibold text-base text-gray-700 mb-2">Informasi</h3>
                    <div class="space-y-2 text-sm">
                        <a href="{{ route('marketplace.store') }}" class="inline-flex items-center group">
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 transition-colors group-hover:text-green-600"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="text-gray-600 transition-colors group-hover:text-green-700">Toko: Hijau Market,
                                Alex</span>
                        </a>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                </path>
                            </svg>
                            <span class="text-gray-600">Berat/Bobot: Kilogram</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Peta & Alamat --}}
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-700">Peta</h3>
                <div id="map" class="w-full rounded-lg mt-2 border border-gray-200"></div>
            </div>
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-700">Jarak Lokasi</h3>
                <p id="distance-info" class="p-3 bg-gray-50 rounded-md mt-2 text-sm md:text-base text-gray-800">Menghitung
                    jarak...</p>
            </div>
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-700">Alamat</h3>
                <p class="p-3 bg-gray-50 rounded-md mt-2 text-sm md:text-base text-gray-800">Jl. Kayu Tangi, Banjarmasin
                    Utara, Kota Banjarmasin, Kalimantan Selatan</p>
            </div>
        </div>

        {{-- Total Pembayaran & Tombol Aksi --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            {{-- [MODIFIKASI] Flexbox diatur agar lebih rapi di mobile --}}
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-4">
                <span class="text-base md:text-lg font-semibold text-gray-700">Total Pembayaran</span>
                <span class="text-xl md:text-2xl font-bold text-green-800">Rp 30.000</span>
            </div>
            <a href="{{ route('marketplace.purchase.detail') }}"
                class="block w-full text-center py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Beli Sekarang
            </a>
        </div>

    </div>
@endsection

@push('scripts')
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userLocation = L.latLng(-3.3005, 114.5912);
            const storeLocation = L.latLng(-3.2915, 114.5985);
            const map = L.map('map');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var routingControl = L.Routing.control({
                waypoints: [userLocation, storeLocation],
                routeWhileDragging: false,
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
                    const marker = L.marker(waypoint.latLng, {
                        icon: markerIcon
                    });
                    return marker.bindPopup(i === 0 ? '<b>Lokasi Anda</b>' : '<b>Lokasi Toko</b>');
                }
            }).addTo(map);

            routingControl.on('routesfound', function(e) {
                var summary = e.routes[0].summary;
                var distance = (summary.totalDistance / 1000).toFixed(2);
                document.getElementById('distance-info').innerText = `${distance} Km`;
            });
        });
    </script>
@endpush
