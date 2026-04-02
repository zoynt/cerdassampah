@extends('layouts.dashboard')

@section('title', $bankSampahTerpilih ? 'Informasi Akun - ' . $bankSampahTerpilih->bank_name : 'Informasi Akun')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style Select2 (Sudah Benar) */
        .select2-container--default .select2-selection--single { height: 48px !important; border-radius: 0.5rem !important; border: 1px solid #d1d5db !important; background-color: #f9fafb !important; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 46px !important; padding-left: 1rem !important; color: #111827 !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px !important; right: 0.5rem !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow b { display: none !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: center; background-size: 1.25em; width: 1.5rem; }
        .select2-container--open .select2-selection--single .select2-selection__arrow { transform: rotate(180deg); }
        .select2-dropdown { border-radius: 0.5rem !important; border: 1px solid #d1d5db !important; }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: #16a34a !important; }
    </style>
@endpush


@section('content')
<div class="space-y-6">

    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Bank Sampah Digital</h1>

    {{-- Dropdown Pilih Bank Sampah --}}
    <div class="bg-white p-4 rounded-xl shadow">
        <label for="bank_slug_selector" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bank Sampah</label>
        <select name="bank_slug_selector" id="bank_slug_selector" style="width: 100%;"
                onchange="window.location.href = this.value;">
            @forelse ($daftarBank as $bank)
                <option value="{{ route('digital.informasi', ['bank' => $bank->slug]) }}" @selected($bankSampahTerpilih && $bankSampahTerpilih->id == $bank->id)>
                    {{ $bank->bank_name }}
                </option>
            @empty
                <option value="">Anda belum terdaftar di bank sampah manapun</option>
            @endforelse
        </select>
    </div>

    {{-- Konten Utama --}}
    @if($bankSampahTerpilih && $nomorRekening)
    {{-- Kartu Saldo Utama --}}
        <div class="rounded-2xl shadow-lg p-6 bg-gradient-to-br from-green-600 to-teal-600 text-white space-y-6">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center overflow-hidden border-2 border-white/50">
                        @if (Auth::user()->profile_photo_path)
                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil">
                        @else
                            <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar User">
                        @endif
                    </div>
                </div>
                <div class="flex-grow">
                    <p class="text-sm opacity-80">Saldo Tersedia</p>
                    <p class="text-3xl sm:text-4xl font-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                    <p class="text-xs opacity-80 mt-1">Nomor Rekening: {{ $nomorRekening }}</p>
                    @if($bankSampahTerpilih)
                        {{-- PERBAIKAN: Gunakan bank_name --}}
                        <p class="text-xs opacity-80">{{ $bankSampahTerpilih->bank_name }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm">Masuk</p>
                        <p class="font-bold text-lg">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-xs bg-black/20 text-white px-2 py-0.5 rounded-full whitespace-nowrap">{{ $waktuMasukTerakhir }}</span>
                </div>
                <div class="bg-red-500 rounded-xl p-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm">Keluar</p>
                        <p class="font-bold text-lg">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-xs bg-black/20 text-white px-2 py-0.5 rounded-full whitespace-nowrap">{{ $waktuKeluarTerakhir }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('digital.harga', ['bank' => $bankSampahTerpilih->slug]) }}" class="block text-center w-full py-3 bg-amber-400 text-amber-900 font-semibold rounded-lg hover:bg-amber-500 transition shadow">Cek Harga Sampah</a>
                <a href="{{ route('digital.tarik-saldo.form', ['bank' => $bankSampahTerpilih->slug]) }}" class="block text-center w-full py-3 bg-amber-400 text-amber-900 font-semibold rounded-lg hover:bg-amber-500 transition shadow">Tarik Saldo</a>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                     <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">Silahkan datang langsung ke cabang dengan membawa sampah yang dipilah.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Transaksi Terbaru</h2>
            <a href="{{ route('digital.riwayat', ['bank' => $bankSampahTerpilih->slug]) }}" class="text-sm font-medium text-green-600 hover:text-green-800">
                Tampilkan Semua
            </a>
        </div>
        
        {{-- Transaksi Terbaru --}}
        <div class="mt-4">

        <div class="space-y-4">
            @forelse($transaksiTerbaru as $transaksi)
                {{-- ====================================================== --}}
                {{-- [PERBAIKAN] Layout Card Transaksi Kembali ke Awal + Badge --}}
                {{-- ====================================================== --}}
                <div @class([
                        'p-4 rounded-xl border shadow-sm', // Tambah shadow-sm
                        'bg-green-50 border-green-200' => $transaksi->transaction_type == 'pemasukan',
                        'bg-red-50 border-red-200' => $transaksi->transaction_type == 'penarikan',
                        'bg-yellow-50 border-yellow-200' => $transaksi->status == 'Pending', // Highlight Pending
                    ])>
                    <div class="flex justify-between items-start"> {{-- Flex container utama --}}
                        {{-- Bagian Kiri: Detail Teks --}}
                        <div>
                            <p class="font-bold text-gray-800">
                                {{ $transaksi->description }} {{-- Tampilkan description asli --}}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $transaksi->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                            </p>
                            {{-- Tampilkan detail item jika setoran --}}
                            @if ($transaksi->transaction_type == 'pemasukan' && $transaksi->details->isNotEmpty())
                                <div class="mt-1 text-xs text-gray-600 border-l-2 border-gray-200 pl-2 space-y-0.5"> {{-- Sedikit spacing --}}
                                    @foreach($transaksi->details as $detail)
                                        <p> {{ $detail->wasteProduct->item_name ?? 'item' }} ({{ $detail->weight_kg }} kg x Rp {{ number_format($detail->price_per_kg, 0, ',', '.') }}) </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Bagian Kanan: Harga & Status --}}
                        <div class="text-right flex-shrink-0 ml-4"> {{-- Text right & flex-shrink-0 --}}
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
                </div>
                {{-- ====================================================== --}}
                {{-- Akhir Perbaikan Card --}}
                {{-- ====================================================== --}}
            @empty
                <div class="text-center p-6 bg-gray-50 rounded-xl shadow-sm"> <p class="text-gray-500">Belum ada transaksi terbaru.</p> </div>
            @endforelse
        </div>
    </div>
    @else
        {{-- Tampilan jika user belum terdaftar atau tidak ada bank --}}
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Akun</h2>
            <p class="text-gray-500">Anda belum terdaftar sebagai nasabah aktif di bank sampah manapun.</p>
            <a href="{{ route('banksampah-user') }}" class="mt-4 inline-block px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"> Cari Bank Sampah Terdekat </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
    {{-- Memuat jQuery & Select2 JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bank_slug_selector').select2({
                placeholder: "Pilih Bank Sampah",
                allowClear: false,
                minimumResultsForSearch: Infinity
            });
        });
    </script>
@endpush