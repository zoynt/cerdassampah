@extends('layouts.dashboard')

@section('title', 'Lokasi TPS')

@section('content')

    @php
        // DATA STATIS (SEMENTARA) - Nanti ini akan diambil dari database Anda
        $dataTps = [
            [
                'latitude' => -3.3195,
                'longitude' => 114.5925,
                'alamat' =>
                    'Jl. Pangeran Hidayatullah Depan Kantor Pariwisata, Kecamatan Banjarmasin Timur, Kelurahan Benua Anyar',
                'status' => 'Resmi',
                'gambar' =>
                    'https://images.unsplash.com/photo-1579332565413-1064a5f2caf7?q=80&w=2070&auto=format&fit=crop',
            ],
            [
                'latitude' => -3.3245,
                'longitude' => 114.598,
                'alamat' =>
                    'Jl. Belitung Darat, Gg. Serumpun 3, Belitung Utara, Kec. Banjarmasin Barat, Kota Banjarmasin',
                'status' => 'Liar',
                'gambar' =>
                    'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=1974&auto=format&fit=crop',
            ],
            [
                'latitude' => -3.31,
                'longitude' => 114.6001,
                'alamat' => 'Jl. Veteran No.15, RT.16, Melayu, Kec. Banjarmasin Tengah, Kota Banjarmasin',
                'status' => 'Resmi',
                'gambar' =>
                    'https://images.unsplash.com/photo-1594736341257-6d6a5323a231?q=80&w=2070&auto=format&fit=crop',
            ],
        ];
    @endphp

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lokasi TPS Kota Banjarmasin</h1>
        </div>

        <div class="bg-white p-2 rounded-xl shadow-md">
            <div id="map" class="w-full h-[400px] rounded-lg z-0"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-green-700 text-white p-4 rounded-lg shadow-sm text-center">
                <h3 class="font-semibold">TPS & TPS-T</h3>
                <p class="text-2xl font-bold">10</p>
            </div>
            <div class="bg-green-700 text-white p-4 rounded-lg shadow-sm text-center">
                <h3 class="font-semibold">TPS Liar</h3>
                <p class="text-2xl font-bold">10</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            @foreach ($dataTps as $tps)
                <div class="tps-list-item flex justify-between items-center border-b pb-3 cursor-pointer hover:bg-gray-50 transition-colors"
                    data-index="{{ $loop->index }}">
                    <p class="text-gray-600 text-sm">{{ $tps['alamat'] }}</p>
                    @if ($tps['status'] == 'Resmi')
                        <span class="text-xs font-semibold text-green-800 bg-green-100 px-3 py-1 rounded-full">Resmi</span>
                    @else
                        <span class="text-xs font-semibold text-red-800 bg-red-100 px-3 py-1 rounded-full">Liar</span>
                    @endif
                </div>
            @endforeach

            <div class="text-center pt-4">
                <a href="#" class="text-sm font-semibold text-green-700 hover:underline">Tampilkan lebih banyak</a>
            </div>
        </div>
    </div>

@endsection


@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* PENYESUAIAN: Mengatur lebar popup agar lebih persegi */
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            width: 220px;
            /* Atur lebar tetap di sini */
        }

        .leaflet-popup-content {
            margin: 13px 19px;
        }

        .leaflet-popup-content img {
            width: 100%;
            height: 100px;
            /* Sedikit lebih tinggi agar proporsional */
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .leaflet-popup-content p {
            margin: 2px 0;
            font-size: 12px;
            line-height: 1.4;
            word-wrap: break-word;
            /* Memastikan teks panjang turun ke bawah */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = L.map('map').setView([-3.316694, 114.590111], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            setTimeout(function() {
                map.invalidateSize();
            }, 100);

            const iconResmi = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            const iconLiar = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            const dataTps = @json($dataTps);
            const markers = [];

            dataTps.forEach(tps => {
                const icon = tps.status === 'Resmi' ? iconResmi : iconLiar;
                const marker = L.marker([tps.latitude, tps.longitude], {
                    icon: icon
                }).addTo(map);

                const popupContent = `
                <div>
                    <img src="${tps.gambar}" alt="Foto TPS">
                    <p><b>Alamat:</b> ${tps.alamat}</p>
                    <p><b>Status:</b> ${tps.status}</p>
                </div>
            `;

                marker.bindPopup(popupContent);
                markers.push(marker);
            });

            const listItems = document.querySelectorAll('.tps-list-item');
            listItems.forEach(item => {
                item.addEventListener('click', function() {
                    const index = this.dataset.index;
                    const targetMarker = markers[index];

                    if (targetMarker) {
                        const latLng = targetMarker.getLatLng();
                        map.flyTo(latLng, 17);
                        targetMarker.openPopup();
                    }
                });
            });
        });
    </script>
@endpush
