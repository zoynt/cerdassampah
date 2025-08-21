@extends('layouts.landing')

@section('content')

    <div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-[-999]">
        <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 490" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150">
            <path d="M 0,500 L 0,187 C 257.5,150.5 515,114 755,114 C 995,114 1217.5,150.5 1440,187 L 1440,500 L 0,500 Z" stroke="none" stroke-width="0" fill="#60cb83" fill-opacity="1" class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 250)"></path>
        </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-32 flex flex-col-reverse lg:flex-row items-center justify-between gap-10 overflow-hidden" style="padding-bottom: 48px;">
        <!-- Konten Kiri -->
        <div class="w-full text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mt-6 mb-12 text-white" data-aos="zoom-in">
                Laporan TPS Liar
            </h1>

            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-up">
            <!-- Form Laporan -->
            <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama Lengkap -->
            <div class="col-span-1 flex flex-col items-center sm:items-start">
                <label for="name" class="block text-lg font-semibold text-gray-600">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Nama" required>
            </div>

            <!-- Email -->
            <div class="col-span-1 flex flex-col items-center sm:items-start">
                <label for="email" class="block text-lg font-semibold text-gray-600">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Email" required>
            </div>
        </div>

        <!-- Peta -->
        <div class="mb-6 flex flex-col items-center sm:items-start">
            <label for="map" class="block text-lg font-semibold text-gray-600">Peta</label>
            <div id="mapid" class="w-full h-52 bg-gray-100 rounded-lg mb-4">
                <!-- Leaflet Map will be initialized here -->
            </div>
        </div>

<!-- Latitude and Longitude Fields -->
<div class="grid grid-cols-2 gap-6 mb-6">
    <div class="col-span-1 flex flex-col items-center sm:items-start">
        <label for="latitude" class="block text-lg font-semibold text-gray-600">Latitude</label>
        <input type="text" id="latitude" name="latitude" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Latitude" readonly>
    </div>
    <div class="col-span-1 flex flex-col items-center sm:items-start">
        <label for="longitude" class="block text-lg font-semibold text-gray-600">Longitude</label>
        <input type="text" id="longitude" name="longitude" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Longitude" readonly>
    </div>
</div>


<!-- Alamat -->
<div class="mb-6 flex flex-col items-center sm:items-start">
    <label for="address" class="block text-lg font-semibold text-gray-600">Alamat</label>
    <input type="text" id="address" name="address" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Alamat" required>
