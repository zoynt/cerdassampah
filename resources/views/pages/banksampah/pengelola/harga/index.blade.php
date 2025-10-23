@extends('layouts.dashboard')

@section('title', 'Pengaturan Harga Sampah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

{{-- Menambahkan Aset CSS untuk Select2 dan Style Kustom --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Perbaikan umum untuk Select2 agar menyatu dengan form */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            background-color: #f9fafb !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 1rem !important;
            color: #111827 !important;
        }
        .select2-container--open .select2-dropdown {
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            z-index: 9999 !important; /* Pastikan dropdown di atas */
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.25rem !important;
            border: 1px solid #d1d5db !important;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #16a34a !important; /* Warna hijau */
        }

        /* --- Perbaikan Ikon & Animasi untuk Select2 --- */
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            display: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 1.25em;
            width: 2.5rem; height: 100%; position: absolute; top: 0; right: 0;
            transition: transform 0.2s ease-in-out;
        }
        .select2-container--open .select2-selection--single .select2-selection__arrow {
            transform: rotate(180deg);
        }

        /* --- Animasi untuk Ikon Field Status Awal (Dropdown biasa) --- */
        .select-wrapper svg {
            transition: transform 0.2s ease-in-out;
        }
        .select-wrapper:focus-within svg {
            transform: rotate(180deg);
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Pengaturan Harga Sampah</h1>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-blue-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg></div>
            <div><p class="text-sm text-blue-100">Total Item</p><p class="text-3xl font-bold">{{ $totalItem }}</p></div>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
            <div><p class="text-sm text-green-100">Harga Aktif</p><p class="text-3xl font-bold">{{ $hargaAktif }} <span class="text-2xl font-medium">Item</span></p></div>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
                <path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path><path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zM14 12a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                </svg>
            </div>
            <div><p class="text-sm text-yellow-100">Rata-rata Harga</p><p class="text-3xl font-bold">Rp {{ number_format($rataRataHarga, 0, ',', '.') }}</p></div>
        </div>
    </div>
    
    {{-- Form Accordion Tambah Item Sampah --}}
    <div x-data="{ isOpen: {{ $errors->any() ? 'true' : 'false' }} }" class="bg-white rounded-xl shadow-lg">
        <div @click="isOpen = !isOpen" class="p-6 flex justify-between items-center cursor-pointer hover:bg-gray-100" :class="{ 'border-b': isOpen, 'rounded-xl': !isOpen, 'rounded-t-xl': isOpen }">
            <h2 class="text-lg font-bold text-gray-800">Tambah Item Sampah Baru</h2>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-300" x-bind:class="{ 'rotate-180': isOpen }" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </div>

        <div x-show="isOpen" x-transition>
            <form action="{{ route('pengelola.harga.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    {{-- Baris Kategori & Nama Item --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category-select" class="block mb-2 text-sm font-medium text-gray-700">Kategori Sampah</label>
                            <select id="category-select" name="waste_category_id" required style="width: 100%;">
                                <option></option> {{-- Option kosong untuk placeholder Select2 --}}
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('waste_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('waste_category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="item_name" class="block mb-2 text-sm font-medium text-gray-700">Nama Item Spesifik</label>
                            <input type="text" id="item_name" name="item_name" placeholder="Cth: Botol PET Bening, Kardus Kering" class="w-full h-11 px-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500" required value="{{ old('item_name') }}">
                            @error('item_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Baris Harga & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price_per_kg_display" class="block mb-2 text-sm font-medium text-gray-700">Harga Awal /kg</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><span class="text-gray-500">Rp</span></div>
                                <input type="text" id="price_per_kg_display" placeholder="2.000" class="price-input w-full h-11 pl-10 pr-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50" required>
                                <input type="hidden" name="price_per_kg" id="price_per_kg_value" value="{{ old('price_per_kg') }}">
                            </div>
                             @error('price_per_kg') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status Awal</label>
                            <div class="relative w-full select-wrapper">
                                <select name="status" class="w-full h-11 pl-4 pr-10 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 appearance-none" required>
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                         </div>
                    </div>

                    {{-- Baris Deskripsi (Full Width) --}}
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                        <input type="text" name="description" placeholder="Cth: Hanya botol air mineral tanpa label, bersih, dan kering" class="w-full h-11 px-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50" value="{{ old('description') }}">
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-xl">
                    <button type="submit" class="h-11 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-6 text-center flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        <span>Tambah Item</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Harga Aktif --}}
    <div class="border-t pt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Harga Aktif</h2>
        <div class="space-y-4">
            @forelse($products as $product)
            <div id="item-{{ $product->id }}" class="bg-white rounded-xl shadow-lg transition-all duration-300">
                {{-- Mode Lihat --}}
                <div class="view-mode">
                    <div class="p-6 flex flex-col sm:flex-row sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-xl font-bold text-gray-900">{{ $product->item_name ?? 'N/A' }}</h3>
                                @if($product->status == 'Aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-green-700">{{ $product->category->name ?? 'N/A' }}</span>
                            <p class="text-sm text-gray-500 mt-2">{{ $product->description }}</p>
                        </div>
                        <div class="sm:text-right flex-shrink-0">
                            <p class="text-xl font-bold text-green-600">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}<span class="text-sm text-gray-500 font-medium">/kg</span></p>
                            <div class="mt-2 flex items-center sm:justify-end gap-2">
                                <button onclick="toggleEdit({{ $product->id }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                    <span>Edit</span>
                                </button>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('pengelola.harga.destroy', $product->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $product->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Mode Edit --}}
                <div class="edit-mode hidden">
                    <form action="{{ route('pengelola.harga.update', $product->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="edit-category-{{ $product->id }}" class="block mb-1 text-sm font-medium text-gray-700">Kategori Sampah</label>
                                <select id="edit-category-{{ $product->id }}" name="waste_category_id" required style="width: 100%;">
                                     <option></option>
                                     @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected($product->waste_category_id == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="edit-item-name-{{ $product->id }}" class="block mb-1 text-sm font-medium text-gray-700">Nama Item Spesifik</label>
                                <input type="text" id="edit-item-name-{{ $product->id }}" name="item_name" value="{{ $product->item_name }}" class="w-full h-11 px-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500" required>
                            </div>
                            <div>
                                <label for="price-edit-display-{{$product->id}}" class="block mb-1 text-sm font-medium text-gray-700">Harga Baru /kg</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><span class="text-gray-500">Rp</span></div>
                                    <input type="text" id="price-edit-display-{{$product->id}}" data-target="price-edit-value-{{$product->id}}" value="{{ (int) $product->price_per_kg }}" class="price-input w-full h-11 pl-10 pr-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50" required>
                                    <input type="hidden" name="price_per_kg" id="price-edit-value-{{$product->id}}" value="{{ (int) $product->price_per_kg }}">
                                </div>
                            </div>
                            <div>
                                <label for="edit-description-{{$product->id}}" class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
                                <input type="text" id="edit-description-{{$product->id}}" name="description" value="{{ $product->description }}" class="w-full h-11 px-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50">
                            </div>
                            <div>
                                <label for="edit-status-{{$product->id}}" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
                                <div class="relative">
                                    <select id="edit-status-{{$product->id}}" name="status" class="w-full h-11 pl-4 pr-10 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 appearance-none" required>
                                        <option value="Aktif" @selected($product->status == 'Aktif')>Aktif</option>
                                        <option value="Tidak Aktif" @selected($product->status == 'Tidak Aktif')>Tidak Aktif</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t">
                            <button type="button" onclick="toggleEdit({{ $product->id }})" class="font-medium rounded-lg text-sm px-5 py-2.5 text-center text-gray-700 bg-gray-200 hover:bg-gray-300">Batal</button>
                            <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg><span>Simpan</span></button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500"><p>Anda belum menambahkan item sampah.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memuat jQuery & Select2 --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Memuat SweetAlert --}}

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada form TAMBAH
        $('#category-select').select2({
            placeholder: 'Pilih Kategori Sampah...',
            dropdownParent: $('body') // Atasi masalah z-index jika di dalam modal/accordion
        });

        // --- Fungsi untuk konfirmasi hapus ---
        window.confirmDelete = function(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?', text: "Anda tidak akan bisa mengembalikan data ini!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById(`delete-form-${id}`).submit(); }
            });
        }
        
        // --- Fungsi untuk memformat input harga ---
        function setupPriceInput(displayInput) {
            if (!displayInput) return;
            
            // [PERBAIKAN] Logika untuk menemukan input 'value' yang benar
            const valueInputId = displayInput.dataset.target || displayInput.id.replace('_display', '_value').replace('-display-', '-value-');
            const valueInput = document.getElementById(valueInputId);
            
            if (!valueInput) { 
                console.error('Value input not found for:', displayInput.id, '-> Tried:', valueInputId); 
                return; 
            }
            
            displayInput.addEventListener('input', function(e) {
                let rawValue = e.target.value.replace(/[^0-9]/g, '');
                valueInput.value = rawValue;
                let formattedValue = new Intl.NumberFormat('id-ID').format(rawValue);
                e.target.value = (rawValue === '') ? '' : formattedValue;
            });
            displayInput.dispatchEvent(new Event('input')); // Format nilai awal
        }
        // Terapkan ke SEMUA input harga (tambah & edit)
        document.querySelectorAll('.price-input').forEach(setupPriceInput);

        // --- Fungsi untuk beralih mode lihat/edit & inisialisasi Select2 EDIT ---
        window.toggleEdit = function(id) {
            const item = document.getElementById(`item-${id}`);
            const viewMode = item.querySelector('.view-mode');
            const editMode = item.querySelector('.edit-mode');
            
            viewMode.classList.toggle('hidden');
            editMode.classList.toggle('hidden');

            if (!editMode.classList.contains('hidden')) {
                const editSelect = $(`#edit-category-${id}`);
                if (!editSelect.hasClass("select2-hidden-accessible")) {
                    editSelect.select2({
                        placeholder: 'Pilih Kategori...',
                        dropdownParent: $('body')
                    });
                }
            } else {
                 const editSelect = $(`#edit-category-${id}`);
                 if (editSelect.hasClass("select2-hidden-accessible")) {
                    editSelect.select2('destroy');
                 }
            }
        }
    });
</script>
@endpush