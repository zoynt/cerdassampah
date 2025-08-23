@extends('layouts.dashboard')

@section('title', 'Laporan')

@section('content')

    {{-- Style khusus untuk halaman ini (termasuk Leaflet CSS) --}}

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapid {
            height: 350px;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        #upload-area.file-selected {
            border-color: #22c55e;
        }
    </style>

    <div class="bg-white rounded-xl shadow-md p-6 sm:p-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center">
            Laporan TPS Liar
        </h1>

        @if (session('status'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('status') }}
            </div>
        @endif
        {{-- Blok untuk menampilkan SEMUA error validasi jika ada --}}
        @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <span class="font-medium">Oops! Harap unggah bukti pendukung</span>
                {{-- <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul> --}}
            </div>
        @endif

        <form action="{{ route('laporan.store.user') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Form fields Nama dan Email --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-1">
                    <label for="name" class="block text-base font-semibold text-gray-600">Nama
                        Pelapor</label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-3 border rounded-lg mt-2 bg-gray-100" value="{{ Auth::user()->name }}"
                        readonly>
                </div>
                <div class="col-span-1">
                    <label for="email" class="block text-base font-semibold text-gray-600">Email
                        Pelapor</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 border rounded-lg mt-2 bg-gray-100" value="{{ Auth::user()->email }}"
                        readonly>
                </div>
            </div>

            {{-- Peta --}}
            <div class="mb-6">
                <label for="map" class="block text-base font-semibold text-gray-600 mb-2">Geser Pin
                    atau Klik Peta
                    untuk Menentukan Lokasi</label>
                <div id="mapid" class="w-full bg-gray-100"></div>
            </div>

            {{-- Latitude, Longitude, dan Alamat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="latitude" class="block text-base font-semibold text-gray-600">Latitude</label>
                    <input type="text" id="latitude" name="latitude"
                        class="w-full px-4 py-3 border rounded-lg mt-2 bg-gray-100" readonly required>
                </div>
                <div>
                    <label for="longitude" class="block text-base font-semibold text-gray-600">Longitude</label>
                    <input type="text" id="longitude" name="longitude"
                        class="w-full px-4 py-3 border rounded-lg mt-2 bg-gray-100" readonly required>
                </div>
            </div>

            {{-- PENYESUAIAN: Tombol "Cari" dihapus --}}
            <div class="mb-6">
                <label for="address" class="block text-base font-semibold text-gray-600">Alamat</label>
                <input type="text" id="address" name="address" class="w-full px-4 py-3 border rounded-lg mt-2"
                    placeholder="Ketik alamat, peta akan menyesuaikan otomatis" required>
            </div>

            {{-- ... sisa form (upload file dan tombol kirim) ... --}}
            <div class="mb-6">
                <label for="file" class="block text-base font-semibold text-gray-600">Unggah Bukti
                    Pendukung</label>
                <div class="relative flex items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg mt-2 cursor-pointer"
                    id="upload-area" onclick="document.getElementById('file').click()">
                    <div class="flex flex-col items-center justify-center w-full text-center" id="upload-content">
                        <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />

                        </svg>
                        <span class="text-lg text-gray-500" id="upload-text">Klik untuk unggah
                            foto</span>
                    </div>
                    <img id="image-preview" src="#" alt="Pratinjau Gambar"
                        class="absolute h-full w-full object-contain rounded-lg hidden p-2">
                </div>
                <input type="file" id="file" name="file" class="hidden" accept=".png, .jpg, .jpeg"
                    onchange="previewImage(event)">
            </div>
            <div class="mt-6">
                <button type="submit"
                    class="w-full py-3 text-white text-lg bg-green-600 font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let marker;
            let debounceTimer; // Variabel untuk timer debounce
            const map = L.map('mapid').setView([-3.316694, 114.590111], 13);
            const addressInput = document.getElementById('address');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            const redMarkerIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            function placeMarkerAndGetData(lat, lng) {
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], {
                        icon: redMarkerIcon,
                        draggable: true
                    }).addTo(map);
                    marker.on('dragend', function(event) {
                        const newLatLng = event.target.getLatLng();
                        updateLatLngFields(newLatLng.lat, newLatLng.lng);
                        reverseGeocode(newLatLng.lat, newLatLng.lng);
                    });
                }
                map.flyTo([lat, lng], 17);
                updateLatLngFields(lat, lng);
                // Saat pin digerakkan oleh sistem, kita juga panggil reverseGeocode
                reverseGeocode(lat, lng);
            }

            map.on('click', function(e) {
                placeMarkerAndGetData(e.latlng.lat, e.latlng.lng);
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    placeMarkerAndGetData(lat, lng);
                });
            }


            function geocodeAddress() {
                const address = addressInput.value;
                if (address.length < 5) {
                    return; // Jangan cari jika alamat terlalu pendek
                }

                const apiUrl =
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;
                            // Hanya pindahkan peta dan marker, tidak perlu reverse geocode lagi
                            if (marker) {
                                marker.setLatLng([lat, lon]);
                            } else {
                                marker = L.marker([lat, lon], {
                                    icon: redMarkerIcon,
                                    draggable: true
                                }).addTo(map);
                            }
                            map.flyTo([lat, lon], 17);
                            updateLatLngFields(lat, lon);
                        }
                    })
                    .catch(error => console.error("Error geocoding address:", error));
            }

            // --- PENYESUAIAN: Menerapkan DEBOUNCE pada input alamat ---
            addressInput.addEventListener('keyup', function() {
                // Hapus timer sebelumnya setiap kali ada ketukan baru
                clearTimeout(debounceTimer);
                // Atur timer baru
                debounceTimer = setTimeout(() => {
                    geocodeAddress(); // Jalankan pencarian setelah 1 detik tidak ada ketukan
                }, 1000); // 1000 milidetik = 1 detik
            });
        });

        // --- FUNGSI-FUNGSI PEMBANTU (tidak berubah) ---
        function updateLatLngFields(lat, lng) {
            /* ... */
        }

        function reverseGeocode(lat, lng) {
            /* ... */
        }

        function previewImage(event) {
            /* ... */
        }

        // Implementasi lengkap dari fungsi pembantu
        function updateLatLngFields(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }

        function reverseGeocode(lat, lng) {
            const apiUrl = `/reverse-geocode?lat=${lat}&lon=${lng}`;
            fetch(apiUrl).then(response => response.json()).then(data => {
                document.getElementById('address').value = data.display_name || "Alamat tidak ditemukan";
            }).catch(error => {
                console.error("Error fetching address:", error);
                document.getElementById('address').value = "Gagal mengambil alamat";
            });
        }

        function previewImage(event) {
            const fileInput = event.target;
            const uploadArea = document.getElementById('upload-area');
            const uploadContent = document.getElementById('upload-content');
            const imagePreview = document.getElementById('image-preview');
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadContent.classList.add('hidden');
                    uploadArea.classList.add('file-selected');
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                imagePreview.src = "#";
                imagePreview.classList.add('hidden');
                uploadContent.classList.remove('hidden');
                uploadArea.classList.remove('file-selected');
            }
        }
    </script>

@endsection
