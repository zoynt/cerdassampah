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

    @if (!isset($product))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Data produk tidak ditemukan. Pastikan Anda mengakses halaman dengan ID/slug produk
                yang valid.</span>
        </div>
    @else
        <div class="space-y-6">
            {{-- Detail Pesanan --}}
            <div class="flex items-center gap-4">
                <div x-data="{ tooltip: false }" class="relative inline-block">
                    <button onclick="window.history.back()" @mouseenter="tooltip = true" @mouseleave="tooltip = false"
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
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Atur Pesanan</h1>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                    <div class="md:col-span-1 bg-green-100 rounded-lg h-32 md:h-40 group overflow-hidden">
                        @if (isset($checkoutImage))
                            <img src="{{ asset('storage/' . $checkoutImage->image_path) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-110">
                        @else
                            {{-- Fallback jika tidak ada gambar sama sekali --}}
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 md:w-24 md:h-24 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    {{-- path svg --}}
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="md:col-span-3">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $product->name }}</h2>

                        {{-- [UBAH] Tampilkan detail kuantitas dan harga satuan --}}
                        <div class="text-gray-600 mt-2 text-sm">{{ $quantity }} barang x Rp
                            {{ number_format($product->price, 0, ',', '.') }}</div>

                        <hr class="my-4">

                        <div class="flex items-center justify-between mt-2">
                            <span class="text-base text-gray-500">Total Harga</span>
                            <span class="font-bold text-base md:text-lg text-gray-800">
                                {{-- [UBAH] Hitung total harga berdasarkan kuantitas --}}
                                Rp {{ number_format($product->price * $quantity, 0, ',', '.') }}
                            </span>
                        </div>
                        <hr class="my-4">
                        <h3 class="font-semibold text-base text-gray-700 mb-4">Informasi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">


                            <a href="{{ route('marketplace.store.show', $product->store) }}"
                                class="flex items-center gap-3 p-2 -m-2 rounded-lg hover:bg-gray-50 transition-colors group">

                                {{-- Ikon --}}
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>

                                {{-- Teks --}}
                                <div>
                                    <p class="text-xs text-gray-500">Toko</p>
                                    {{-- Tag <a> di sini diubah menjadi <p> --}}
                                    <p class="font-semibold text-gray-800 transition-colors group-hover:text-green-600">
                                        {{ $product->store->name ?? 'Toko Tidak Ditemukan' }}
                                    </p>
                                </div>
                            </a>

                            {{-- Info Berat/Bobot --}}
                            <div class="flex items-center gap-3">
                                {{-- [UBAH] Warna background dan ikon menjadi hijau --}}
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Berat/Bobot</p>
                                    <p class="font-semibold text-gray-800">
                                        {{ (int) ($product->weight_per_item ?? 0) }}
                                        {{ $product->selling_unit ?? 'Satuan' }}
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Detail Peta & Alamat --}}
            <div class="bg-white p-6 rounded-xl shadow-md space-y-6"> {{-- [UBAH] space-y-4 menjadi space-y-6 --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        {{-- [UBAH] Ukuran font judul dikecilkan --}}
                        <h3 class="font-semibold text-gray-700">Peta Lokasi</h3>

                        {{-- [UBAH] Warna tombol menjadi hijau --}}
                        <a id="google-maps-link" href="#" target="_blank"
                            class="hidden inline-flex items-center gap-2 px-3 py-1.5 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-700 text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M12 2a8 8 0 018 8c0 5.25-8 11.25-8 11.25S4 15.25 4 10a8 8 0 018-8zm0 2.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Lihat Rute</span>
                        </a>
                    </div>
                    <div id="map" class="w-full rounded-lg border border-gray-200"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <div>
                        {{-- [UBAH] Ukuran font judul dikecilkan --}}
                        <h3 class="font-semibold text-gray-700 mb-1">Jarak Lokasi</h3>
                        <p id="distance-info" class="p-3 bg-gray-50 rounded-md text-sm text-gray-800">Menunggu izin
                            lokasi...</p>
                    </div>
                    <div>
                        {{-- [UBAH] Ukuran font judul dikecilkan --}}
                        <h3 class="font-semibold text-gray-700 mb-1">Alamat Toko</h3>
                        <p class="p-3 bg-gray-50 rounded-md text-sm text-gray-800">{{ $product->store->address }}</p>
                    </div>
                </div>
                <div>
                    {{-- [UBAH] Ukuran font judul dikecilkan --}}
                    <h3 class="font-semibold text-gray-700 mb-1">Alamat Anda (Lokasi Terkini)</h3>
                    <p id="user-address-info" class="p-3 bg-gray-50 rounded-md text-sm text-gray-800">Menunggu lokasi...</p>
                </div>
            </div>

            {{-- Total Pembayaran & Tombol Aksi --}}
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-4">
                    <span class="text-base md:text-lg font-semibold text-gray-700">Total Pembayaran</span>
                    <span class="text-xl md:text-2xl font-bold text-green-800">
                        {{ 'Rp ' . number_format($product->price * $quantity, 0, ',', '.') }}
                    </span>
                </div>

                {{-- [HAPUS] Tag <form> dihilangkan, diganti dengan button biasa --}}
                <input type="hidden" name="quantity" id="quantity" value="{{ $quantity }}">
                <input type="hidden" name="delivery_address" id="delivery_address">
                <input type="hidden" name="delivery_latitude" id="delivery_latitude">
                <input type="hidden" name="delivery_longitude" id="delivery_longitude">

                {{-- [UBAH] Tombol ini sekarang memicu JavaScript, bukan submit form --}}
                <button id="pay-button"
                    class="block w-full text-center py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Beli dan Bayar Sekarang
                </button>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
     <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const storeLat = {{ $product->store->latitude ?? 'null' }};
            const storeLng = {{ $product->store->longitude ?? 'null' }};

            const distanceInfo = document.getElementById('distance-info');
            const userAddressInfo = document.getElementById('user-address-info');
            const googleMapsLink = document.getElementById('google-maps-link');

            if (!storeLat || !storeLng) {
                document.getElementById('map').innerHTML =
                    '<div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-500">Lokasi toko tidak tersedia.</div>';
                distanceInfo.innerText = 'Tidak dapat menghitung jarak.';
                return;
            }

            const storeLocation = L.latLng(storeLat, storeLng);

            // --- [INI BAGIAN YANG HILANG] DEFINISI IKON KUSTOM ---
            // Ikon Merah untuk TOKO (Tujuan/Stop)
            const storeIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Ikon Hijau untuk PENGGUNA (Anda/Start)
            const userIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            // --- AKHIR BAGIAN IKON ---

            const map = L.map('map').setView(storeLocation, 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Gunakan ikon MERAH untuk toko
            L.marker(storeLocation, {
                icon: storeIcon
            }).addTo(map).bindPopup("<b>Lokasi Toko</b><br>{{ $product->store->name }}").openPopup();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const userLocation = L.latLng(userLat, userLng);

                        // Gunakan ikon HIJAU untuk pengguna
                        L.marker(userLocation, {
                            icon: userIcon
                        }).addTo(map).bindPopup("<b>Lokasi Anda</b>").openPopup();

                        map.fitBounds([storeLocation, userLocation], {
                            padding: [50, 50]
                        });

                        const distance = userLocation.distanceTo(storeLocation) / 1000;
                        distanceInfo.innerText = `${distance.toFixed(2)} km dari lokasi Anda.`;

                        const gmapsUrl =
                            `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${storeLat},${storeLng}`;
                        googleMapsLink.href = gmapsUrl;
                        googleMapsLink.classList.remove('hidden');

                        fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                userAddressInfo.innerText = data.display_name || 'Alamat tidak ditemukan.';
                                document.getElementById('delivery_address').value = data.display_name;
                                document.getElementById('delivery_latitude').value = userLat;
                                document.getElementById('delivery_longitude').value = userLng;
                            })
                            .catch(error => {
                                userAddressInfo.innerText = 'Gagal mendapatkan nama alamat.';
                                console.error('Error fetching address:', error);
                            });
                    },
                    function(error) {
                        distanceInfo.innerText =
                            'Gagal mendapatkan lokasi Anda. Izinkan akses lokasi di browser Anda.';
                        console.error("Geolocation error: ", error.message);
                    }
                );
            } else {
                distanceInfo.innerText = "Browser Anda tidak mendukung Geolocation.";
            }
        });
    </script>
    <script type="text/javascript">
        // Ambil tombol pembayaran
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Tampilkan loading atau nonaktifkan tombol untuk mencegah klik ganda
            payButton.disabled = true;
            payButton.innerText = 'Memproses...';

            // Kumpulkan data dari form
            const payload = {
                _token: '{{ csrf_token() }}',
                quantity: document.getElementById('quantity').value,
                delivery_address: document.getElementById('delivery_address').value,
                delivery_latitude: document.getElementById('delivery_latitude').value,
                delivery_longitude: document.getElementById('delivery_longitude').value,
            };

            // Kirim request AJAX ke backend untuk mendapatkan Snap Token
            fetch('{{ route('marketplace.order.place', $product) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // Jika ada error dari backend (misal: stok habis)
                        alert(data.error);
                        payButton.disabled = false;
                        payButton.innerText = 'Beli dan Bayar Sekarang';
                    } else {
                        // Jika berhasil, panggil Snap popup
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                /* Anda dapat menambahkan analitik di sini */
                                // Redirect ke halaman detail pembelian setelah sukses
                                window.location.href = data.redirect_url;
                            },
                            onPending: function(result) {
                                /* Anda dapat menambahkan analitik di sini */
                                alert("Menunggu pembayaran Anda!");
                                window.location.href = data.redirect_url;
                            },
                            onError: function(result) {
                                /* Anda dapat menambahkan analitik di sini */
                                alert("Pembayaran gagal!");
                                payButton.disabled = false;
                                payButton.innerText = 'Beli dan Bayar Sekarang';
                            },
                            onClose: function() {
                                /* Anda akan diberitahu jika pelanggan menutup pop-up pembayaran tanpa menyelesaikan pembayaran */
                                payButton.disabled = false;
                                payButton.innerText = 'Beli dan Bayar Sekarang';
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    payButton.disabled = false;
                    payButton.innerText = 'Beli dan Bayar Sekarang';
                });
        });
    </script>
@endpush
