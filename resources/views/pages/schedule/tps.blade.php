{{-- File: resources/views/pages/jadwal/tps.blade.php --}}

@extends('layouts.dashboard')

{{-- Judul Halaman --}}
@section('title', 'Rute & Jadwal')

{{-- Sisipkan CSS Kustom dan Leaflet --}}
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            z-index: 10;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            padding: 1px;
        }

        .leaflet-popup-content {
            margin: 0;
            padding: 0;
        }

        .leaflet-popup-tip-container {
            display: none;
        }
    </style>
@endpush


@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal TPS</h1>

        {{-- Bagian Peta --}}
        <div class="bg-white p-2 rounded-xl shadow-md">
            <div id="map" class="w-full rounded-lg"></div>
        </div>

        {{-- Bagian Filter --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Dropdown-dropdown filter --}}
                <div class="relative">
                    <select
                        class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option>Semua TPS</option>
                        {{-- Opsi lainnya bisa ditambahkan di sini --}}
                    </select>
                    {{-- Icon panah dropdown --}}
                </div>
                <div class="relative">
                    <select
                        class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option>Semua Hari</option>
                        {{-- Opsi lainnya bisa ditambahkan di sini --}}
                    </select>
                    {{-- Icon panah dropdown --}}
                </div>
            </div>
        </div>

        {{-- Bagian Tabel Jadwal --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">TPS</th>
                            <th scope="col" class="px-6 py-4">Alamat</th>
                            <th scope="col" class="px-6 py-4">Hari</th>
                            <th scope="col" class="px-6 py-4">Waktu</th>
                            <th scope="col" class="px-6 py-4">Jenis Angkutan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- INI ADALAH CONTOH DATA HARDCODED UNTUK SLICING JIKA TANPA CONTROLLER --}}
                        {{-- Cara ini kurang disarankan karena membuat kode berantakan --}}
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">1</td>
                            <td class="px-6 py-4">TPS Kayu Tangi</td>
                            <td class="px-6 py-4">Jl. Sultan Adam</td>
                            <td class="px-6 py-4">Senin & Kamis</td>
                            <td class="px-6 py-4">07.00 - 09.00</td>
                            <td class="px-6 py-4">Truk Sampah Organik</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">2</td>
                            <td class="px-6 py-4">TPS Cemara Raya</td>
                            <td class="px-6 py-4">Jl. Cemara Raya</td>
                            <td class="px-6 py-4">Selasa & Jumat</td>
                            <td class="px-6 py-4">07.00 - 09.00</td>
                            <td class="px-6 py-4">Truk Sampah Anorganik</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">3</td>
                            <td class="px-6 py-4">TPS S. Parman</td>
                            <td class="px-6 py-4">Jl. Brigjend H. Hasan Basri</td>
                            <td class="px-6 py-4">Rabu & Sabtu</td>
                            <td class="px-6 py-4">08.00 - 10.00</td>
                            <td class="px-6 py-4">Truk Sampah Organik</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">4</td>
                            <td class="px-6 py-4">TPS Adhyaksa</td>
                            <td class="px-6 py-4">Jl. Adhyaksa</td>
                            <td class="px-6 py-4">Senin & Kamis</td>
                            <td class="px-6 py-4">07.00 - 09.00</td>
                            <td class="px-6 py-4">Truk Sampah Campuran</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">5</td>
                            <td class="px-6 py-4">TPS Veteran</td>
                            <td class="px-6 py-4">Jl. Veteran</td>
                            <td class="px-6 py-4">Selasa & Jumat</td>
                            <td class="px-6 py-4">08.00 - 10.00</td>
                            <td class="px-6 py-4">Truk Sampah Organik</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Bagian Paginasi (Static Sesuai Desain) --}}
            <div class="flex items-center justify-between p-4 border-t">
                {{-- ... kode paginasi sama seperti sebelumnya ... --}}
            </div>
        </div>
    </div>
@endsection


{{-- Sisipkan JS Leaflet dan Inisialisasi Peta --}}
@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([-3.316694, 114.590111], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // DATA LOKASI STATIC LANGSUNG DI JAVASCRIPT
            const lokasiTps = [{
                    nama: 'TPS Kayu Tangi',
                    alamat: 'Jl. Sultan Adam',
                    lat: -3.3005,
                    lng: 114.5932,
                    detail: 'Beroperasi di kelurahan Kayu Tangi'
                },
                {
                    nama: 'TPS Cemara Raya',
                    alamat: 'Jl. Cemara Raya',
                    lat: -3.3051,
                    lng: 114.6015,
                    detail: 'Dekat pasar Cemara'
                },
                {
                    nama: 'TPS S. Parman',
                    alamat: 'Jl. Brigjend H. Hasan Basri',
                    lat: -3.3108,
                    lng: 114.5888,
                    detail: 'Sebelah ULM'
                },
                {
                    nama: 'TPS Adhyaksa',
                    alamat: 'Jl. Adhyaksa',
                    lat: -3.2974,
                    lng: 114.5981,
                    detail: 'Area perumahan'
                },
                {
                    nama: 'TPS Veteran',
                    alamat: 'Jl. Veteran',
                    lat: -3.3210,
                    lng: 114.5950,
                    detail: 'Pusat kota'
                },
            ];

            // Loop untuk menambahkan marker (sama seperti sebelumnya)
            lokasiTps.forEach(tps => {
                const marker = L.marker([tps.lat, tps.lng]).addTo(map);
                const popupContent = `
                    <div class="w-64 rounded-lg overflow-hidden shadow-lg bg-white p-0">
                        <img class="w-full h-24 object-cover" src="{{ asset('img/tps-placeholder.jpg') }}" alt="Foto TPS">
                        <div class="p-3">
                            <div class="font-bold text-base mb-1 text-gray-800">${tps.nama}</div>
                            <p class="text-gray-600 text-xs mb-2">
                                <span class="font-semibold">Alamat:</span> ${tps.alamat}
                            </p>
                            <p class="text-gray-500 text-xs">${tps.detail}</p>
                        </div>
                    </div>
                `;
                marker.bindPopup(popupContent, {
                    offset: [0, -20]
                });
                if (tps.nama === 'TPS Kayu Tangi') {
                    marker.openPopup();
                }
            });
        });
    </script>
@endpush
