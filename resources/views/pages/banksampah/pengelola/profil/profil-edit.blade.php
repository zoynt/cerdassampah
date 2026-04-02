@extends('layouts.dashboard')

@section('title', $bank->exists ? 'Edit Profil Bank Sampah' : 'Lengkapi Profil Bank Sampah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 300px;
            z-index: 5; 
        }

        .input-field {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            color: #1f2937;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-field:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: #16a34a;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.25);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    {{ Breadcrumbs::render('pengelola.bank-profil.edit') }}
    <form action="{{ route('pengelola.bank-profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') 

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
                            {{-- ====================================================== --}}
                            {{-- [PERBAIKAN] Mengembalikan ke style Marketplace --}}
                            {{-- ====================================================== --}}
                            <button type="button" @click="open = !open" class="input-field text-left w-full flex justify-between items-center">
                                
                                {{-- 1. Menghapus div wrapper --}}
                                <span x-show="selectedHari.length === 0" class="text-gray-500">Pilih hari...</span>
                                {{-- 2. Mengembalikan class truncate --}}
                                <span x-show="selectedHari.length > 0" x-text="selectedHari.join(', ')" class="truncate"></span>
                                
                                {{-- 3. Mengomentari ikon SVG agar sama --}}
                                {{-- <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg> --}}
                            
                            </button>
                            {{-- ====================================================== --}}
                            {{-- Akhir Perbaikan --}}
                            {{-- ====================================================== --}}

                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-30 w-full mt-1 bg-white border rounded-lg shadow-lg max-h-48 overflow-y-auto" style="display: none;">
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

                <div x-data="mapSearch()">
                    <div>
                        <label for="map-search" class="form-label">Cari Alamat Bank Sampah di Peta</label>
                        <div class="relative">
                            <input type="text" id="map-search" name="address" x-model.debounce.500ms="searchQuery" 
                                   placeholder="Ketik nama jalan, tempat, atau komplek..." class="input-field">
                            
                            <div x-show="results.length > 0" x-transition class="absolute z-20 w-full mt-1 bg-white border rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="result in results" :key="result.place_id">
                                    <div @click="selectLocation(result)" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" x-text="result.display_name"></div>
                                </template>
                            </div>
                            <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $bank->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $bank->longitude) }}">
                        <div id="map" class="w-full rounded-lg"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="district" class="form-label">Kecamatan</label>
                            <input type="text" id="district" name="district" value="{{ old('district', $bank->district) }}" class="input-field">
                        </div>
                        <div>
                            <label for="sub_district" class="form-label">Kelurahan</label>
                            <input type="text" id="sub_district" name="sub_district" value="{{ old('sub_district', $bank->sub_district) }}" class="input-field">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="opening_hour" class="form-label">Mulai pada pukul</label>
                        <input type="time" id="opening_hour" name="opening_hour"
                               value="{{ old('opening_hour', $bank->opening_hour) }}" class="input-field">
                    </div>
                    <div>
                        <label for="closing_hour" class="form-label">Berakhir pada pukul</label>
                        <input type="time" id="closing_hour" name="closing_hour"
                               value="{{ old('closing_hour', $bank->closing_hour) }}" class="input-field">
                    </div>
                    <div>
                        <label for="phone_number" class="form-label">Nomor Telepon / WA</label>
                        <input type="tel" id="phone_number" name="phone_number"
                               value="{{ old('phone_number', $bank->phone_number) }}" class="input-field"
                               placeholder="08xxxxxxxxxx" required>
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
                    <label class="form-label">Unggah Foto Bank Sampah</label>
                    <input type="file" name="image_path" class="hidden" x-ref="image"
                           @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                    <div @click="$refs.image.click()"
                         class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-green-500">
                        <div class="space-y-1 text-center">
                            <template x-if="!imagePreview">
                                <div>
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                         viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
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
                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        {{ $bank->exists ? 'Simpan Perubahan' : 'Buat Profil' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function mapSearch() {
            return {
                searchQuery: '{{ old('address', $bank->address ?? '') }}', 
                results: [],
                loading: false,
                map: null,
                marker: null,

                init() {
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    
                    let defaultLat = latInput.value || -3.316694;
                    let defaultLng = lngInput.value || 114.590111;
                    let defaultZoom = latInput.value ? 17 : 13;

                    this.map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

                    if (latInput.value && lngInput.value) {
                        this.marker = L.marker([defaultLat, defaultLng]).addTo(this.map);
                    }

                    this.map.on('click', (e) => this.updateFormAndMarker(e.latlng.lat, e.latlng.lng));

                    this.$watch('searchQuery', (query) => {
                        if (query.length < 4) {
                            this.results = [];
                            return;
                        }
                        if (document.activeElement.id === 'map-search') {
                            this.searchLocation(query);
                        }
                    });
                },

                searchLocation(query) {
                    this.loading = true;
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5&accept-language=id`;
                    
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Search Error:', error);
                            this.loading = false;
                        });
                },

                selectLocation(location) {
                    this.updateFormAndMarker(location.lat, location.lon);
                    this.results = [];
                    this.searchQuery = location.display_name;
                },

                updateFormAndMarker(lat, lng) {
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    const kecamatanInput = document.getElementById('district');
                    const kelurahanInput = document.getElementById('sub_district');

                    latInput.value = lat;
                    lngInput.value = lng;
                    if (this.marker) {
                        this.marker.setLatLng([lat, lng]);
                    } else {
                        this.marker = L.marker([lat, lng]).addTo(this.map);
                    }
                    this.map.setView([lat, lng], 17);

                    this.searchQuery = 'Mencari alamat...';
                    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`;
                    
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.address) {
                                this.searchQuery = data.display_name || '';
                                kecamatanInput.value = data.address.suburb || data.address.city_district || '';
                                kelurahanInput.value = data.address.village || data.address.quarter || '';
                            } else {
                                this.searchQuery = 'Alamat tidak ditemukan.';
                            }
                        })
                        .catch(error => {
                            console.error('Reverse Geocoding Error:', error);
                            this.searchQuery = 'Gagal mengambil data alamat.';
                        });
                }
            }
        }
    </script>
@endpush