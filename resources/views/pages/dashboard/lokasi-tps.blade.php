@extends('layouts.dashboard')

@section('title', 'Lokasi TPS')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            width: 220px;
        }

        .leaflet-popup-content {
            margin: 13px 19px;
            font-family: 'Poppins', sans-serif;
        }

        .leaflet-popup-content img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .leaflet-popup-content p {
            margin: 4px 0;
            font-size: 12px;
            line-height: 1.4;
            word-wrap: break-word;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lokasi TPS Kota Banjarmasin</h1>
        </div>

        <div class="bg-white p-2 rounded-xl shadow-md">
            <div id="map" class="w-full h-[450px] rounded-lg z-0"></div>
        </div>

        {{-- Bagian Filter --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
            <form id="filter-form" action="{{ route('lokasi-tps.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <div>
                        <label for="kecamatan" class="block mb-2 text-sm font-medium text-gray-700">Kecamatan</label>
                        <div class="relative">
                            <select id="kecamatan" name="kecamatan"
                                class="block w-full pl-4 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatans as $data)
                                    <option value="{{ $data->kecamatan }}" @selected(request('kecamatan') == $data->kecamatan)>
                                        {{ $data->kecamatan }}</option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('lokasi-tps.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        {{-- KOTAK JUMLAH TPS (DINAMIS) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-green-700 text-white p-4 rounded-lg shadow-sm text-center">
                <h3 class="font-semibold">TPS Resmi</h3>
                <p id="resmi-count" class="text-3xl font-bold">{{ $resmiCount }}</p>
            </div>
            <div class="bg-red-700 text-white p-4 rounded-lg shadow-sm text-center">
                <h3 class="font-semibold">TPS Liar (Ilegal)</h3>
                <p id="ilegal-count" class="text-3xl font-bold">{{ $ilegalCount }}</p>
            </div>
        </div>

        {{-- DAFTAR ALAMAT TPS --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Daftar Lokasi</h2>
            <div id="tps-list-container" class="space-y-4">
                @include('layouts.partials._tps_location_list', ['listTps' => $listTps])
            </div>
            <div id="pagination-container" class="pt-6">
                @if ($listTps->hasPages())
                    {{ $listTps->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = L.map('map').setView([-3.316694, 114.590111], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            let allMarkers = [];
            const iconResmi = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });
            const iconIlegal = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            function updateUI(data) {
                // Update Peta
                allMarkers.forEach(marker => map.removeLayer(marker));
                allMarkers = [];
                if (data.map_locations && data.map_locations.length > 0) {
                    const bounds = L.latLngBounds(data.map_locations.map(tps => [tps.latitude, tps.longitude]));
                    map.fitBounds(bounds, {
                        padding: [50, 50],
                        maxZoom: 15
                    });
                }

                const markerObjectsById = {};
                (data.map_locations || []).forEach(tps => {
                    const icon = tps.status === 'resmi' ? iconResmi : iconIlegal;
                    const marker = L.marker([tps.latitude, tps.longitude], {
                        icon: icon
                    }).addTo(map);
                    const popupContent = `
                        <div class="p-3">
                            <img src="${tps.image_url}" alt="Foto TPS">
                            <p class="text-gray-600 text-xs mb-2">${tps.alamat}</p>
                            <p class="inline-block rounded-full px-3 py-1 text-xs font-semibold text-white ${tps.status === 'resmi' ? 'bg-green-600' : 'bg-red-500'}">
                                        Status: ${tps.status.charAt(0).toUpperCase() + tps.status.slice(1)} </p>
                        </div>`;

                    marker.bindPopup(popupContent);
                    allMarkers.push(marker);
                    markerObjectsById[tps.id] = marker;
                });

                // Update Daftar & Paginasi & Hitungan
                document.getElementById('tps-list-container').innerHTML = data.list_html;
                document.getElementById('pagination-container').innerHTML = data.pagination_html;
                document.getElementById('resmi-count').textContent = data.resmiCount;
                document.getElementById('ilegal-count').textContent = data.ilegalCount;

                // Pasang ulang event listener untuk daftar baru
                document.querySelectorAll('.tps-list-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const tpsId = this.dataset.id;
                        const targetMarker = markerObjectsById[tpsId];
                        if (targetMarker) {
                            map.flyTo(targetMarker.getLatLng(), 17);
                            targetMarker.openPopup();
                        }
                    });
                });
            }

            // Inisialisasi awal
            updateUI({
                map_locations: @json($locations),
                list_html: document.getElementById('tps-list-container').innerHTML,
                pagination_html: document.getElementById('pagination-container').innerHTML,
                resmiCount: {{ $resmiCount }},
                ilegalCount: {{ $ilegalCount }}
            });

            // Logika Filter AJAX
            const filterForm = document.getElementById('filter-form');

            function handleFilterChange() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;
                history.pushState(null, '', url);

                document.getElementById('tps-list-container').innerHTML =
                    `<p class="text-center p-6 animate-pulse">Memuat data...</p>`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => updateUI(data))
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('tps-list-container').innerHTML =
                            `<p class="text-center p-6 text-red-500">Gagal memuat data.</p>`;
                    });
            }

            filterForm.querySelector('select').addEventListener('change', handleFilterChange);
        });
    </script>
@endpush
