@extends('layouts.dashboard')

@section('title', 'Riwayat Pembayaran')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@push('styles')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Styles Select2 (Hanya untuk dropdown Aksi Massal) */
        .action-bar .select2-container--default .select2-selection--single { height: 40px !important; border-radius: 0.375rem !important; border: 1px solid #d1d5db !important; background-color: #ffffff !important; }
        .action-bar .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px !important; padding-left: 0.75rem !important; color: #1f2937 !important; }
        .select2-container--open .select2-dropdown { border-radius: 0.5rem !important; border: 1px solid #d1d5db !important; }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: #16a34a !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow b { display: none !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: center; background-size: 1.25em; width: 2.5rem; height: 100%; position: absolute; top: 0; right: 0; transition: transform 0.2s ease-in-out; }
        .select2-container--open .select2-selection--single .select2-selection__arrow { transform: rotate(180deg); }

        /* Style untuk select filter standar agar mirip input & punya panah */
        .standard-select {
            /* height diatur oleh h-11 di HTML */
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            background-color: #f9fafb !important;
            padding-left: 1rem !important;
            padding-right: 2.5rem !important;
            color: #111827 !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25em;
        }
    </style>
@endpush

@section('content')
{{-- Inisialisasi Alpine.js dengan DUA set variabel --}}
<div class="space-y-6" x-data="{
    pendingIds: [],
    checkAllPending: false,
    lainIds: [],
    checkAllLain: false,
    
    toggleAllPending() {
        let currentIds = {{ $paymentsPending instanceof \Illuminate\Support\Collection ? $paymentsPending->pluck('id')->toJson() : $paymentsPending->getCollection()->pluck('id')->toJson() }};
        if (this.checkAllPending) { this.pendingIds = [...new Set([...this.pendingIds, ...currentIds])]; }
        else { this.pendingIds = this.pendingIds.filter(id => !currentIds.includes(id)); }
    },
    updateCheckAllPending() {
        let currentIds = {{ $paymentsPending instanceof \Illuminate\Support\Collection ? $paymentsPending->pluck('id')->toJson() : $paymentsPending->getCollection()->pluck('id')->toJson() }};
        if (currentIds.length === 0) { this.checkAllPending = false; return; }
        this.checkAllPending = currentIds.every(id => this.pendingIds.includes(id));
    },

    toggleAllLain() {
        let currentIds = {{ $payments->pluck('id')->toJson() }};
        if (this.checkAllLain) { this.lainIds = [...new Set([...this.lainIds, ...currentIds])]; }
        else { this.lainIds = this.lainIds.filter(id => !currentIds.includes(id)); }
    },
    updateCheckAllLain() {
        let currentIds = {{ $payments->pluck('id')->toJson() }};
        if (currentIds.length === 0) { this.checkAllLain = false; return; }
        this.checkAllLain = currentIds.every(id => this.lainIds.includes(id));
    }
}" x-init="
    $watch('pendingIds', () => updateCheckAllPending());
    $watch('lainIds', () => updateCheckAllLain());
">

    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Pembayaran</h1>

    {{-- Blok Pesan Feedback --}}
    @if (session('success')) <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg" role="alert">{{ session('success') }}</div> @endif
    @if (session('warning')) <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg" role="alert">{{ session('warning') }}</div> @endif
    @if (session('error')) <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">{{ session('error') }}</div> @endif
    @if ($errors->any()) <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert"><p class="font-bold">Oops!</p><ul class="mt-2 list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif

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
                 <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg></div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full h-11 pl-10 pr-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500" placeholder="Cari berdasarkan nama atau username...">
                 </div>
                 {{-- [PERBAIKAN] Tambahkan h-11 dan standard-select. Hapus id="filter-metode" --}}
                 <div class="w-full md:w-52">
                    <select name="metode" class="w-full h-11 standard-select focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Metode</option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" @selected(request('metode') == $method)>{{ str_replace('Penarikan via ', '', $method) }}</option>
                        @endforeach
                    </select>
                 </div>
                 {{-- [PERBAIKAN] Tambahkan h-11 dan standard-select. Hapus id="filter-status" --}}
                 <div class="w-full md:w-48">
                    <select name="status" class="w-full h-11 standard-select focus:ring-green-500 focus:border-green-500">
                        <option value="" @selected(request('status') == '')>Semua Status</option>
                        <option value="Selesai" @selected(request('status') == 'Selesai')>Hanya Selesai</option>
                        <option value="Gagal" @selected(request('status') == 'Gagal')>Hanya Gagal</option>
                        <option value="Pending" @selected(request('status') == 'Pending')>Hanya Pending</option>
                    </select>
                 </div>
                 <div> <button type="submit" class="h-11 w-full md:w-auto text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-8 text-center">Cari</button> <a href="{{ route('pengelola.pembayaran.index') }}" class="inline-block h-11 w-full md:w-auto text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-5 text-center leading-[44px] ml-2">Reset</a> </div>
            </div>
        </form>
    </div>

    {{-- Form 1: Tabel Pembayaran Pending --}}
    @if($paymentsPending->isNotEmpty())
    <form action="{{ route('pengelola.pembayaran.bulkUpdate') }}" method="POST">
        @csrf
        <div x-show="pendingIds.length > 0" x-transition class="bg-yellow-50 border-yellow-200 rounded-xl shadow-sm py-3 px-5 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 action-bar" x-cloak>
            <div class="flex items-center gap-3">
                <input type="checkbox" x-model="checkAllPending" @change="toggleAllPending()" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                <span class="text-sm font-semibold text-gray-700" x-text="pendingIds.length + ' item dipilih'"></span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-auto" style="min-width: 200px;">
                    <select id="bulk-action-select-pending" name="action" required style="width: 100%;">
                        <option></option>
                        <option value="Selesai">Proses & Tandai Selesai</option>
                        <option value="Gagal">Tandai Gagal</option>
                    </select>
                </div>
                <button type="submit" class="h-10 text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-sm px-5 text-center shadow-sm transition-colors flex items-center justify-center gap-1">
                   <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                   <span>Terapkan</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran (Pending)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-yellow-600 text-white">
                        <tr class="border-yellow-700">
                            <th class="px-4 py-3"> <input type="checkbox" x-model="checkAllPending" @change="toggleAllPending()" class="rounded border-gray-300 text-yellow-800 shadow-sm focus:border-yellow-300 focus:ring focus:ring-offset-0 focus:ring-yellow-200 focus:ring-opacity-50 h-5 w-5"> </th>
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
                        @foreach($paymentsPending as $payment)
                        <tr class="border-b hover:bg-yellow-50 transition-colors duration-200" :class="{ 'bg-yellow-50': pendingIds.includes({{ $payment->id }}) }">
                            <td class="px-4 py-4">
                                <input type="checkbox" x-model="pendingIds" value="{{ $payment->id }}" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                                <input type="hidden" name="ids[]" value="{{ $payment->id }}" :disabled="!pendingIds.includes({{ $payment->id }})">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->rekening->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">Rp {{ number_format(abs($payment->transaction_amount), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ str_replace('Penarikan via ', '', $payment->description) ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800"> Pending </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->rekening->rekening_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('pengelola.pembayaran.show', $payment->uuid) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($paymentsPending instanceof \Illuminate\Pagination\LengthAwarePaginator && $paymentsPending->hasPages())
                    <div class="p-4 border-t bg-gray-50"> {{ $paymentsPending->appends(request()->query())->links() }} </div>
                @endif
            </div>
        </div>
    </form>
    @endif
    {{-- Akhir Form/Tabel Pending --}}


    {{-- Form 2: Tabel Pembayaran Selesai & Gagal --}}
    <form action="{{ route('pengelola.pembayaran.bulkUpdate') }}" method="POST">
        @csrf
        <div x-show="lainIds.length > 0" x-transition class="bg-green-50 border-green-200 rounded-xl shadow-sm py-3 px-5 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 action-bar" x-cloak>
            <div class="flex items-center gap-3">
                <input type="checkbox" x-model="checkAllLain" @change="toggleAllLain()" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                <span class="text-sm font-semibold text-gray-700" x-text="lainIds.length + ' item dipilih'"></span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-auto" style="min-width: 200px;">
                    <select id="bulk-action-select-lain" name="action" required style="width: 100%;">
                        <option></option>
                        <option value="Pending">Ubah Status Menjadi Pending</option>
                    </select>
                </div>
                <button type="submit" class="h-10 text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-sm px-5 text-center shadow-sm transition-colors flex items-center justify-center gap-1">
                   <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                   <span>Terapkan</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran (Selesai & Gagal)</h2>
                <a href="{{ route('pengelola.pembayaran.create') }}" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>Pembayaran Baru</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-green-700 text-white">
                        <tr class="border-green-800">
                            <th class="px-4 py-3"> <input type="checkbox" x-model="checkAllLain" @change="toggleAllLain()" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5"> </th>
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
                        <tr class="border-b hover:bg-green-50 transition-colors duration-200" :class="{ 'bg-green-50': lainIds.includes({{ $payment->id }}) }">
                            <td class="px-4 py-4">
                                <input type="checkbox" x-model="lainIds" value="{{ $payment->id }}" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                                <input type="hidden" name="ids[]" value="{{ $payment->id }}" :disabled="!lainIds.includes({{ $payment->id }})">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->rekening->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">Rp {{ number_format(abs($payment->transaction_amount), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ str_replace('Penarikan via ', '', $payment->description) ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{-- [PERBAIKAN] Tambah font-semibold --}}
                                @if($payment->status == 'Selesai') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Selesai</span>
                                @elseif($payment->status == 'Gagal') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Gagal</span>
                                @else <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">{{ $payment->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->rekening->rekening_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('pengelola.pembayaran.show', $payment->uuid) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                            @if(request('status') !== 'Pending')
                            <tr class="border-b"> <td colspan="8" class="px-6 py-10 text-center text-gray-500"> Data riwayat pembayaran tidak ditemukan. </td> </tr>
                            @endif
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($payments->hasPages())
            <div class="p-4 border-t bg-gray-50">{{ $payments->links() }}</div>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
    {{-- Memuat jQuery, Select2 JS, Alpine.js --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        $(document).ready(function() {
            // [PERBAIKAN] HAPUS Inisialisasi Select2 untuk filter
            // $('#filter-metode').select2({ ... });
            // $('#filter-status').select2({ ... });
            
            // Inisialisasi Select2 untuk AKSI MASSAL (Ini sudah benar)
            $('#bulk-action-select-pending').select2({ placeholder: 'Pilih Aksi...', allowClear: false, dropdownParent: $('body'), minimumResultsForSearch: Infinity });
            $('#bulk-action-select-lain').select2({ placeholder: 'Pilih Aksi...', allowClear: false, dropdownParent: $('body'), minimumResultsForSearch: Infinity });
        });
        // Script Alpine.js sudah ada di dalam x-data
        document.addEventListener('alpine:init', () => {
             // Bisa tambahkan logic Alpine custom di sini jika perlu
        })
    </script>
@endpush