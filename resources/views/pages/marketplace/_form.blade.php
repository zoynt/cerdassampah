@csrf
<div class="bg-white p-8 rounded-2xl shadow-lg space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Nama Produk --}}
        <div>
            <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $produk->nama ?? '') }}"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
        </div>

        {{-- Kategori Produk --}}
        <div x-data="{
                open: false,
                search: '{{ old('kategori', $produk->kategori ?? '') }}',
                kategori: '{{ old('kategori', $produk->kategori ?? '') }}',
                kategoriList: {{ json_encode($kategoriList) }},
                get filteredKategori() {
                    return this.kategoriList.filter(i => i.toLowerCase().includes(this.search.toLowerCase()));
                }
            }">
            <label for="kategori" class="block mb-2 text-sm font-medium text-gray-700">Kategori Produk</label>
            <input type="hidden" name="kategori" x-model="kategori">
            <div class="relative">
                <input type="text" x-model="search" @click="open = true" @click.away="open=false"
                       placeholder="Pilih atau ketik kategori"
                       class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <div x-show="open" x-transition class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto" style="display: none;">
                    <template x-for="item in filteredKategori" :key="item">
                        <div @click="kategori = item; search = item; open = false;"
                             class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer"
                             x-text="item"></div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Harga --}}
        <div>
            <label for="harga" class="block mb-2 text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga ?? '') }}" placeholder="Contoh: 3000"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
        </div>

        {{-- Stok Barang --}}
        <div>
            <label for="stok" class="block mb-2 text-sm font-medium text-gray-700">Stok Barang</label>
            <input type="number" name="stok" id="stok" value="{{ old('stok', $produk->stok ?? '') }}" placeholder="Jumlah stok saat ini"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- BARIS BARU DENGAN BOBOT, SATUAN, DAN STATUS --}}
    {{-- ======================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="bobot" class="block mb-2 text-sm font-medium text-gray-700">Bobot/Berat per Produk</label>
            <input type="text" name="bobot" id="bobot" value="{{ old('bobot', $produk->bobot ?? '') }}" placeholder="Contoh: 1000"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
        </div>

        <div>
            <label for="satuan_berat" class="block mb-2 text-sm font-medium text-gray-700">Satuan Berat</label>
            <select name="satuan_berat" id="satuan_berat" class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach (['Kilogram', 'Gram', 'Buah', 'Liter'] as $satuan)
                    <option value="{{ $satuan }}" @selected(old('satuan_berat', $produk->satuan_berat ?? 'Kilogram') == $satuan)>{{ $satuan }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status Produk</label>
            <select name="status" id="status" class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach ($statusList as $status)
                    <option value="{{ $status }}" @selected(old('status', $produk->status ?? 'Tersedia') == $status)>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>


    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
    </div>

    {{-- Unggah Gambar --}}
    <div x-data="{ imagePreview: '{{ isset($produk) && $produk->image_path ? asset('storage/' . $produk->image_path) : null }}' }">
        <label class="block mb-2 text-sm font-medium text-gray-700">Gambar Produk</label>
        <input type="file" name="gambar" class="hidden" x-ref="image" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
        <div @click="$refs.image.click()" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-green-500">
            <div class="space-y-1 text-center">
                <template x-if="!imagePreview">
                    <div>
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <p class="mt-1 text-sm text-gray-600">Unggah foto di sini</p>
                    </div>
                </template>
                <template x-if="imagePreview">
                    <img :src="imagePreview" class="mx-auto max-h-40 rounded-lg object-cover">
                </template>
            </div>
        </div>
    </div>

    {{-- Tombol Simpan/Update --}}
    <div class="flex justify-end">
        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
            {{ isset($produk) ? 'Update' : 'Simpan' }}
        </button>
    </div>
</div>
