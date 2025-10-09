@extends('layouts.dashboard')
@section('title', 'Detail Pembelian')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map-container {
            height: 400px;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('marketplace.history') }}"
                class="flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-md hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pembelian</h1>
        </div>
        <div
            class="bg-green-700 text-white p-4 rounded-xl shadow-md flex justify-between items-center text-sm md:text-base font-semibold">
            <span>No Order</span>
            <span class="tracking-wider">{{ $order->order_number }}</span>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <h3 class="font-semibold text-gray-800 text-base md:text-lg">Informasi Tagihan</h3>
            <div class="pt-2 space-y-4 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Status Transaksi</span>
                    <span
                        class="text-sm font-semibold px-2 py-1 rounded-md capitalize
                        @if ($order->status == 'completed') bg-green-100 text-green-800 @endif
                        @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                        @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                        @if ($order->status == 'canceled') bg-red-100 text-red-800 @endif">
                        {{ $order->status }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pembelian</span>
                    <span class="font-semibold text-gray-800 text-sm md:text-base">Rp
                        {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tanggal Pembelian</span>
                    <span
                        class="font-semibold text-gray-800 text-sm md:text-base">{{ $order->created_at->locale('id')->translatedFormat('d F Y') }}</span>
                </div>
                @if ($order->payment_method)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-semibold text-gray-800 text-sm md:text-base capitalize">
                            {{ str_replace('_', ' ', $order->payment_method) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Informasi Pembelian --}}
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <h3 class="font-semibold text-gray-800 text-base md:text-lg">Informasi Pembelian</h3>
            <div class="pt-2 text-sm space-y-4">
                {{-- Data Penjual/Toko --}}
                <div>
                    <p class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Penjual</p>
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mt-2">

                        {{-- [UBAH DI SINI] Tambahkan optional() dan ?-> untuk keamanan --}}
                        <a href="{{ optional($order->seller?->store)->id ? route('marketplace.store.show', $order->seller->store) : '#' }}"
                            class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                            <img class="w-10 h-10 rounded-full bg-gray-200 object-cover"
                                src="{{ optional($order->seller?->store)->image_path ? asset('storage/' . $order->seller->store->image_path) : asset('img/placeholder.png') }}"
                                alt="Avatar Toko">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ optional($order->seller?->store)->name ?? 'Toko Dihapus' }}</p>
                                <p class="text-xs text-gray-500">{{ optional($order->seller)->name ?? 'Penjual Dihapus' }}
                                </p>
                            </div>
                        </a>

                        {{-- [UBAH DI SINI] Tambahkan optional() dan ?-> untuk keamanan --}}

                        @if ($order->seller && $order->seller->store && $order->seller->store->phone_number)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->seller->store->phone_number) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm transition ease-in-out duration-150">

                                {{-- Ikon Chat --}}
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                </svg>

                                <span>Chat Penjual</span>
                            </a>
                        @endif
                    </div>
                </div>
                <hr class="border-dashed my-4">
                <p class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Item Dibeli</p>
                <div class="space-y-3">
                    @foreach ($order->orderItems as $item)
                        <div class="flex justify-between items-center">
                            <div>
                                {{-- [PERUBAHAN DI SINI] --}}
                                @if ($item->product)
                                    <a href="{{ route('marketplace.products.show', $item->product) }}"
                                        class="font-semibold text-gray-800 hover:text-green-700 transition-colors">
                                        {{ $item->product->name }}
                                    </a>
                                @else
                                    <p class="font-semibold text-gray-500 italic">Produk Dihapus</p>
                                @endif

                                <p class="text-gray-500">{{ $item->quantity }} x Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-semibold text-gray-800 text-sm md:text-base">Rp
                                {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md space-y-6">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Peta Lokasi</h3>
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
                <div id="map-container" class="w-full rounded-xl overflow-hidden border border-gray-200"></div>
            </div>

            {{-- Alamat Toko --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-1">Alamat Toko</h3>
                <p class="p-3 bg-gray-50 rounded-md text-sm text-gray-800">{{ $order->seller->store->address }}</p>
            </div>

            {{-- Jarak Lokasi --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-1">Jarak Lokasi</h3>
                <p id="distance-info" class="p-3 bg-gray-50 rounded-md text-sm text-gray-800">Menunggu izin lokasi...</p>
            </div>

            {{-- Alamat Anda --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-1">Alamat Anda (Lokasi Terkini)</h3>
                <p id="user-address-info" class="p-3 bg-gray-50 rounded-md text-sm text-gray-800">Menunggu lokasi...</p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        {{-- Tombol Aksi (Kode Baru yang Lebih Rapi) --}}
        <div class="pt-2 flex flex-wrap justify-end items-center gap-3">

            {{-- Tombol "Cetak" selalu ditampilkan, tapi kita beri urutan prioritas --}}
            <a href="{{ route('marketplace.invoice.show', $order) }}"
                class="w-full sm:w-auto text-center px-6 py-2.5 font-semibold rounded-lg shadow-sm bg-gray-700 text-white hover:bg-gray-600 order-last sm:order-none">
                Cetak Bukti Pembayaran
            </a>

            {{-- Tombol khusus untuk status 'completed' --}}
            @if (Auth::id() === $order->buyer_id)

                {{-- Tombol khusus untuk status 'completed' --}}
                @if ($order->status == 'completed')
                    @if ($hasReviewed)
                        <a href="{{ route('marketplace.rating.show', $order) }}"
                            class="w-full sm:w-auto text-center px-6 py-2.5 font-semibold rounded-lg shadow-sm bg-yellow-500 text-white hover:bg-yellow-600">
                            Edit Ulasan
                        </a>
                    @else
                        <a href="{{ route('marketplace.rating.show', $order) }}"
                            class="w-full sm:w-auto text-center px-6 py-2.5 font-semibold rounded-lg shadow-sm bg-green-700 text-white hover:bg-green-600">
                            Beri Ulasan
                        </a>
                    @endif

                    {{-- Tombol khusus untuk status 'pending' --}}
                @elseif ($order->status == 'pending')
                    {{-- Tombol "Bayar Sekarang" dan "Batalkan" tetap di sini karena hanya relevan untuk pembeli --}}
                    <form action="{{ route('marketplace.order.cancel', $order) }}" method="POST" class="w-full sm:w-auto"
                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                        @csrf
                        <button type="submit"
                            class="w-full text-center px-6 py-2.5 font-semibold rounded-lg shadow-sm bg-red-600 text-white hover:bg-red-700">
                            Batalkan
                        </button>
                    </form>

                    <button id="pay-now-button"
                        class="w-full sm:w-auto text-center px-6 py-2.5 font-semibold rounded-lg shadow-sm bg-green-700 text-white hover:bg-green-600">
                        Bayar Sekarang
                    </button>

                    {{-- Tombol disabled untuk status lainnya (processing/canceled) --}}
                @else
                    <a
                        class="w-full sm:w-auto text-center px-6 py-2.5 font-semibold rounded-lg shadow-sm bg-gray-200 text-gray-500 cursor-not-allowed">
                        Beri Ulasan
                    </a>
                @endif
                @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-now-button');

            if (payButton) {
                payButton.addEventListener('click', function() {
                    const snapToken = '{{ $order->snap_token }}';
                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            alert("Pembayaran sukses!");
                            window.location.reload();
                        },
                        onPending: function(result) {
                            alert("Menunggu pembayaran Anda!");
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal!");
                        },
                        onClose: function() {
                            alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                        }
                    });
                });
            }
            const distanceInfo = document.getElementById('distance-info');
            const userAddressInfo = document.getElementById('user-address-info');
            const googleMapsLink = document.getElementById('google-maps-link');
            const mapContainer = document.getElementById('map-container');
            const userLat = {{ $order->delivery_latitude ?? 'null' }};
            const userLng = {{ $order->delivery_longitude ?? 'null' }};
            const storeLat = {{ $order->seller->store->latitude ?? 'null' }};
            const storeLng = {{ $order->seller->store->longitude ?? 'null' }};
            userAddressInfo.innerText = "{{ $order->delivery_address ?? 'Alamat pengiriman tidak tersedia.' }}";
            if (!userLat || !userLng || !storeLat || !storeLng) {
                mapContainer.innerHTML =
                    '<div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-500">Data lokasi tidak lengkap untuk menampilkan peta.</div>';
                distanceInfo.innerText = 'Tidak dapat menghitung jarak.';
                return;
            }
            const storeLocation = L.latLng(storeLat, storeLng);
            const userLocation = L.latLng(userLat, userLng);

            const map = L.map('map-container').setView(userLocation, 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);


            const storeIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            const userIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });


            L.marker(storeLocation, {
                icon: storeIcon
            }).addTo(map).bindPopup("<b>Lokasi Toko</b><br>{{ $order->seller->store->name }}");
            L.marker(userLocation, {
                icon: userIcon
            }).addTo(map).bindPopup("<b>Alamat Pengiriman Anda</b>");
            map.fitBounds([storeLocation, userLocation], {
                padding: [50, 50]
            });
            const distance = userLocation.distanceTo(storeLocation) / 1000;
            distanceInfo.innerText = `Sekitar ${distance.toFixed(2)} km dari lokasi toko.`;
            const gmapsUrl =
                `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${storeLat},${storeLng}`;
            googleMapsLink.href = gmapsUrl;
            googleMapsLink.classList.remove('hidden');
        });
    </script>
@endpush
