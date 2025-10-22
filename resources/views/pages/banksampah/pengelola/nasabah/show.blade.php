@extends('layouts.dashboard')

@section('title', 'Detail Nasabah - ' . $user->name) {{-- Judul lebih spesifik --}}

{{-- Favicon --}}
@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Nasabah</h1>
        {{-- Tombol Kembali --}}
        <a href="{{ route('pengelola.nasabah.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-green-600 hover:text-green-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H15a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
            Kembali ke Daftar Nasabah
        </a>
    </div>

    {{-- Blok Pesan Feedback (Jika ada redirect dari update status) --}}
    @if (session('success')) <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg" role="alert">{{ session('success') }}</div> @endif
    @if (session('warning')) <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg" role="alert">{{ session('warning') }}</div> @endif
    @if (session('error')) <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">{{ session('error') }}</div> @endif
    @if ($errors->any()) <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert"><p class="font-bold">Oops!</p><ul class="mt-2 list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Profil & Riwayat Singkat --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Kartu Profil Utama --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center">
                    {{-- Avatar --}}
                    <img class="w-28 h-28 mx-auto mb-4 rounded-full shadow-md object-cover" {{-- Tambah object-cover --}}
                         src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=dcfce7&color=166534' }}"
                         alt="Avatar {{ $user->name }}">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>

                    {{-- Badge Status --}}
                    <div class="mt-4">
                        @if($rekening?->status == 'Aktif')
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Aktif
                            </span>
                        @elseif($rekening?->status == 'Tidak Aktif')
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Tidak Aktif
                            </span>
                        @else
                             <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                                Belum Ada Rekening
                             </span>
                        @endif
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Bergabung (Sistem)</dt>
                        <dd class="mt-1 font-semibold text-gray-800">{{ $user->created_at->isoFormat('D MMMM YYYY') }}</dd>
                         {{-- Tampilkan Tanggal Pendaftaran Rekening jika ada --}}
                         @if ($rekening)
                             <dt class="mt-4 text-sm font-medium text-gray-500">Tanggal Daftar Nasabah</dt>
                             <dd class="mt-1 font-semibold text-gray-800">{{ $rekening->created_at->isoFormat('D MMMM YYYY') }}</dd>
                         @endif
                    </dl>
                </div>
            </div>

            {{-- Kartu Riwayat Transaksi Terakhir --}}
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Transaksi Terakhir</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($transaksiTerakhir as $transaksi)
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-b-0 last:pb-0">
                            <div>
                                {{-- Tampilkan tipe transaksi --}}
                                <p class="font-semibold text-gray-700 capitalize">{{ $transaksi->transaction_type == 'pemasukan' ? 'Setoran Sampah' : 'Penarikan Saldo' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaksi->created_at->isoFormat('D MMM YYYY, HH:mm') }}</p>
                            </div>
                            {{-- Tampilkan amount dengan warna berbeda --}}
                            @if($transaksi->transaction_type == 'pemasukan')
                                <p class="font-semibold text-green-600">+ Rp {{ number_format($transaksi->transaction_amount, 0, ',', '.') }}</p>
                            @else
                                <p class="font-semibold text-red-600">- Rp {{ number_format(abs($transaksi->transaction_amount), 0, ',', '.') }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-center text-gray-500 py-4">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Info Lengkap & Aksi --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Kartu Informasi Detail --}}
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b"><h3 class="text-lg font-semibold text-gray-800">Informasi Lengkap</h3></div>
                <dl class="divide-y divide-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $user->phone_number ?? '-' }}</dd></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Alamat</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $user->address ?? '-' }}</dd></div>
                    {{-- Tampilkan No Rekening jika ada --}}
                    @if ($rekening)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">No. Rekening Bank Sampah</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $rekening->rekening_number }}</dd></div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Saldo Saat Ini</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">Rp {{ number_format($rekening?->saldo ?? 0, 0, ',', '.') }}</dd></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Total Transaksi</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $totalTransaksi }} Kali</dd></div>
                </dl>
            </div>

            {{-- ====================================================== --}}
            {{-- [PERBAIKAN] Kartu Aksi Perubahan Status (Tombol Langsung) --}}
            {{-- ====================================================== --}}
            @if ($rekening) {{-- Hanya tampilkan jika nasabah punya rekening di bank ini --}}
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b"><h3 class="text-lg font-semibold text-gray-800">Aksi Pengelola</h3></div>
                <div class="p-6">
                    <form action="{{ route('pengelola.nasabah.updateStatus', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($rekening->status == 'Tidak Aktif')
                            {{-- Tombol Aktifkan --}}
                            <input type="hidden" name="status" value="Aktif">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-md">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /> </svg>
                                Aktifkan Nasabah Ini
                            </button>
                            <p class="mt-2 text-sm text-gray-500">Mengaktifkan nasabah akan mengizinkan mereka melakukan transaksi.</p>

                        @elseif($rekening->status == 'Aktif')
                            {{-- Tombol Nonaktifkan --}}
                            <input type="hidden" name="status" value="Tidak Aktif">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md">
                               <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /> </svg>
                                Nonaktifkan Nasabah Ini
                            </button>
                            <p class="mt-2 text-sm text-gray-500">Menonaktifkan nasabah akan mencegah mereka melakukan transaksi baru.</p>
                        @endif
                    </form>
                </div>
            </div>
            @endif
            {{-- ====================================================== --}}
            {{-- Akhir Kartu Aksi --}}
            {{-- ====================================================== --}}

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('edit-nasabah-form');
        const statusSelect = document.getElementById('status');
        const submitButton = document.getElementById('submit-button');

        // Fungsi untuk mengaktifkan tombol dan mengubah style-nya
        function enableButton() {
            submitButton.disabled = false;
            submitButton.textContent = 'Simpan Perubahan';
            submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitButton.classList.add('bg-green-600', 'hover:bg-green-700');
        }

        // Tambahkan event listener pada dropdown status
        statusSelect.addEventListener('change', function () {
            enableButton();
        });
    });
</script>
@endpush