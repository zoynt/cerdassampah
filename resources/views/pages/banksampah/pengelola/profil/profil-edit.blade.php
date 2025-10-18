@extends('layouts.dashboard')

@section('title', $bank->exists ? 'Edit Profil Bank Sampah' : 'Buat Profil Bank Sampah')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 300px; z-index: 10; }
        .input-field { display: block; width: 100%; padding: 0.75rem 1rem; color: #1f2937; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; transition: border-color 0.2s, box-shadow 0.2s; }
        .input-field:focus { outline: 2px solid transparent; outline-offset: 2px; border-color: #16a34a; box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.25); }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #4b5563; }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    <form action="{{ route('pengelola.bank-profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Rute kita menggunakan PUT --}}

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">
                <p class="font-bold">Oops! Terjadi kesalahan:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif
        
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-green-600 p-6">
                <h1 class="text-2xl font-bold text-white">
                    {{ $bank->exists ? 'Edit Profil Bank Sampah' : 'Lengkapi Profil Bank Sampah Anda' }}
                </h1>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bank_name" class="form-label">Nama Bank Sampah</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $bank->bank_name) }}" class="input-field" required>
                    </div>
                    <div x-data="{
                        open: false,
                        hari: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                        selectedHari: {{ json_encode(old('operational_days', $bank->operational_days ?? [])) }}
                    }">
                        <label class="form-label">Hari Operasional</label>
                        <template x-for="day in selectedHari">
                            <input type="hidden" name="operational_days[]" :value="day">
                        </template>
                        <div class="relative">
                            <button type="button" @click="open = !open" class="input-field text-left w-full flex justify-between items-center">
                                <span x-show="selectedHari.length === 0" class="text-gray-500">Pilih hari...</span>
                                <span x-show="selectedHari.length > 0" x-text="selectedHari.join(', ')" class="truncate"></span>
                                <svg class="w-5 h-5 text-gray-400" ...><path ... /></svg>
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
                    <label for="address" class="form-label">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3" class="input-field">{{ old('address', $bank->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="district" class="form-label">Kecamatan</label>
                        <input type="text" id="district" name="district" value="{{ old('district', $bank->district) }}" class="input-field">
                    </div>
                    <div>
                        <label for="sub_district" class="form-label">Kelurahan</label>
                        <input type="text" id="sub_district" name="sub_district" value="{{ old('sub_district', $bank->sub_district) }}" class="input-field">
                    </div>
                </div>
                <div>
                    <label class="form-label">Peta (Klik atau ketik alamat untuk mencari)</label>
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $bank->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $bank->longitude) }}">
                    <div id="map" class="w-full rounded-lg mt-2"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="opening_hour" class="form-label">Jam Buka</label>
                        <input type="time" id="opening_hour" name="opening_hour" value="{{ old('opening_hour', $bank->opening_hour) }}" class="input-field">
                    </div>
                    <div>
                        <label for="closing_hour" class="form-label">Jam Tutup</label>
                        <input type="time" id="closing_hour" name="closing_hour" value="{{ old('closing_hour', $bank->closing_hour) }}" class="input-field">
                    </div>
                    <div>
                        <label for="phone_number" class="form-label">Nomor Telepon / WA</label>
                        <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $bank->phone_number) }}" class="input-field" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="input-field">{{ old('description', $bank->description) }}</textarea>
                </div>
                
                <div>
                    <label for="status" class="form-label">Status Bank Sampah</label>
                    <select name="status" id="status" class="input-field">
                        <option value="1" @selected(old('status', $bank->is_active) == 1)>Aktif</option>
                        <option value="0" @selected(old('status', $bank->is_active) == 0)>Non-aktif</option>
                    </select>
                </div>

                <div x-data="{ imagePreview: '{{ $bank->image_path ? asset('storage/' . $bank->image_path) : '' }}' }">
                    <label class="form-label">Unggah Foto Bank Sampah (Banner)</label>
                    <input type="file" name="image_path" class="hidden" x-ref="image" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                    <div @click="$refs.image.click()" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-green-500">
                        <div class="space-y-1 text-center">
                            <template x-if="!imagePreview">
                                <div>
                                    <svg class="mx-auto h-12 w-12 text-gray-400" ...><path ... /></svg>
                                    <p class="mt-1 text-sm text-gray-600">Unggah foto di sini</p>
                                </div>
                            </template>
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="mx-auto max-h-40 rounded-lg">
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t mt-6">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    {{-- Skrip untuk Peta Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const alamatInput = document.getElementById('address');
            const kecamatanInput = document.getElementById('district');
            const kelurahanInput = document.getElementById('sub_district');
            let defaultLat = latInput.value ? latInput.value : -3.316694;
            let defaultLng = lngInput.value ? lngInput.value : 114.590111;
            let defaultZoom = latInput.value ? 17 : 13;
            const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            let marker = null;
            if (latInput.value && lngInput.value) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
            }
            function updateMapAndMarker(lat, lng) {
                latInput.value = lat;
                lngInput.value = lng;
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
                map.setView([lat, lng], 17);
            }
            map.on('click', function(e) { updateMapAndMarker(e.latlng.lat, e.latlng.lng); });
            let debounceTimer;
            function geocodeAddress() {
                const address = `${alamatInput.value}, ${kelurahanInput.value}, ${kecamatanInput.value}, Banjarmasin`;
                if (alamatInput.value.trim() === '' && kelurahanInput.value.trim() === '' && kecamatanInput.value.trim() === '') { return; }
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            updateMapAndMarker(data[0].lat, data[0].lon);
                        }
                    })
                    .catch(error => console.error('Geocoding Error:', error));
            }
            function onAddressInput() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(geocodeAddress, 1000);
            }
            alamatInput.addEventListener('input', onAddressInput);
            kecamatanInput.addEventListener('input', onAddressInput);
            kelurahanInput.addEventListener('input', onAddressInput);
        });
    </script>
@endpush