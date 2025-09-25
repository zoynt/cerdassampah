@extends('layouts.dashboard')

@section('title', 'Detail Bank Sampah')

@push('styles')
    {{-- CSS untuk Peta Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 250px;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">


    {{-- ======================================================= --}}
    {{-- DROPDOWN FILTER BARU YANG BISA DICARI (SEARCHABLE) --}}
    {{-- ======================================================= --}}
    <div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Bank Sampah</h1>

        {{-- PERBAIKAN: Menggunakan $daftarBank untuk list, bukan $bankSampah --}}
        <div x-data="{
                open: false,
                search: '',
                banks: {{ json_encode($daftarBank) }},
                get filteredBanks() {
                    if (this.search === '') return this.banks;
                    return this.banks.filter(
                        bank => bank.bank_name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                selectBank(slug) {
                    window.location.href = '{{ url('/bank-sampah') }}/' + slug;
                }
            }" class="relative">

            <input type="text"
                   x-model="search"
                   @click="open = true"
                   @click.away="open = false"
                   placeholder="{{ $bankSampah->bank_name }}"
                   class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" /></svg>
            </div>

            <div x-show="open" x-transition
                 class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                <template x-for="bank in filteredBanks" :key="bank.id">
                    <div @click="selectBank(bank.slug)"
                         class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer"
                         {{-- PERBAIKAN: Menampilkan nama bank dari list --}}
                         x-text="bank.bank_name">
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- HEADER KARTU DENGAN GAMBAR --}}
    <div class="relative h-48 rounded-2xl overflow-hidden shadow-lg">
        {{-- PERBAIKAN: Menggunakan asset() dan kolom 'image' --}}
        <img src="{{ asset($bankSampah->image) }}" alt="Foto {{ $bankSampah->bank_name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-6">
            <h1 class="text-3xl font-bold text-white">{{ $bankSampah->bank_name }}</h1>
            {{-- PERBAIKAN: Menggunakan kolom 'bank_address' --}}
            <p class="text-white/80">{{ $bankSampah->bank_address }}</p>
        </div>
    </div>

    {{-- KONTEN UTAMA (GRID 2 KOLOM) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI: DAFTAR HARGA SAMPAH --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Jenis Sampah yang Diterima</h2>
                <div x-data="{ openCategories: {{ json_encode($hargaSampah->keys()) }} }" class="space-y-2">
                    @forelse ($hargaSampah as $kategori => $items)
                        <div>
                            <button @click="openCategories.includes('{{ $kategori }}') ? openCategories = openCategories.filter(c => c !== '{{ $kategori }}') : openCategories.push('{{ $kategori }}')" class="w-full flex justify-between items-center p-4 bg-gray-100 rounded-lg text-left font-semibold text-gray-700 hover:bg-gray-200 transition">
                                <span>{{ $kategori }}</span>
                                <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': openCategories.includes('{{ $kategori }}')}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="openCategories.includes('{{ $kategori }}')" x-transition class="p-4 space-y-3">
                                @foreach ($items as $item)
                                <div class="flex justify-between items-center border-b pb-2">
                                    {{-- PERBAIKAN: Sesuaikan dengan nama kolom item sampah Anda, contoh 'item_name' --}}
                                    <p class="text-gray-600">{{ $item->item_name ?? 'Nama Item Tidak Ditemukan' }}</p>
                                    <p class="font-medium text-gray-800">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}/kg</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Bank sampah ini belum mendaftarkan harga sampah.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: INFO & PETA --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex">
                        <span class="w-24 font-semibold text-gray-500 shrink-0">Jam Buka</span>
                        {{-- PERBAIKAN: Menggabungkan data hari dan jam dari database --}}
                        <span class="text-gray-800">
                            {{-- Tampilkan hari jika ada --}}
                            @if($bankSampah->bank_day && is_array($bankSampah->bank_day))
                                {{ implode(', ', $bankSampah->bank_day) }}
                            @endif
                            <br>
                            {{-- Tampilkan jam buka dan tutup --}}
                            {{ \Carbon\Carbon::parse($bankSampah->bank_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($bankSampah->bank_end_time)->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-24 font-semibold text-gray-500 shrink-0">Kontak</span>
                        {{-- PERBAIKAN: Menggunakan kolom 'bank_no' --}}
                        <span class="text-gray-800">{{ $bankSampah->bank_no }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 font-semibold text-gray-500 shrink-0">Deskripsi</span>
                        {{-- PERBAIKAN: Menggunakan kolom 'bank_description' --}}
                        <span class="text-gray-800 flex-1">{{ $bankSampah->bank_description }}</span>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- PERBAIKAN: Tombol kontak menggunakan 'bank_no' --}}
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bankSampah->bank_no) }}" target="_blank" class="flex items-center justify-center gap-2 text-center w-full py-2 bg-green-100 text-green-800 font-semibold rounded-lg hover:bg-green-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 12c0 1.74.45 3.48 1.34 5l-1.4 5.13 5.26-1.38c1.45.81 3.12 1.25 4.71 1.25h.01c5.46 0 9.9-4.45 9.9-9.9S17.5 2 12.04 2zM9.57 8.52c0-.23.12-.35.24-.35h.58c.12 0 .33.02.5.3.17.28.58 1.4.66 1.5.08.12.12.2.04.33-.08.12-.12.2-.24.32-.12.12-.24.2-.36.32-.12.1-.2.18-.08.35.12.17.52.7 1.1 1.25.78.73 1.45 1 1.65 1.12.2.12.32.1.4-.04.08-.12.35-.4.47-.52.12-.12.24-.1.35-.04.12.04 1.12.53 1.32.6.2.08.32.12.36.18.04.08.02.43-.06.81-.08.37-.52.68-1.2.93-.68.24-1.3.26-1.92.14-.63-.12-1.3-.2-2.32-1.23-1.2-1.2-2-2.65-2.08-3.1-.08-.43.06-.83.06-.83z"/></svg>
                        <span>Kontak</span>
                    </a>
                    {{-- PERBAIKAN: Tombol rute menggunakan 'bank_latitude' & 'bank_longitude' dan URL Google Maps yang benar --}}
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $bankSampah->bank_latitude }},{{ $bankSampah->bank_longitude }}" target="_blank" class="flex items-center justify-center gap-2 text-center w-full py-2 bg-blue-100 text-blue-800 font-semibold rounded-lg hover:bg-blue-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <span>Lihat Rute</span>
                    </a>
                </div>
            </div>
            <div class="bg-white p-2 rounded-2xl shadow-lg">
                <div id="map" class="w-full rounded-lg"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            {{-- PERBAIKAN: Menggunakan kolom 'bank_latitude' & 'bank_longitude' --}}
            const lat = {{ $bankSampah->bank_latitude }};
            const lng = {{ $bankSampah->bank_longitude }};
            const map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map)
                {{-- PERBAIKAN: Menggunakan kolom 'bank_address' --}}
                .bindPopup('<b>{{ $bankSampah->bank_name }}</b><br>{{ $bankSampah->bank_address }}').openPopup();
        });
    </script>
@endpush
