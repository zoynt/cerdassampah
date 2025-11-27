@extends('layouts.dashboard')

@section('title', 'Jadwal Bank Sampah')
  <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

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

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
            <form id="filter-form" action="{{ route('banksampah-user') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kecamatan" class="block mb-2 text-sm font-medium text-gray-700">Kecamatan</label>
                        <div class="relative">
                            <select id="kecamatan" name="kecamatan"
                                class="block w-full pl-4 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatans as $data)
                                    <option value="{{ $data->district }}" @selected(request('kecamatan') == $data->district)>
                                        {{ $data->district }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

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
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('banksampah-user') }}"
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
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', shadowSize: [41, 41]
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

                    const popupContent =
                        `<div class="w-64 rounded-lg overflow-hidden shadow-lg bg-white p-0">
                            <img class="w-full h-32 object-cover" src="${loc.image_url}" alt="Foto ${loc.nama}">
                            <div class="p-3">
                                <div class="font-bold text-base mb-2 text-gray-800">${loc.nama}</div>
                                <p class="text-gray-600 text-xs mb-2"><span class="font-semibold">Alamat:</span> ${loc.alamat || 'Tidak ada alamat.'}</p>
                                <p class="text-gray-500 text-xs"><span class="font-semibold">Deskripsi: </span>${loc.deskripsi || ''}</p>
                                
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <a href="${detailUrl}" class="block text-center w-full px-4 py-2 bg-white text-green-700 text-sm font-semibold rounded-lg border border-green-200 hover:bg-green-50 transition-colors duration-300">
                                        Detail Bank
                                    </a>
                                    <a href="${setorUrl}" class="button-link block text-center w-full px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-colors duration-300">
                                        Setorkan Sampah
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    
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

            filterForm.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', handleFilterChange);
            });

             filterForm.addEventListener('submit', function(event) {
                 event.preventDefault();
                 handleFilterChange();
             });

        });
    </script>

    {{-- 1. Popup Belum Terdaftar Sama Sekali --}}
    @if(session('show_registration_popup'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Belum Terdaftar!',
                text: "{{ session('warning') }}",
                icon: 'info',
                confirmButtonText: 'Siap, Daftar Sekarang!',
                confirmButtonColor: '#15803d',
                background: '#fff',
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-green-100',
                    title: 'text-green-800 font-bold',
                    confirmButton: 'px-6 py-2 rounded-lg'
                },
                didOpen: () => {
                    const iconElement = Swal.getIcon();
                    if (iconElement) {
                        iconElement.style.borderColor = '#15803d';
                        iconElement.style.color = '#15803d';
                    }
                }
            });
        });
    </script>
    @endif

    {{-- 2. Popup PENDING (Menunggu Persetujuan) --}}
    @if(session('show_pending_popup'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Menunggu Persetujuan!',
                html: `Status nasabah Anda di <b>{{ session('bank_name') }}</b> belum disetujui oleh pihak bank sampah.<br><br>Mohon tunggu atau hubungi pihak bank sampah.`,
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Siap, Hubungi Sekarang!',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#eab308', // Kuning
                cancelButtonColor: '#6b7280',
                background: '#fff',
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-yellow-100',
                    title: 'text-yellow-800 font-bold',
                    confirmButton: 'px-6 py-2 rounded-lg'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buka link WhatsApp di tab baru
                    window.open("{{ session('wa_link') }}", '_blank');
                }
            });
        });
    </script>
    @endif

    {{-- 3. Popup TIDAK AKTIF (Dinonaktifkan) --}}
    @if(session('show_inactive_popup'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Akun Dinonaktifkan!',
                html: `Status nasabah Anda di <b>{{ session('bank_name') }}</b> telah dinonaktifkan oleh pengelola.<br><br>Silakan hubungi pengelola untuk informasi lebih lanjut.`,
                icon: 'error',
                showCancelButton: false,
                confirmButtonText: 'Hubungi Pengelola',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#dc2626', // Merah
                cancelButtonColor: '#6b7280',
                background: '#fff',
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-red-100',
                    title: 'text-red-800 font-bold',
                    confirmButton: 'px-6 py-2 rounded-lg'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buka link WhatsApp di tab baru
                    window.open("{{ session('wa_link') }}", '_blank');
                }
            });
        });
    </script>
    @endif

    <!-- @if(session('show_registration_popup'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Belum Terdaftar!',
                text: "{{ session('warning') }}",
                icon: 'info',
                confirmButtonText: 'Siap, Daftar Sekarang!',
                confirmButtonColor: '#15803d',
                background: '#fff',
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-green-100',
                    title: 'text-green-800 font-bold',
                    confirmButton: 'px-6 py-2 rounded-lg'
                },
                didOpen: () => {
                    const iconElement = Swal.getIcon();
                    if (iconElement) {
                        iconElement.style.borderColor = '#15803d';
                        iconElement.style.color = '#15803d';
                    }
                }
            });
        });
    </script>
    @endif -->
@endpush