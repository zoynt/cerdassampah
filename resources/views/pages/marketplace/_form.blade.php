<script>
    function imageUploaderData(existingImages = []) {
        return {
            existingImages: existingImages,
            newImagePreviews: [],
            imagesToDelete: [], // Array untuk menyimpan path gambar yang akan dihapus

            handleFileSelect(event) {
                // Alur kerja "Ganti": Saat memilih file baru, preview akan menampilkan HANYA file-file baru tersebut.
                this.newImagePreviews = []; 
                for (let i = 0; i < event.target.files.length; i++) {
                    this.newImagePreviews.push({ url: URL.createObjectURL(event.target.files[i]), path: null });
                }
            },
            
            removeImage(index) {
                // Tentukan array mana yang sedang aktif ditampilkan
                let activeArray = this.newImagePreviews.length > 0 ? this.newImagePreviews : this.existingImages;
                const removedImage = activeArray[index];

                // Jika gambar yang dihapus adalah gambar lama (punya 'path'), catat path-nya untuk dihapus di server.
                if (removedImage && removedImage.path) {
                    this.imagesToDelete.push(removedImage.path);
                }

                // Hapus gambar dari array aktif yang sedang ditampilkan
                activeArray.splice(index, 1);
            },

            get allImages() {
                // Menentukan gambar mana yang akan ditampilkan di preview
                return this.newImagePreviews.length > 0 ? this.newImagePreviews : this.existingImages;
            }
        }
    }
</script>

@php
    // Menyiapkan semua data awal yang dibutuhkan oleh Alpine.js
    $alpineData = [
        'categoryName' => old('kategori', optional($produk->category)->name ?? ''),
        'categoryList' => $kategoriList,
        'existingImages' => $produk->images->map(fn($img) => [
            'url' => asset('storage/' . $img->image_path),
            'path' => $img->image_path // Path ini penting untuk proses hapus
        ])
    ];
@endphp
@csrf
<div class="bg-white p-8 rounded-2xl shadow-lg space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Nama Produk --}}
        <div>
            <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $produk->name ?? '') }}"
                placeholder="Masukkan nama produk"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required>
        </div>

        {{-- Kategori Produk --}}
        <div x-data="{
                open: false,
                search: '{{ old('kategori', optional($produk->category)->name ?? '') }}',
                kategori: '{{ old('kategori', optional($produk->category)->name ?? '') }}',
                kategoriList: {{ Js::from($kategoriList) }},
                get filteredKategori() {
                    if (!this.search) return this.kategoriList;
                    return this.kategoriList.filter(i => i.toLowerCase().includes(this.search.toLowerCase()));
                }
            }">
            <label for="kategori" class="block mb-2 text-sm font-medium text-gray-700">Kategori Produk</label>
            <input type="hidden" name="kategori" x-model="kategori">
            <div class="relative">
                <input type="text" id="kategori" x-model="search" @click="open = true" @click.away="open=false"
                    placeholder="Pilih atau ketik kategori"
                    class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <div x-show="open" x-transition
                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                    style="display: none;">
                    <template x-for="item in filteredKategori" :key="item">
                        <div @click="kategori = item; search = item; open = false;"
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer" x-text="item"></div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- ... sisa input harga, stok, dll ... --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Harga --}}
        <div>
            <label for="harga" class="block mb-2 text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="harga" id="harga"
                value="{{ old('harga', $produk->exists ? (float) $produk->price : '') }}" placeholder="Contoh : 1000"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required>
        </div>

        {{-- Stok Barang --}}
        <div>
            <label for="stok" class="block mb-2 text-sm font-medium text-gray-700">Stok Barang</label>
            <input type="number" name="stok" id="stok"
                value="{{ old('stok', $produk->exists ? (float) $produk->stock : '') }}"
                placeholder="Jumlah stok saat ini"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="bobot" class="block mb-2 text-sm font-medium text-gray-700">Bobot/Berat per
                Produk</label>
            <input type="text" name="bobot" id="bobot"
                value="{{ old('bobot', $produk->exists ? (float) $produk->weight_per_item : '') }}"
                placeholder="Contoh: 10"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required>
        </div>

        <div>
            <label for="satuan_berat" class="block mb-2 text-sm font-medium text-gray-700">Satuan
                Berat</label>
            <select name="satuan_berat" id="satuan_berat"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach (['Kilogram', 'Gram', 'Buah', 'Liter'] as $satuan)
                    <option value="{{ $satuan }}" @selected(old('satuan_berat', $produk->selling_unit ?? 'Kilogram') == $satuan)>{{ $satuan }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status Produk</label>
            <select name="status" id="status"
                class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach ($statusList as $status)
                    <option value="{{ $status }}" @selected(old('status', $produk->exists ? ($produk->status == 'available' ? 'Tersedia' : 'Habis') : 'Tersedia') == $status)>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('deskripsi', $produk->description ?? '') }}</textarea>
    </div>
    
    {{-- Gambar Produk dengan x-data terisolasi --}}
    <div x-data="imageUploaderData({{ Js::from($produk->images->map(fn($img) => ['url' => asset('storage/' . $img->image_path), 'path' => $img->image_path])) }})">
        <h3 class="block mb-2 text-sm font-medium text-gray-700">Gambar Produk</h3>
        <input type="file" name="gambar[]" multiple class="hidden" x-ref="imageInput" @change="handleFileSelect">

        {{-- [PENTING] Hidden input untuk melacak gambar yang dihapus --}}
        <template x-for="path in imagesToDelete" :key="path">
            <input type="hidden" name="images_to_delete[]" :value="path">
        </template>
        
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
            <template x-for="(image, index) in allImages" :key="index">
                <div class="aspect-square bg-gray-100 rounded-lg p-1 relative">
                    <img :src="image.url" alt="Product thumbnail" class="w-full h-full object-contain rounded-md">
                    <button type="button" @click.prevent="removeImage(index)" 
                            class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-lg font-bold hover:bg-red-700 transition-transform transform hover:scale-110 focus:outline-none">
                        &times;
                    </button>
                    <template x-if="index === 0">
                        <div class="absolute top-1 left-1 bg-green-600 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded-full">Utama</div>
                    </template>
                </div>
            </template>
            <button type="button" @click="$refs.imageInput.click()"
                class="aspect-square bg-gray-50 rounded-lg flex flex-col items-center justify-center border-2 border-dashed border-gray-300 text-gray-500 hover:border-green-500 hover:text-green-600 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span class="text-xs mt-1">Tambah Foto</span>
            </button>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit"
            class="w-full sm:w-auto px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
            {{ $produk->exists ? 'Update Produk' : 'Simpan Produk' }}
        </button>
    </div>
</div>
