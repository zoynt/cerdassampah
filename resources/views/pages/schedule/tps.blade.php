{{-- Lokasi File: resources/views/pages/schedule/tps.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Rute & Jadwal')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
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

        .leaflet-control-zoom {
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal & Lokasi TPS</h1>

        {{-- Bagian Peta Leaflet --}}
        <div class="bg-white p-2 rounded-xl shadow-md">
            <div id="map" class="w-full rounded-lg"></div>
        </div>

        {{-- Bagian Filter --}}

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
            <form id="filter-form" action="{{ route('tps.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Filter Status --}}
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status TPS</label>
                        <div class="relative">
                            <select id="status" name="status"
                                class="block w-full pl-4 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Status</option>
                                <option value="resmi" @selected(request('status') == 'resmi')>Resmi</option>
                                <option value="ilegal" @selected(request('status') == 'ilegal')>Ilegal</option>
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

                </div>
                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('tps.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>


        {{-- Bagian Tabel Jadwal --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">Nama TPS</th>
                            <th scope="col" class="px-6 py-4">Alamat</th>
                            <th scope="col" class="px-6 py-4">Kecamatan</th>
                            <th scope="col" class="px-6 py-4">Hari Operasional</th>
                            <th scope="col" class="px-6 py-4">Waktu</th>
                            <th scope="col" class="px-6 py-4">Jenis Angkutan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $schedule)
                            {{-- PERUBAHAN DI SINI: Menambahkan class dan data attribute pada <tr> --}}
                            <tr class="bg-white border-b hover:bg-green-50 transition-colors duration-200 cursor-pointer tps-row"
                                data-id="{{ $schedule->id }}" data-lat="{{ $schedule->tps_latitude }}"
                                data-lng="{{ $schedule->tps_longitude }}">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $loop->iteration + $schedules->firstItem() - 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $schedule->tps_name }}</td>
                                <td class="px-6 py-4">{{ $schedule->tps_address }}</td>
                                <td class="px-6 py-4">{{ $schedule->kecamatan }}</td>
                                <td class="px-6 py-4">{{ $schedule->tps_day }}</td>
                                <td class="px-6 py-4">{{ date('H:i', strtotime($schedule->tps_start_time)) }} -
                                    {{ date('H:i', strtotime($schedule->tps_end_time)) }} WITA</td>
                                <td class="px-6 py-4">{{ $schedule->tps_transport }}</td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data TPS yang ditemukan. Silakan coba reset filter Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Paginasi Dinamis --}}
            @if ($schedules->hasPages())
                <div class="p-4 border-t bg-gray-50">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([-3.316694, 114.590111], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const greenIcon = L.icon({
                /* ...icon definition... */
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                shadowSize: [41, 41]
            });
            const redIcon = L.icon({
                /* ...icon definition... */
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            const tpsLocations = @json($tpsLocations);

            // PERUBAHAN DI SINI: Variabel untuk menyimpan semua marker
            let markers = {};

            if (tpsLocations.length > 0) {
                const bounds = L.latLngBounds(tpsLocations.map(tps => [tps.lat, tps.lng]));
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            }

            tpsLocations.forEach(tps => {
                const icon = tps.status === 'resmi' ? greenIcon : redIcon;
                const marker = L.marker([tps.lat, tps.lng], {
                    icon: icon
                }).addTo(map);

                // PERUBAHAN DI SINI: Simpan marker ke dalam object markers
                markers[tps.id] = marker;

                const popupContent = `
                    <div class="w-64 rounded-lg overflow-hidden shadow-lg bg-white p-0">
                        <img class="w-full h-32 object-cover" src="${tps.image_url}" alt="Foto ${tps.nama}">
                        <div class="p-3">
                            <div class="font-bold text-base mb-1 text-gray-800">${tps.nama}</div>
                            <p class="text-gray-600 text-xs mb-2">${tps.alamat}</p>
                            <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold text-white ${tps.status === 'resmi' ? 'bg-green-600' : 'bg-red-500'}">
                                Status: ${tps.status.charAt(0).toUpperCase() + tps.status.slice(1)}
                            </span>
                        </div>
                    </div>`;
                marker.bindPopup(popupContent);
            });

            // PERUBAHAN DI SINI: Tambahkan event listener untuk setiap baris tabel
            const tableRows = document.querySelectorAll('.tps-row');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    const lat = this.dataset.lat;
                    const lng = this.dataset.lng;
                    const id = this.dataset.id;

                    if (lat && lng && id) {
                        // Animasikan peta ke lokasi yang diklik
                        map.flyTo([lat, lng], 17); // Zoom level 17 (lebih dekat)

                        // Buka popup marker yang sesuai
                        if (markers[id]) {
                            // setTimeout untuk memberi waktu animasi map selesai
                            setTimeout(() => {
                                markers[id].openPopup();
                            }, 500); // 500ms
                        }

                        // (Opsional) Beri highlight pada baris yang diklik
                        tableRows.forEach(r => r.classList.remove('bg-green-100'));
                        this.classList.add('bg-green-100');
                    }
                });
            });
            const filterForm = document.getElementById('filter-form');
            // Dapatkan semua elemen <select> di dalam form
            const filterSelects = filterForm.querySelectorAll('select');

            // Tambahkan event listener 'change' ke setiap elemen <select>
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Ketika salah satu select diubah, submit form-nya
                    filterForm.submit();
                });
            });
        });
    </script>
@endpush
