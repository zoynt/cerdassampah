@extends('layouts.dashboard')

@section('title', $bankSampahTerpilih ? 'Riwayat Transaksi - ' . $bankSampahTerpilih->bank_name : 'Riwayat Transaksi')

{{-- Favicon --}}
@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

{{-- Select2 CSS (jika belum ada di layout utama) --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style Select2 agar konsisten */
        .select2-container--default .select2-selection--single { height: 52px !important; border-radius: 0.5rem !important; border: 1px solid #d1d5db !important; background-color: #f9fafb !important; } /* py-3 equivalent */
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 50px !important; padding-left: 1rem !important; color: #1f2937 !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 50px !important; right: 0.5rem !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow b { display: none !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: center; background-size: 1.25em; width: 1.5rem; }
        .select2-container--open .select2-selection--single .select2-selection__arrow { transform: rotate(180deg); }
        .select2-dropdown { border-radius: 0.5rem !important; border: 1px solid #d1d5db !important; }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: #16a34a !important; }
        /* Style select standar agar mirip Select2 */
         .standard-select {
            height: 52px !important; /* py-3 */
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            background-color: #f9fafb !important;
            padding-left: 1rem !important;
            padding-right: 2.5rem !important;
            color: #1f2937 !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25em;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        {{-- Kartu Ringkasan --}}
        <div class="rounded-2xl shadow-lg p-6 bg-gradient-to-br from-green-600 to-teal-600 text-white">
            <h2 class="text-xl font-semibold mb-1">Ringkasan Transaksi</h2>
            <p class="text-sm opacity-80 mb-2">{{ $bankSampahTerpilih ? $bankSampahTerpilih->bank_name : 'Semua Bank Sampah' }}</p>
            <p class="text-4xl font-bold mb-4">{{ $totalTransaksiCount }}</p>
            <p class="text-sm opacity-80 mb-6">Total Transaksi Sesuai Filter</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-yellow-400 p-4 rounded-xl relative">
                    <p class="text-sm font-medium text-yellow-900">Total Saldo</p>
                    <p class="text-xl font-bold text-yellow-900">Rp {{ number_format($waktuSaldoTerakhir, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-400 p-4 rounded-xl relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuMasukTerakhir }}</span>
                    <p class="text-sm font-medium text-green-900">Total Masuk</p>
                    <p class="text-xl font-bold text-green-900">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-500 p-4 rounded-xl text-white relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuKeluarTerakhir }}</span>
                    <p class="text-sm font-medium">Total Keluar</p>
                    <p class="text-xl font-bold">Rp {{ number_format(abs($totalKeluar), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Filter Transaksi --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Transaksi</h3>
            {{-- Form dengan Select2 untuk Bank Sampah --}}
            <form id="filter-form" action="{{ route('digital.riwayat', ['bank' => $bankSampahTerpilih->slug ?? null]) }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bank_slug_selector" class="block text-sm font-medium text-gray-600 mb-1">Bank Sampah</label>
                    {{-- Ganti select biasa dengan select untuk Select2 --}}
                    <select name="bank_slug" id="bank_slug_selector" style="width: 100%;" onchange="window.location.href = this.value;">
                        {{-- Opsi "Semua Bank" dengan value ke route tanpa parameter bank --}}
                        <option value="{{ route('digital.riwayat') }}" @selected(!$bankSampahTerpilih)>Semua Bank Sampah</option>
                        @foreach ($daftarBank as $bank)
                            <option value="{{ route('digital.riwayat', ['bank' => $bank->slug]) }}" @selected($bankSampahTerpilih && $bankSampahTerpilih->id == $bank->id)>{{ $bank->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-600 mb-1">Tipe Transaksi</label>
                    {{-- Select Tipe Transaksi (pakai style standard) --}}
                    <select name="tipe" id="tipe" onchange="this.form.submit()" class="block w-full standard-select focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Tipe</option>
                        <option value="pemasukan" @selected(request('tipe') == 'pemasukan')>Transaksi Masuk</option>
                        <option value="penarikan" @selected(request('tipe') == 'penarikan')>Transaksi Keluar</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Judul Riwayat Transaksi --}}
        <div class="flex justify-between items-center mt-6"> {{-- Tambah margin top --}}
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
        </div>

        {{-- Daftar Transaksi --}}
        <div class="space-y-4"> {{-- Kurangi spacing dari space-y-6 --}}
            @forelse ($semuaTransaksi as $transaksi)
                {{-- ====================================================== --}}
                {{-- [PERBAIKAN] Card Transaksi dengan Badge Status --}}
                {{-- ====================================================== --}}
                <div @class([
                        'p-4 rounded-xl border shadow-sm', // Tambah shadow-sm
                        'flex flex-col sm:flex-row justify-between sm:items-start gap-2', // Layout flex responsif items-start
                        'bg-green-50 border-green-200' => $transaksi->transaction_type == 'pemasukan',
                        'bg-red-50 border-red-200' => $transaksi->transaction_type == 'penarikan',
                        'bg-yellow-50 border-yellow-200' => $transaksi->status == 'Pending', // Highlight Pending
                    ])>
                    {{-- Bagian Kiri: Detail Teks --}}
                    <div class="flex-grow">
                        <p class="font-bold text-gray-800 leading-tight">{{ $transaksi->description }}</p>
                        <p class="text-sm text-gray-500">{{ $transaksi->rekening->bank->bank_name }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $transaksi->created_at->isoFormat('D MMM YYYY, HH:mm') }}</p> {{-- Format Carbon --}}

                        {{-- Tampilkan detail item jika setoran --}}
                        @if($transaksi->details->isNotEmpty())
                            <div class="mt-2 text-xs text-gray-600 border-l-2 border-gray-200 pl-2 space-y-0.5">
                                @foreach($transaksi->details as $detail)
                                    <p> {{ $detail->wasteProduct->item_name ?? 'item' }} ({{ $detail->weight_kg }} kg) </p>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Bagian Kanan: Harga & Status --}}
                    <div class="text-left sm:text-right mt-2 sm:mt-0 flex-shrink-0"> {{-- Alignment & flex-shrink --}}
                        {{-- Harga --}}
                        @if ($transaksi->transaction_type == 'pemasukan')
                            <p class="font-semibold text-lg text-green-600 whitespace-nowrap"> + Rp {{ number_format($transaksi->transaction_amount, 0, ',', '.') }} </p>
                        @else
                            <p class="font-semibold text-lg text-red-600 whitespace-nowrap"> - Rp {{ number_format(abs($transaksi->transaction_amount), 0, ',', '.') }} </p>
                        @endif

                        {{-- Badge Status (di bawah harga) --}}
                        <div class="mt-1">
                            @if($transaksi->status == 'Selesai' || ($transaksi->transaction_type == 'pemasukan' && !$transaksi->status))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"> Selesai </span>
                            @elseif($transaksi->status == 'Gagal')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"> Gagal </span>
                            @elseif($transaksi->status == 'Pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800"> Pending </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800"> {{ $transaksi->status ?? '-' }} </span>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- ====================================================== --}}
                {{-- Akhir Perbaikan Card --}}
                {{-- ====================================================== --}}
            @empty
                <div class="text-center p-6 bg-gray-50 rounded-xl shadow-sm">
                    <p class="text-gray-500">Tidak ada transaksi ditemukan sesuai filter.</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="pt-4">
                {{ $semuaTransaksi->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memuat jQuery & Select2 JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk dropdown bank sampah
            $('#bank_slug_selector').select2({
                // placeholder: "Semua Bank Sampah", // Placeholder diambil dari <option> pertama
                allowClear: false, // Tidak boleh dikosongkan jika sudah ada bank
                minimumResultsForSearch: Infinity // Sembunyikan search box jika tidak banyak bank
            });
        });
    </script>
@endpush