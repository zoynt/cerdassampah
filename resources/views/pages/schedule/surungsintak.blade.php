@extends('layouts.dashboard')

@section('title', 'Rute & Jadwal')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 450px;
            z-index: 10;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            padding: 1px;
        }

        .leaflet-popup-content {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .leaflet-popup-tip-container {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Surung Sintak</h1>
        <div class="bg-white p-2 rounded-xl shadow-md">
            <div id="map" class="w-full rounded-lg"></div>
        </div>
        {{-- Bagian Filter --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
            <form id="filter-form" action="{{ route('surung-sintak.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Filter Kecamatan --}}
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

                    {{-- Filter Hari --}}
                    <div>
                        <label for="hari" class="block mb-2 text-sm font-medium text-gray-700">Hari</label>
                        <div class="relative">
                            <select id="hari" name="hari"
                                class="block w-full pl-4 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Hari</option>
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                    <option value="{{ $day }}" @selected(request('hari') == $day)>{{ $day }}
                                    </option>
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
                    <a href="{{ route('surung-sintak.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">Wilayah/Area</th>
                            <th scope="col" class="px-6 py-4">Hari</th>
                            <th scope="col" class="px-6 py-4">Jam</th>
                            <th scope="col" class="px-6 py-4">Petugas</th>
                            <th scope="col" class="px-6 py-4">TPS Tujuan</th>
                        </tr>
                    </thead>
                    <tbody id="surung-table-body">
                        @include('layouts.partials._surung_table_body', ['schedules' => $schedules])
                    </tbody>
                </table>
            </div>
            <div id="pagination-container" class="p-4 border-t bg-gray-50">
                @if ($schedules->hasPages())
                    {{ $schedules->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([-3.316694, 114.590111], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let allMarkers = [];
            const greenIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            function updateMapAndTableInteractivity(locations) {
                allMarkers.forEach(marker => map.removeLayer(marker));
                allMarkers = [];

                if (locations && locations.length > 0) {
                    const bounds = L.latLngBounds(locations.map(loc => [loc.lat, loc.lng]));
                    map.fitBounds(bounds, {
                        padding: [50, 50],
                        maxZoom: 15
                    });
                }

                const markerObjectsById = {};
                (locations || []).forEach(loc => {
                    const marker = L.marker([loc.lat, loc.lng], {
                        icon: greenIcon
                    }).addTo(map);
                    const popupContent =
                        `<div class="w-64 rounded-lg overflow-hidden shadow-lg bg-white p-0"><div class="p-4"><div class="font-bold text-base mb-2 text-gray-800">${loc.area}</div><div class="space-y-1.5 text-xs text-gray-600"><p><span class="font-semibold">Kecamatan:</span> ${loc.kecamatan}</p><p><span class="font-semibold">Petugas:</span> ${loc.petugas}</p><p><span class="font-semibold">Deskripsi:</span> ${loc.deskripsi || 'Tidak ada deskripsi.'}</p></div></div></div>`;
                    marker.bindPopup(popupContent);
                    allMarkers.push(marker);
                    markerObjectsById[loc.id] = marker;
                });

                document.querySelectorAll('.surung-row').forEach(row => {
                    row.addEventListener('click', function() {
                        const id = this.dataset.id;
                        if (markerObjectsById[id]) {
                            map.flyTo(markerObjectsById[id].getLatLng(), 16);
                            setTimeout(() => {
                                markerObjectsById[id].openPopup();
                            }, 500);
                        }
                    });
                });
            }

            updateMapAndTableInteractivity(@json($surungLocations));

            const filterForm = document.getElementById('filter-form');

            function handleFilterChange() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;
                history.pushState(null, '', url);
                document.getElementById('surung-table-body').innerHTML =
                    `<tr><td colspan="6" class="text-center p-6 animate-pulse">Memuat data...</td></tr>`;
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('surung-table-body').innerHTML = data.table_html;
                        document.getElementById('pagination-container').innerHTML = data.pagination_html;
                        updateMapAndTableInteractivity(data.map_locations);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('surung-table-body').innerHTML =
                            `<tr><td colspan="6" class="text-center p-6 text-red-500">Gagal memuat data.</td></tr>`;
                    });
            }

            filterForm.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', handleFilterChange);
            });
        });
    </script>
@endpush
