@extends('layouts.dashboard')

@section('title', 'Form Setoran Sampah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Form Setoran Sampah</h1>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">
                    Input Data Setoran di {{ $bankSampah->name ?? 'Bank Sampah Anda' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Isi detail setoran sampah dari nasabah.</p>
            </div>
            
            <form action="{{ route('pengelola.setoran.store') }}" method="POST">
                @csrf
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- [PERBAIKAN] Dropdown Nama Pengguna dengan gaya kustom --}}
                        <div>
                            <label for="user_id" class="block mb-2 text-sm font-medium text-gray-700">Nama Pengguna</label>
                            <div class="relative">
                                <select id="user_id" name="user_id" placeholder="Ketik atau pilih nasabah..." class="w-full h-11 pl-4 pr-10 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 appearance-none" required>
                                    <option value="">Ketik atau pilih nasabah...</option>
                                    @foreach($nasabahs as $nasabah)
                                        <option value="{{ $nasabah->id }}" data-username="{{ $nasabah->username }}">{{ $nasabah->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="username" name="username" class="w-full h-11 px-4 text-gray-500 bg-gray-200 border border-gray-300 rounded-lg cursor-not-allowed" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Jenis Sampah --}}
                        <div>
                            <label for="jenis_sampah" class="block mb-2 text-sm font-medium text-gray-700">Jenis Sampah</label>
                            <div class="relative">
                                <select id="bank_waste_product_id" name="bank_waste_product_id" placeholder="Ketik atau pilih jenis sampah..." class="w-full h-11 pl-4 pr-10 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 appearance-none" required>
                                    <option value="">Ketik atau pilih jenis sampah...</option>
                                    @foreach($jenisSampahList as $product)
                                        <option value="{{ $product->id }}">{{ $product->category->name ?? 'Nama Item Tidak Ditemukan' }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        {{-- Berat (kg) --}}
                        <div>
                            <label for="berat" class="block mb-2 text-sm font-medium text-gray-700">Berat (kg)</label>
                            <input type="number" id="berat" name="berat" step="0.1" class="w-full h-11 px-4 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required placeholder="Contoh: 1.5">
                        </div>
                    </div>

                    {{-- Harga --}}
                <div>
                    <label for="harga_display" class="block mb-2 text-sm font-medium text-gray-700">Harga</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><span class="text-gray-500">Rp</span></div>
                        {{-- [PERBAIKAN] Dibuat readonly agar tidak bisa diisi manual --}}
                        <input type="text" id="harga_display" class="block w-full h-11 pl-10 pr-4 text-gray-500 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" readonly placeholder="Otomatis terhitung">
                        <input type="hidden" name="harga" id="harga_value">
                    </div>
                </div>
                </div>

                <div class="px-6 pt-4 border-t border-gray-200">
                    <button type="submit" class="w-full h-11 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm text-center flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- [PERBAIKAN UTAMA] Ambil semua data produk langsung dari PHP ---
        const productsData = @json($jenisSampahList->keyBy('id'));

        // --- Referensi ke elemen-elemen form ---
        const userIdSelect = document.getElementById('user_id');
        const usernameInput = document.getElementById('username');
        const productSelect = document.getElementById('bank_waste_product_id');
        const weightInput = document.getElementById('berat');
        const hargaDisplay = document.getElementById('harga_display');
        const hargaValue = document.getElementById('harga_value');

        // --- Fungsi untuk mengisi username ---
        userIdSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            usernameInput.value = selectedOption.getAttribute('data-username') || '';
        });

        // --- Fungsi baru yang lebih andal untuk kalkulasi harga ---
        function calculatePrice() {
            const selectedProductId = productSelect.value;
            const selectedProduct = productsData[selectedProductId];
            
            const pricePerKg = selectedProduct ? parseFloat(selectedProduct.price_per_kg) : 0;
            const weight = parseFloat(weightInput.value) || 0;
            
            const totalHarga = pricePerKg * weight;

            hargaValue.value = totalHarga;
            hargaDisplay.value = new Intl.NumberFormat('id-ID').format(totalHarga);
        }

        // --- Event Listener ---
        productSelect.addEventListener('change', calculatePrice);
        weightInput.addEventListener('input', calculatePrice);
    });
</script>
@endpush