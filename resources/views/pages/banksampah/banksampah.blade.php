@extends('layouts.dashboard')

@section('title', 'Jadwal Bank Sampah')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 450px; z-index: 10; }
        .leaflet-popup-content-wrapper { border-radius: 8px; padding: 1px; }
        .leaflet-popup-content { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        .leaflet-popup-tip-container { display: none; }
        .leaflet-popup-content a { color: inherit; text-decoration: none; }
        .leaflet-popup-content a.button-link { color: white; }
        .custom-select { appearance: none !important; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.25em; }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Bank Sampah</h1>
        <div class="bg-white p-2 rounded-xl shadow-md">
            <div id="map" class="w-full rounded-lg"></div>
        </div>

        {{-- Kartu Filter (Layout Diperbaiki) --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-5">Filter Pencarian</h2>
            <form id="filter-form" action="{{ route('banksampah-user') }}" method="GET">
                <div class="flex flex-wrap items-end gap-4">
                    {{-- Filter Kecamatan --}}
                    <div class="flex-grow w-full sm:w-auto">
                        <label for="kecamatan" class="block mb-1 text-sm font-medium text-gray-700">Kecamatan</label>
                        <div class="relative">
                            <select id="kecamatan" name="kecamatan" class="custom-select block w-full h-11 pl-4 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatans as $data)
                                    <option value="{{ $data->district }}" @selected(request('kecamatan') == $data->district)>{{ $data->district }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Filter Hari --}}
                    <div class="flex-grow w-full sm:w-auto">
                        <label for="hari" class="block mb-1 text-sm font-medium text-gray-700">Hari</label>
                        <div class="relative">
                            <select id="hari" name="hari" class="custom-select block w-full h-11 pl-4 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Semua Hari</option>
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                    <option value="{{ $day }}" @selected(request('hari') == $day)>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit" class="h-11 w-full sm:w-auto text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-6 text-center shadow-sm transition-colors"> Cari </button>
                        <a href="{{ route('banksampah-user') }}" class="h-11 w-full sm:w-auto flex items-center justify-center px-5 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors"> Reset </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Bank Sampah --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">Nama Bank Sampah</th>
                            <th scope="col" class="px-6 py-4">Alamat</th>
                            <th scope="col" class="px-6 py-4">Kecamatan</th>
                            <th scope="col" class="px-6 py-4">Hari Buka</th>
                            <th scope="col" class="px-6 py-4">Jam Operasional</th>
                        </tr>
                    </thead>
                    <tbody id="bank-table-body">
                        @include('layouts.partials._bank_table_body', ['schedules' => $schedules])
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
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            function updateMapAndTableInteractivity(locations) {
                allMarkers.forEach(marker => map.removeLayer(marker));
                allMarkers = [];

                if (locations && locations.length > 0) {
                    const bounds = L.latLngBounds(locations.map(loc => [loc.lat, loc.lng]));
                    map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
                } else {
                     map.setView([-3.316694, 114.590111], 13);
                }

                const markerObjectsById = {};
                (locations || []).forEach(loc => {
                    const marker = L.marker([loc.lat, loc.lng], { icon: greenIcon }).addTo(map);
                    
                    const detailUrl = `/bank-sampah/profil/${loc.slug}`;
                    const setorUrl = `/bank-sampah/item/${loc.slug}`;

                    // ======================================================
                    // [PERBAIKAN] Tambahkan <br> di tombol "Lihat Item"
                    // ======================================================
                    const popupContent = `
                        <div class="w-64 rounded-lg overflow-hidden shadow-lg bg-white p-0">
                            <img class="w-full h-32 object-cover" src="${loc.image_url}" alt="Foto ${loc.nama}">
                            <div class="p-3">
                                <div class="font-bold text-base mb-2 text-gray-800">${loc.nama}</div>
                                <p class="text-gray-600 text-xs mb-2"><span class="font-semibold">Alamat:</span> ${loc.alamat || 'Tidak ada alamat.'}</p>
                                <p class="text-gray-500 text-xs"><span class="font-semibold">Deskripsi: </span>${loc.deskripsi || 'Tidak ada deskripsi.'}</p>
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    {{-- Tombol Detail Bank (Tetap sama) --}}
                                    <a href="${detailUrl}"
                                       class="h-full flex items-center justify-center text-center w-full px-4 py-2 bg-white text-green-700 text-sm font-semibold rounded-lg border border-green-200 hover:bg-green-50 transition-colors duration-200">
                                        Detail<br>Bank {{-- Teks 2 baris --}}
                                    </a>
                                    {{-- Tombol Lihat Item (Tambahkan <br>) --}}
                                    <a href="${setorUrl}"
                                       class="button-link h-full flex items-center justify-center text-center w-full px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-colors duration-200">
                                        Lihat<br>Item {{-- Tambahkan <br> di sini --}}
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    // ======================================================
                    // Akhir Perbaikan Tombol Popup
                    // ======================================================
                        
                    marker.bindPopup(popupContent);
                    allMarkers.push(marker);
                    markerObjectsById[loc.id] = marker;
                });

                document.querySelectorAll('.bank-row').forEach(row => {
                    row.replaceWith(row.cloneNode(true));
                });
                document.querySelectorAll('.bank-row').forEach(row => {
                     row.addEventListener('click', function() {
                         const id = this.dataset.id;
                         if (markerObjectsById[id]) {
                             map.flyTo(markerObjectsById[id].getLatLng(), 16);
                             setTimeout(() => { markerObjectsById[id].openPopup(); }, 500);
                         }
                     });
                 });
            }

            updateMapAndTableInteractivity(@json($bankLocations));

            // Logika AJAX untuk filter
            const filterForm = document.getElementById('filter-form');
            
            function handleFilterChange() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;
                history.pushState(null, '', url);
                document.getElementById('bank-table-body').innerHTML = `<tr><td colspan="6" class="text-center p-6 animate-pulse text-gray-500">Memuat data...</td></tr>`;
                document.getElementById('pagination-container').innerHTML = '';
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('bank-table-body').innerHTML = data.table_html;
                        document.getElementById('pagination-container').innerHTML = data.pagination_html;
                        updateMapAndTableInteractivity(data.map_locations);
                    })
                    .catch(error => {
                        console.error('Error fetching filtered data:', error);
                        document.getElementById('bank-table-body').innerHTML = `<tr><td colspan="6" class="text-center p-6 text-red-500">Gagal memuat data. Coba lagi nanti.</td></tr>`;
                    });
            }

            // Trigger filter saat select berubah
            filterForm.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', handleFilterChange);
            });

            // Trigger filter saat form disubmit (jika pakai tombol Cari)
             filterForm.addEventListener('submit', function(event) {
                 event.preventDefault(); // Mencegah submit form standar
                 handleFilterChange();
             });

        });
    </script>
@endpush