</div>


        <!-- Unggah Bukti Pendukung -->
        <div class="mb-6 flex flex-col items-center sm:items-start">
            <label for="file" class="block text-lg font-semibold text-gray-600">Unggah Bukti Pendukung</label>

            <!-- Flex untuk area unggah -->
            <div class="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg mt-2 cursor-pointer" id="upload-area" onclick="document.getElementById('file').click()">
                <div class="flex flex-col items-center justify-center w-full text-center" id="upload-content">
                    <!-- Ikon dan Teks -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="33" viewBox="0 0 50 33" fill="none" id="upload-icon">
                        <path d="M47.1056 15.5849C43.9543 12.3535 39.9359 9.09895 37.0498 5.62873C36.4114 4.86114 35.6959 4.15187 34.9106 3.51294C32.1178 1.24087 28.6148 -0.000809 25 3.95457e-07C20.9023 3.95457e-07 17.1713 1.56676 14.385 4.12812C11.931 6.38397 8.99308 8.30236 6.11843 9.9898C5.03637 10.625 4.04884 11.4234 3.19477 12.3653C1.13744 14.6343 -0.000357712 17.576 8.43599e-08 20.625C8.43599e-08 27.4519 5.60417 33 12.5 33H39.5833C45.3333 33 50 28.38 50 22.6875C50 19.9226 48.8988 17.4237 47.1056 15.5849ZM39.5833 28.875H12.5C7.89583 28.875 4.16667 25.1831 4.16667 20.625C4.16667 16.3969 7.35417 12.87 11.5833 12.4369C12.963 12.2965 14.2032 11.4751 14.8542 10.2506C15.8124 8.40438 17.2666 6.85565 19.0567 5.77488C20.8468 4.69411 22.9033 4.12321 25 4.125C30.4583 4.125 35.1667 7.96125 36.2292 13.2619C36.5985 15.0901 38.142 16.4473 40.0025 16.5797L40.0417 16.5825C41.6078 16.6868 43.0759 17.3745 44.1505 18.5072C45.225 19.6399 45.8263 21.1336 45.8333 22.6875C45.8333 26.0906 43.0208 28.875 39.5833 28.875ZM19.3418 15.9141C18.3589 16.8871 19.048 18.5625 20.431 18.5625C21.286 18.5625 21.9792 19.2556 21.9792 20.1107V21.7292C21.9792 23.3975 23.3316 24.75 25 24.75C26.6684 24.75 28.0208 23.3975 28.0208 21.7292V20.1107C28.0208 19.2556 28.714 18.5625 29.569 18.5625C30.952 18.5625 31.6411 16.8871 30.6582 15.9141L27.4112 12.6996C26.0756 11.3774 23.9244 11.3774 22.5888 12.6996L19.3418 15.9141Z" fill="#757575"/>
                    </svg>
                    <span class="text-lg text-gray-500" id="upload-text">Unggah atau ambil foto di sini</span>
                    <div id="file-name" class="mt-2 text-gray-600 hidden"></div>
                </div>
            </div>

            <!-- Input file yang tersembunyi -->
            <input type="file" id="file" name="file" class="hidden" accept=".png, .jpg, .jpeg" onchange="displayFileName()">
        </div>


        <!-- Tombol Kirim Laporan -->
        <div class="mt-6">
            <button type="submit" class="w-full py-3 text-white text-lg bg-green-600 font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                Kirim Laporan
            </button>
        </div>
            </form>
        </div>

        </div>
    </div>


    <style>
        html, body {
            overflow-x: hidden;
        }

        /* Additional adjustments for spacing and responsiveness */
        @media (max-width: 768px) {
            .flex-col-reverse {
                flex-direction: column-reverse;
            }
            .lg\:py-32 {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
        }

        /* Font Awesome Icons */
        .fa-phone-alt, .fa-envelope, .fa-link, .fa-instagram, .fa-youtube, .fa-sitemap {
            font-size: 1.25rem;
        }

/* Menambahkan gaya agar file yang dipilih muncul di dalam border, dan border berubah menjadi hijau */
#upload-area {
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #D1D5DB;
    border-radius: 10px;
    transition: border-color 0.3s ease-in-out;
}

/* Setelah file dipilih, border menjadi hijau */
#upload-area.file-selected {
    border-color: #38A169; /* Hijau */
}

/* Menyembunyikan teks dan ikon jika file terpilih */
#upload-area.file-selected #upload-text,
#upload-area.file-selected #upload-icon {
    display: none;
}

/* Ketika file terpilih, tampilkan nama file di dalam area border */
#upload-area.file-selected #file-name {
    display: block;
    font-size: 14px;
    color: #4A5568;
}


/* Leaflet map container */
#mapid {
    width: 100%;
    height: 350px;
    border-radius: 10px;
}

/* For the border and dashed style on file upload */
.border-2 {
    border-width: 2px;
}

.border-dashed {
    border-style: dashed;
}

.border-gray-300 {
    border-color: #D1D5DB;
}

/* Mengatur warna teks untuk setiap span */
.text-green {
    color: #38A169; /* Warna hijau Tailwind */
    font-weight: 600; /* Untuk membuat teks tebal */
}

.text-yellow {
    color: #F59E0B; /* Warna kuning Tailwind */
    font-weight: 600; /* Untuk membuat teks tebal */
}

.text-red {
    color: #EF4444; /* Warna merah Tailwind */
    font-weight: 600; /* Untuk membuat teks tebal */
}

        /* Agar kontrol layer tetap rata kiri */
