@extends('layouts.dashboard')

@section('title', 'Form Pembayaran Baru')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style untuk Select2 agar memiliki ikon di dalam */
        .select2-container--default .select2-selection--single {
            height: 44px !important; border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important; background-color: #f9fafb !important;
            padding-left: 2.75rem !important; 
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important; padding-left: 0 !important;
        }
        .select2-container--open .select2-dropdown { border-radius: 0.5rem !important; }

        /* --- CSS untuk Ikon & Animasi Panah di SEMUA Select2 --- */
        .select2-container--default .select2-selection--single .select2-selection__arrow b { display: none !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: center; background-size: 1.25em;
            width: 2.5rem; height: 100%; position: absolute; top: 0; right: 0;
            transition: transform 0.2s ease-in-out;
        }
        .select2-container--open .select2-selection--single .select2-selection__arrow {
            transform: rotate(180deg);
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('pengelola.pembayaran.index') }}" class="text-gray-400 hover:text-gray-800 p-1 rounded-full hover:bg-gray-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Pembayaran / Penarikan Saldo</h1>
            <p class="text-sm text-gray-500">Catat transaksi penarikan saldo untuk nasabah.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg">
        <form action="{{ route('pengelola.pembayaran.store') }}" method="POST">
            @csrf
            <div class="p-6 md:p-8 flex flex-col-reverse lg:flex-row gap-8">

                {{-- Kolom Kiri (Form Input) --}}
                <div class="flex-1 space-y-6">
                    <div>
                        <label for="nasabah-select" class="block mb-2 text-sm font-medium text-gray-700">Pilih Nasabah</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                            </div>
                            <select id="nasabah-select" name="user_id" style="width: 100%;" required>
                                <option></option>
                                @foreach($nasabahs as $nasabah)
                                    <option value="{{ $nasabah->id }}" data-saldo="{{ $nasabah->rekeningBankSampah->first()->saldo ?? 0 }}">
                                        {{ $nasabah->name }} ({{ $nasabah->username }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="amount_display" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Penarikan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><span class="text-gray-500 font-semibold">Rp</span></div>
                                <input type="text" id="amount_display" class="formatted-amount w-full h-11 pl-10 pr-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500" placeholder="50.000" required>
                                <input type="hidden" name="amount" id="amount">
                            </div>
                            @error('amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="method-select" class="block mb-2 text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                                </div>
                                <select id="method-select" name="method" style="width: 100%;" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Info Saldo) --}}
                <div class="w-full lg:w-72 bg-gray-50 rounded-lg p-6 flex flex-col justify-center self-start">
                    <h3 class="font-semibold text-gray-700 text-center">Informasi Saldo Nasabah</h3>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Saldo Saat Ini</p>
                        <p id="saldo-display" class="text-4xl font-bold text-green-600">-</p>
                    </div>
                    <div class="mt-4 text-xs text-gray-400 text-center">
                        Pilih nasabah untuk melihat saldo. Pastikan jumlah penarikan tidak melebihi saldo yang tersedia.
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t rounded-b-xl flex justify-end">
                <button type="submit" class="h-11 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-8 text-center flex items-center gap-2 transition-all hover:gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    <span>Simpan Transaksi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const nasabahSelect = $('#nasabah-select');
        const saldoDisplay = $('#saldo-display');

        // Inisialisasi Select2
        nasabahSelect.select2({ placeholder: 'Cari nama atau username nasabah...', dropdownParent: $('body') });
        $('#method-select').select2({ minimumResultsForSearch: Infinity, dropdownParent: $('body') });

        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        // Event listener untuk update saldo
        nasabahSelect.on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const saldo = selectedOption.data('saldo');
            if (saldo !== undefined) {
                saldoDisplay.text(formatRupiah(saldo));
            } else {
                saldoDisplay.text('-');
            }
        });

        // [BARU] Fungsi untuk format angka otomatis pada input jumlah
        function setupFormattedAmountInput(displayInput) {
            if (!displayInput) return;
            const valueInputId = displayInput.id.replace('_display', '');
            const valueInput = document.getElementById(valueInputId);
            if (!valueInput) return;

            displayInput.addEventListener('input', function(e) {
                let rawValue = e.target.value.replace(/[^0-9]/g, '');
                valueInput.value = rawValue;
                if (rawValue) {
                    e.target.value = new Intl.NumberFormat('id-ID').format(rawValue);
                } else {
                    e.target.value = '';
                }
            });
        }

        // Terapkan fungsi ke input jumlah penarikan
        document.querySelectorAll('.formatted-amount').forEach(setupFormattedAmountInput);
    });
</script>
@endpush