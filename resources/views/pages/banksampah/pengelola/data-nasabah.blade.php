@extends('layouts.dashboard')

@section('title', 'Data Nasabah')

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

        /* Style untuk select filter status standar agar mirip input & punya panah */
        .standard-select {
            height: 44px !important; /* Samakan tinggi dengan input search */
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            background-color: #f9fafb !important;
            padding-left: 1rem !important; /* pl-4 */
            padding-right: 2.5rem !important; /* pr-10 for arrow */
            color: #111827 !important;
            appearance: none !important; /* Hide default arrow */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center; /* position arrow */
            background-size: 1.25em; /* size arrow */
        }
    </style>
@endpush

@section('content')
{{-- Inisialisasi Alpine.js dengan DUA set variabel --}}
<div class="space-y-6" x-data="{
    baruIds: [],
    checkAllBaru: false,
    aktifIds: [],
    checkAllAktif: false,

    // Fungsi untuk tabel NASABAH BARU
    toggleAllBaru() {
        let currentIds = {{ $nasabahBaru instanceof \Illuminate\Support\Collection ? $nasabahBaru->pluck('id')->toJson() : $nasabahBaru->getCollection()->pluck('id')->toJson() }};
        if (this.checkAllBaru) { this.baruIds = [...new Set([...this.baruIds, ...currentIds])]; }
        else { this.baruIds = this.baruIds.filter(id => !currentIds.includes(id)); }
    },
    updateCheckAllBaru() {
        let currentIds = {{ $nasabahBaru instanceof \Illuminate\Support\Collection ? $nasabahBaru->pluck('id')->toJson() : $nasabahBaru->getCollection()->pluck('id')->toJson() }};
        if (currentIds.length === 0) { this.checkAllBaru = false; return; }
        this.checkAllBaru = currentIds.every(id => this.baruIds.includes(id));
    },

    // Fungsi untuk tabel NASABAH AKTIF
    toggleAllAktif() {
        let currentIds = {{ $nasabahs->pluck('id')->toJson() }}; // $nasabahs adalah Paginator
        if (this.checkAllAktif) { this.aktifIds = [...new Set([...this.aktifIds, ...currentIds])]; }
        else { this.aktifIds = this.aktifIds.filter(id => !currentIds.includes(id)); }
    },
    updateCheckAllAktif() {
        let currentIds = {{ $nasabahs->pluck('id')->toJson() }};
        if (currentIds.length === 0) { this.checkAllAktif = false; return; }
        this.checkAllAktif = currentIds.every(id => this.aktifIds.includes(id));
    },

    // Handle Form Submission - Tabel Baru
    submitFormBaru(e) {
        let selectedAction = document.getElementById('bulk-action-select-baru').value;
        if (!selectedAction) {
            e.preventDefault();
            alert('Pilih aksi terlebih dahulu!');
            return;
        }
        // Tambahkan hidden input ids yang dipilih
        let form = e.target;

        // Tambahkan hidden inputs untuk ids yang dipilih
        this.baruIds.forEach(id => {
            let idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
    },

    // Handle Form Submission - Tabel Aktif
    submitFormAktif(e) {
        let selectedAction = document.getElementById('bulk-action-select-aktif').value;
        if (!selectedAction) {
            e.preventDefault();
            alert('Pilih aksi terlebih dahulu!');
            return;
        }
        // Tambahkan hidden input ids yang dipilih
        let form = e.target;

        // Tambahkan hidden inputs untuk ids yang dipilih
        this.aktifIds.forEach(id => {
            let idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
    }
}" x-init="
    $watch('baruIds', () => updateCheckAllBaru());
    $watch('aktifIds', () => updateCheckAllAktif());
">

    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Data Nasabah</h1>

    {{-- Blok Pesan Feedback --}}
    @if (session('success')) <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg" role="alert">{{ session('success') }}</div> @endif
    @if (session('warning')) <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg" role="alert">{{ session('warning') }}</div> @endif
    @if (session('error')) <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">{{ session('error') }}</div> @endif
    @if ($errors->any()) <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert"><p class="font-bold">Oops!</p><ul class="mt-2 list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-blue-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
            <div><p class="text-sm font-medium text-blue-100">Total Nasabah</p><p class="text-3xl font-bold">{{ $totalNasabah }}</p></div>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p class="text-sm font-medium text-green-100">Nasabah Aktif</p><p class="text-3xl font-bold">{{ $nasabahAktif }}</p></div>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
            <div><p class="text-sm font-medium text-yellow-100">Total Saldo Nasabah</p><p class="text-3xl font-bold">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p></div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl shadow-lg p-6 filter-card">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Filter</h2>
        <form action="{{ route('pengelola.nasabah.index') }}" method="GET">
            <div class="flex flex-col md:flex-row items-center gap-4">
                 <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg></div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full h-11 pl-10 pr-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500" placeholder="Cari nama atau nomor profil...">
                 </div>
                 <div class="w-full md:w-48">
                    <select name="status" class="w-full standard-select focus:ring-green-500 focus:border-green-500">
                        <option value="" @selected(request('status') == '')>Semua Status</option>
                        <option value="Aktif" @selected(request('status') == 'Aktif')>Hanya Aktif</option>
                        <option value="Tidak Aktif" @selected(request('status') == 'Tidak Aktif')>Hanya Tidak Aktif</option>
                        <option value="Pending" @selected(request('status') == 'Pending')>Hanya Pending</option>
                    </select>
                 </div>
                 <div> <button type="submit" class="h-11 w-full md:w-auto text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-8 text-center">Cari</button> <a href="{{ route('pengelola.nasabah.index') }}" class="inline-block h-11 w-full md:w-auto text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-5 text-center leading-[44px] ml-2">Reset</a> </div>
            </div>
        </form>
    </div>

    {{-- Form 1: Tabel Data Nasabah Baru (Pending) --}}
    @if($nasabahBaru->isNotEmpty())
    <form action="{{ route('pengelola.nasabah.bulkUpdateStatus') }}" method="POST" @submit="submitFormBaru">
        @csrf
        {{-- Bar Aksi Massal (Nasabah Baru - Kuning) --}}
        <div x-show="baruIds.length > 0" x-transition class="bg-yellow-50 border-yellow-200 rounded-xl shadow-sm py-3 px-5 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 action-bar" x-cloak>
            <div class="flex items-center gap-3">
                <input type="checkbox" x-model="checkAllBaru" @change="toggleAllBaru()" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                <span class="text-sm font-semibold text-gray-700" x-text="baruIds.length + ' item dipilih'"></span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-auto" style="min-width: 200px;">
                    <select id="bulk-action-select-baru" name="action" required style="width: 100%;">
                        <option></option>
                        <option value="Aktif">Aktifkan Nasabah Terpilih</option>
                        <option value="Tidak Aktif">Tolak Nasabah Terpilih</option> {{-- 'Tidak Aktif' sebagai status "tolak" --}}
                    </select>
                </div>
                <button type="submit" class="h-10 text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-sm px-5 text-center shadow-sm transition-colors flex items-center justify-center gap-1">
                   <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                   <span>Terapkan</span>
                </button>
            </div>
        </div>

        {{-- Tabel Nasabah Baru --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Data Nasabah Baru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-yellow-600 text-white">
                        <tr class="border-yellow-700">
                            <th class="px-4 py-3"> <input type="checkbox" x-model="checkAllBaru" @change="toggleAllBaru()" class="rounded border-gray-300 text-yellow-800 shadow-sm focus:border-yellow-300 focus:ring focus:ring-offset-0 focus:ring-yellow-200 focus:ring-opacity-50 h-5 w-5"> </th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nomor Rekening</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabahBaru as $rekening)
                        <tr class="border-b hover:bg-yellow-50 transition-colors duration-200" :class="{ 'bg-yellow-50': baruIds.includes({{ $rekening->id }}) }">
                            <td class="px-4 py-4">
                                <input type="checkbox" x-model="baruIds" value="{{ $rekening->id }}" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rekening->rekening_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rekening->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rekening->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"> Pending </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('pengelola.nasabah.show', $rekening->user_id) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Tampilkan pagination jika $nasabahBaru adalah paginator (saat filter 'Pending') --}}
                @if ($nasabahBaru instanceof \Illuminate\Pagination\LengthAwarePaginator && $nasabahBaru->hasPages())
                    <div class="p-4 border-t bg-gray-50"> {{ $nasabahBaru->appends(request()->query())->links() }} </div>
                @endif
            </div>
        </div>
    </form>
    @endif
    {{-- Akhir Form/Tabel Nasabah Baru --}}


    {{-- Form 2: Tabel Data Nasabah (Aktif & Tidak Aktif) --}}
    <form action="{{ route('pengelola.nasabah.bulkUpdateStatus') }}" method="POST" @submit="submitFormAktif">
        @csrf
        {{-- Bar Aksi Massal (Nasabah Lama) --}}
        <div x-show="aktifIds.length > 0" x-transition class="bg-green-50 border-green-200 rounded-xl shadow-sm py-3 px-5 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 action-bar" x-cloak>
            <div class="flex items-center gap-3">
                <input type="checkbox" x-model="checkAllAktif" @change="toggleAllAktif()" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                <span class="text-sm font-semibold text-gray-700" x-text="aktifIds.length + ' item dipilih'"></span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-auto" style="min-width: 200px;">
                    <select id="bulk-action-select-aktif" name="action" required style="width: 100%;">
                        <option></option>
                        <option value="Aktif">Aktifkan Nasabah Terpilih</option>
                        <option value="Tidak Aktif">Nonaktifkan Nasabah Terpilih</option>
                    </select>
                </div>
                <button type="submit" class="h-10 text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-sm px-5 text-center shadow-sm transition-colors flex items-center justify-center gap-1">
                   <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                   <span>Terapkan</span>
                </button>
            </div>
        </div>

        {{-- Tabel Data Nasabah (Aktif & Tidak Aktif) --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Data Nasabah</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-green-700 text-white">
                        <tr class="border-green-800">
                            <th class="px-4 py-3"> <input type="checkbox" x-model="checkAllAktif" @change="toggleAllAktif()" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5"> </th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nomor Rekening</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Total Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nasabahs as $rekening) {{-- Loop $nasabahs (Aktif & Tidak Aktif) --}}
                        <tr class="border-b hover:bg-green-50 transition-colors duration-200" :class="{ 'bg-green-50': aktifIds.includes({{ $rekening->id }}) }">
                            <td class="px-4 py-4">
                                <input type="checkbox" x-model="aktifIds" value="{{ $rekening->id }}" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rekening->rekening_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rekening->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rekening->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rekening->status == 'Aktif')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"> Aktif </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> Tidak Aktif </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">Rp {{ number_format(max(0, $rekening->saldo), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $rekening->transactions_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('pengelola.nasabah.show', $rekening->user_id) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                            @if(request('status') !== 'Pending')
                            <tr class="border-b"> <td colspan="8" class="px-6 py-10 text-center text-gray-500"> Data nasabah terdaftar tidak ditemukan. </td> </tr>
                            @endif
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($nasabahs->hasPages())
            <div class="p-4 border-t bg-gray-50"> {{ $nasabahs->links() }} </div>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
    {{-- Memuat jQuery, Select2 JS, Alpine.js --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Pastikan Alpine.js dimuat di layout utama Anda SEBELUM @push('scripts') --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk dropdown aksi
            $('#bulk-action-select-baru').select2({
                placeholder: 'Pilih Aksi...',
                allowClear: false,
                dropdownParent: $('body'),
                minimumResultsForSearch: Infinity,
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    return $('<span>' + data.text + '</span>');
                }
            });

            $('#bulk-action-select-aktif').select2({
                placeholder: 'Pilih Aksi...',
                allowClear: false,
                dropdownParent: $('body'),
                minimumResultsForSearch: Infinity,
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    return $('<span>' + data.text + '</span>');
                }
            });
        });
        // Script Alpine.js sudah ada di dalam x-data
        document.addEventListener('alpine:init', () => {
             // Logic Alpine custom bisa ditambahkan di sini jika perlu
        })
    </script>
@endpush