.leaflet-control-layers {
    text-align: left; /* Membuat label dan kontrol di rata kiri */
}

.leaflet-control-layers label {
    text-align: left; /* Mengatur label agar rata kiri */
    padding-left: 5px; /* Memberikan sedikit jarak di kiri */
}

        .img {
            width: 95%;     /* Gambar akan mengisi lebar kontainer */
            height: 300px;   /* Anda bisa mengubah tinggi gambar sesuai keinginan */
            display: block;  /* Pastikan gambar tetap berbentuk blok */
            margin: 0 auto;  /* Memastikan gambar berada di tengah */
        }

        .img-bank {
            width: 95%;     /* Gambar akan mengisi lebar kontainer */
            height: 400px;   /* Anda bisa mengubah tinggi gambar sesuai keinginan */
            display: block;  /* Pastikan gambar tetap berbentuk blok */
            margin: 0 auto;  /* Memastikan gambar berada di tengah */
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f9;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .header {
            background-color: #4CAF50;
            padding: 20px;
            color: white;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 48%;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section h2 {
            color: #4CAF50;
        }

        .section p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .section img {
            width: 100%;
            max-width: 200px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .section:last-child {
            margin-right: 0;
        }

        .section img {
            margin-left: auto;
            margin-right: auto;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }

            .section {
                width: 100%;
                margin-bottom: 20px;
            }

            .header {
                font-size: 18px;
            }

            .section img {
                max-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .header {
                font-size: 16px;
            }

            .section p {
                font-size: 14px;
            }

            .section img {
                max-width: 120px;
            }
        }
    </style>


<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    AOS.init();

    // Deklarasi marker dan userMarker secara global
    let marker;   // Marker untuk TPS Liar
    let userMarker; // Marker untuk lokasi pengguna

    document.addEventListener("DOMContentLoaded", function () {
        const targets = document.querySelectorAll('.highlight-animate');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                } else {
                    entry.target.classList.remove('active');
                }
            });
        }, {
            threshold: 0.6
        });
        targets.forEach(target => observer.observe(target));

        const map = L.map('mapid').setView([-3.2910732404474583, 114.59812756610306], 13);

        const baseOSM = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 18,
        });

        const baseESRI = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles © Esri', maxZoom: 18,
        });

        const baseMapTiler = L.tileLayer('https://api.maptiler.com/maps/satellite/{z}/{x}/{y}.jpg?key=8fxAAHSSmNgIETRazciC', {
            attribution: '© MapTiler © OpenStreetMap contributors', maxZoom: 18,
        });

        baseOSM.addTo(map);

        // Marker icon berwarna merah untuk TPS liar
        const markerRed = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            shadowSize: [41, 41]
        });

        let isUsingGPS = false;  // Flag untuk menandakan apakah GPS digunakan

        // Klik pada peta untuk menambahkan marker manual
        map.on('click', function (e) {
            if (isUsingGPS) {
                return;  // Tidak menambahkan marker jika GPS sedang digunakan
            }

            // Hapus marker sebelumnya jika ada
            if (marker) {
                map.removeLayer(marker);
            }

            // Menambahkan marker manual
            marker = L.marker(e.latlng, { icon: markerRed }).addTo(map);
            const latlng = e.latlng;
            marker.bindPopup("<b>TPS Liar</b><br>Latitude: " + latlng.lat + "<br>Longitude: " + latlng.lng).openPopup();

            // Update Latitude dan Longitude fields
            updateLatLngFields(latlng.lat, latlng.lng);

            // Reverse Geocoding untuk mengupdate Alamat (Address)
            reverseGeocode(latlng.lat, latlng.lng);
        });

        // Mendapatkan lokasi GPS pengguna
        function locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 13);  // Set map to user's location

                    // Hapus marker GPS jika ada
                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }

                    // Menambahkan marker lokasi pengguna
                    userMarker = L.marker([lat, lng], { icon: markerRed, draggable: true }).addTo(map);
                    userMarker.bindPopup("<b>TPS Liar</b><br>Latitude: " + lat + "<br>Longitude: " + lng).openPopup();

                    // Set flag GPS aktif
                    isUsingGPS = true;

                    // Menambahkan event listener untuk drag marker
                    userMarker.on('dragend', function(event) {
                        const newLatLng = event.target.getLatLng();
                        userMarker.bindPopup("<b>TPS Liar</b><br>Latitude: " + newLatLng.lat + "<br>Longitude: " + newLatLng.lng).openPopup();

                        // Update Latitude dan Longitude fields ketika marker dipindahkan
                        updateLatLngFields(newLatLng.lat, newLatLng.lng);

                        // Reverse Geocoding untuk mengupdate Alamat (Address) ketika marker dipindahkan
                        reverseGeocode(newLatLng.lat, newLatLng.lng);
                    });

                    // Update Latitude dan Longitude fields dengan lokasi GPS awal
                    updateLatLngFields(lat, lng);

                    // Reverse Geocoding untuk mengupdate Alamat (Address)
                    reverseGeocode(lat, lng);
                }, function(error) {
                    alert("Geolocation is not supported or permission denied.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Mengaktifkan lokasi pengguna
        locateUser();

        const baseMaps = {
            "Citra Biasa (OSM)": baseOSM,
            "Citra Satelit (ESRI)": baseESRI,
            "Citra Satelit (MapTiler)": baseMapTiler
        };

        const overlayMaps = {};

        L.control.layers(baseMaps, overlayMaps, { collapsed: true }).addTo(map);

        // Disable zoom by default
        map.scrollWheelZoom.disable();
    });

    // Fungsi untuk menampilkan nama file yang dipilih
    function displayFileName() {
        const fileInput = document.getElementById('file');
        const fileName = document.getElementById('file-name');
        const uploadArea = document.getElementById('upload-area');  // Ambil elemen area unggah
        const uploadText = document.getElementById('upload-text'); // Teks untuk upload
        const uploadIcon = document.getElementById('upload-icon'); // Ikon upload

        if (fileInput.files.length > 0) {
            fileName.textContent = `File terpilih: ${fileInput.files[0].name}`;
            uploadText.style.display = 'none'; // Sembunyikan teks upload
            uploadIcon.style.display = 'none'; // Sembunyikan ikon upload
            uploadArea.classList.add('file-selected');  // Ubah border menjadi hijau
        } else {
            fileName.textContent = '';  // Jika tidak ada file terpilih
            uploadText.style.display = 'block'; // Tampilkan teks upload lagi
            uploadIcon.style.display = 'block'; // Tampilkan ikon upload lagi
            uploadArea.classList.remove('file-selected');  // Kembalikan border ke warna semula
        }
    }

    // Fungsi untuk mengupdate latitude dan longitude di form
    function updateLatLngFields(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Fungsi untuk melakukan reverse geocoding dan mengupdate alamat
    function reverseGeocode(lat, lng) {
        const apiUrl = `/reverse-geocode?lat=${lat}&lon=${lng}`;
        console.log("Calling reverse geocode:", apiUrl); // Debug

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log("Response dari reverse geocode:", data); // Debug

                if (data && data.display_name) {
                    const address = data.display_name;
                    document.getElementById('address').value = address;

                    // Memperbarui popup marker jika ada
                    if (marker) {
                        marker.bindPopup(`<b>TPS Liar</b><br>Latitude: ${lat}<br>Longitude: ${lng}<br>Alamat: ${address}`).openPopup();
                    }

                    if (userMarker) {
                        userMarker.bindPopup(`<b>TPS Liar</b><br>Latitude: ${lat}<br>Longitude: ${lng}<br>Alamat: ${address}`).openPopup();
                    }
                } else {
                    document.getElementById('address').value = "Alamat tidak ditemukan";
                }
            })
            .catch(error => {
                console.error("Error fetching address:", error);
                document.getElementById('address').value = "Gagal mengambil alamat";
            });
    }
</script>

@endsection
