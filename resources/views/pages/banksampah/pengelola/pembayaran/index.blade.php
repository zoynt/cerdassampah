@extends('layouts.dashboard')

@section('title', 'Riwayat Pembayaran')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Mengatur agar Select2 di dalam filter card terlihat bagus */
        .filter-card .select2-container--default .select2-selection--single {
            height: 44px !important; border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important; background-color: #f9fafb !important;
        }
        .filter-card .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important; padding-left: 1rem !important; color: #111827 !important;
        }

        /* Style untuk Select2 di bar aksi */
        .action-bar .select2-container--default .select2-selection--single {
            height: 40px !important; /* Sesuaikan tinggi */
            border-radius: 0.375rem !important; /* md */
            border: 1px solid #d1d5db !important;
            background-color: #ffffff !important;
        }
        .action-bar .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important; /* Sesuaikan line-height */
            padding-left: 0.75rem !important; /* pl-3 */
            color: #1f2937 !important; /* gray-800 */
        }

        /* Style umum dropdown Select2 */
        .select2-container--open .select2-dropdown { border-radius: 0.5rem !important; border: 1px solid #d1d5db !important; }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: #16a34a !important; }

        /* --- CSS untuk Ikon & Animasi Panah (berlaku untuk SEMUA Select2) --- */
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
{{-- Inisialisasi Alpine.js untuk checkbox --}}
<div class="space-y-6" x-data="{ selectedIds: [], checkAll: false }">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Pembayaran</h1>

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-red-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" ><path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path><path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zM14 12a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path></svg></div>
            <div><p class="text-sm font-medium text-red-100">Total Pengeluaran</p><p class="text-3xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p></div>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
            <div><p class="text-sm font-medium text-green-100">Pembayaran Hari Ini</p><p class="text-3xl font-bold">{{ $pembayaranHariIni }}</p></div>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg></div>
            <div><p class="text-sm font-medium text-yellow-100">Total Transaksi</p><p class="text-3xl font-bold">{{ $totalTransaksiPenarikan }}</p></div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl shadow-lg p-6 filter-card">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Filter</h2>
        <form action="{{ route('pengelola.pembayaran.index') }}" method="GET">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex-1 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full h-11 px-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500" placeholder="Cari berdasarkan nama atau username...">
                </div>
                <div class="w-full md:w-52">
                    <select id="filter-metode" name="metode" style="width: 100%;">
                        <option></option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" @selected(request('metode') == $method)>{{ str_replace('Penarikan via ', '', $method) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-40">
                    <select id="filter-status" name="status" style="width: 100%;">
                        <option></option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="h-11 w-full md:w-auto text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-8 text-center">Cari</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Form Aksi Massal --}}
    <form action="{{ route('pengelola.pembayaran.bulkUpdate') }}" method="POST">
        @csrf

        {{-- Bar Aksi Massal Kondisional --}}
        <div x-show="selectedIds.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="bg-green-50 border border-green-200 rounded-xl shadow-sm py-3 px-5 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 action-bar"> {{-- Tambah class action-bar --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" :checked="selectedIds.length > 0 && selectedIds.length === {{ $payments->count() }}"
                       @change="checkAll = !checkAll; selectedIds = checkAll ? {{ $payments->pluck('id')->toJson() }} : []"
                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50">
                <span class="text-sm font-semibold text-gray-700" x-text="selectedIds.length + ' item dipilih'"></span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select id="bulk-action-select" name="action" required style="width: 200px;">
                    <option></option>
                    <option value="Selesai">Ubah Status Menjadi Selesai</option>
                    <option value="Gagal">Ubah Status Menjadi Gagal</option>
                </select>
                <button type="submit"
                        class="h-10 text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-sm px-5 text-center shadow-sm transition-colors flex items-center justify-center gap-1">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    <span>Terapkan</span>
                </button>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h2>
                <a href="{{ route('pengelola.pembayaran.create') }}" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>Pembayaran Baru</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-green-700 text-white">
                        <tr class="border-green-800">
                            <th class="px-4 py-3">
                                <input type="checkbox" x-model="checkAll" @change="selectedIds = checkAll ? {{ $payments->pluck('id')->toJson() }} : []"
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nama Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">No. Rekening</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="border-b hover:bg-green-50 transition-colors duration-200"
                            :class="{ 'bg-green-50': selectedIds.includes({{ $payment->id }}) }"> {{-- Ubah highlight jadi hijau --}}
                            <td class="px-4 py-4">
                                <input type="checkbox" x-model="selectedIds" value="{{ $payment->id }}"
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50">
                                <template x-if="selectedIds.includes({{ $payment->id }})">
                                     <input type="hidden" name="ids[]" value="{{ $payment->id }}">
                                </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->rekening->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">Rp {{ number_format(abs($payment->transaction_amount), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ str_replace('Penarikan via ', '', $payment->description) ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->status == 'Selesai') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @elseif($payment->status == 'Gagal') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Gagal</span>
                                @else <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $payment->status ?? 'Pending' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->rekening->rekening_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('pengelola.pembayaran.show', $payment->uuid) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-b">
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                Data riwayat pembayaran tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($payments->hasPages())
            <div class="p-4 border-t bg-gray-50">{{ $payments->links() }}</div>
            @endif
        </div>
    </form> {{-- Akhir Form Aksi Massal --}}
</div>
@endsection

@push('scripts')
    {{-- Memuat Alpine.js (pastikan ini ada di layout atau uncomment baris di bawah) --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            // Select2 untuk filter
            $('#filter-metode').select2({ placeholder: 'Semua Metode', allowClear: true, dropdownParent: $('body'), minimumResultsForSearch: Infinity });
            $('#filter-status').select2({ placeholder: 'Semua Status', allowClear: true, dropdownParent: $('body'), minimumResultsForSearch: Infinity });
            
            // Select2 untuk dropdown aksi massal
            $('#bulk-action-select').select2({
                placeholder: 'Pilih Aksi...',
                allowClear: false,
                dropdownParent: $('body'),
                minimumResultsForSearch: Infinity
            });
        });
    </script>
@endpush