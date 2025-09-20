@extends('layouts.dashboard')

@section('title', 'Profil Marketplace')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style> #map { height: 300px; z-index: 10; } </style>
@endpush

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Profil Marketplace</h1>

    <form action="{{ route('marketplace.profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white p-8 rounded-2xl shadow-lg space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_marketplace" class="block mb-2 text-sm font-medium text-gray-700">Nama Marketplace</label>
                    <input type="text" name="nama_marketplace" value="{{ old('nama_marketplace', $marketplace->nama_marketplace) }}" class="input-field" required>
                </div>

                {{-- Combo Box Hari Operasional --}}
                <div x-data="{
                        open: false,
                        hari: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                        selectedHari: {{ json_encode(old('hari_operasional', $marketplace->hari_operasional ?? [])) }}
                    }">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Hari Operasional</label>
                    <template x-for="day in selectedHari">
                        <input type="hidden" name="hari_operasional[]" :value="day">
                    </template>
                    <div class="relative">
                        <button type="button" @click="open = !open" class="input-field text-left w-full flex justify-between items-center">
                            <span x-show="selectedHari.length === 0" class="text-gray-400">Pilih hari...</span>
                            <span x-show="selectedHari.length > 0" x-text="selectedHari.join(', ')" class="truncate"></span>
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="day in hari" :key="day">
                                <div class="flex items-center px-4 py-2 hover:bg-gray-100">
                                    <input type="checkbox" :id="day" :value="day" x-model="selectedHari" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <label :for="day" class="ml-3 text-sm text-gray-700" x-text="day"></label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="alamat_lengkap" class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" rows="3" class="input-field">{{ old('alamat_lengkap', $marketplace->alamat_lengkap) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kecamatan" class="block mb-2 text-sm font-medium text-gray-700">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $marketplace->kecamatan) }}" class="input-field">
                </div>
                 <div>
                    <label for="kelurahan" class="block mb-2 text-sm font-medium text-gray-700">Kelurahan</label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $marketplace->kelurahan) }}" class="input-field">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-700">Mulai pada pukul</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $marketplace->jam_mulai) }}" class="input-field">
                </div>
                 <div>
                    <label for="jam_berakhir" class="block mb-2 text-sm font-medium text-gray-700">Berakhir pada pukul</label>
                    <input type="time" name="jam_berakhir" value="{{ old('jam_berakhir', $marketplace->jam_berakhir) }}" class="input-field">
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="input-field">{{ old('deskripsi', $marketplace->deskripsi) }}</textarea>
            </div>

            <div x-data="{ imagePreview: '{{ $marketplace->image_path ? asset('storage/' . $marketplace->image_path) : null }}' }">
                <label class="block mb-2 text-sm font-medium text-gray-700">Unggah Marketplace</label>
                 <input type="file" name="gambar" class="hidden" x-ref="image" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                 <div @click="$refs.image.click()" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-green-500">
                    <div class="space-y-1 text-center">
                        <template x-if="!imagePreview">
                            <div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                <p class="mt-1 text-sm text-gray-600">Unggah foto di sini</p>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="mx-auto max-h-40 rounded-lg">
                        </template>
                    </div>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Peta (Klik untuk menandai lokasi)</label>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $marketplace->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $marketplace->longitude) }}">
                <div id="map" class="w-full rounded-lg"></div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            // Default location (Banjarmasin)
            let defaultLat = -3.316694;
            let defaultLng = 114.590111;
            let defaultZoom = 13;

            // If existing data is available, use it
            if (latInput.value && lngInput.value) {
                defaultLat = latInput.value;
                defaultLng = lngInput.value;
                defaultZoom = 17; // Zoom closer if a point is already set
            }

            const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            let marker = null;

            // Add existing marker if data exists
            if (latInput.value && lngInput.value) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
            }

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                latInput.value = e.latlng.lat;
                lngInput.value = e.latlng.lng;
            });
        });
    </script>
@endpush
