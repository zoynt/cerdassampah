@extends('layouts.dashboard')

@section('title', 'Detail Nasabah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Nasabah</h1>
        <a href="{{ route('pengelola.nasabah.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-green-600 hover:text-green-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H15a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
            Kembali ke Daftar Nasabah
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1 space-y-6">
            
            {{-- Kartu Profil Utama --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center">
                    <img class="w-28 h-28 mx-auto mb-4 rounded-full shadow-md" src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=dcfce7&color=166534' }}" alt="Avatar">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    
                    {{-- [PERBAIKAN] Status Akun dipindah ke sini menjadi badge --}}
                    <div class="mt-4">
                        @if($rekening?->status == 'Aktif')
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Bergabung</dt>
                        <dd class="mt-1 font-semibold text-gray-800">{{ $user->created_at->isoFormat('D MMMM YYYY') }}</dd>
                    </dl>
                </div>
            </div>

            {{-- [TAMBAH] Kartu baru untuk Riwayat Transaksi Terakhir --}}
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Transaksi Terakhir</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($transaksiTerakhir as $transaksi)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-700">Setoran Sampah</p>
                                <p class="text-xs text-gray-500">{{ $transaksi->created_at->isoFormat('D MMM YYYY, HH:mm') }}</p>
                            </div>
                            <p class="font-semibold text-green-600">+ Rp {{ number_format($transaksi->transaction_amount, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-center text-gray-500 py-4">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-6">
            
            {{-- Kartu Informasi Detail --}}
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b"><h3 class="text-lg font-semibold text-gray-800">Informasi Lengkap</h3></div>
                <dl class="divide-y divide-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $user->phone_number ?? '-' }}</dd></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Alamat</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $user->address ?? '-' }}</dd></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Saldo Saat Ini</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">Rp {{ number_format($rekening?->saldo ?? 0, 0, ',', '.') }}</dd></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-5"><dt class="text-sm font-medium text-gray-500">Total Transaksi</dt><dd class="md:col-span-2 text-sm font-semibold text-gray-900">{{ $totalTransaksi }} Kali</dd></div>
                </dl>
            </div>

            {{-- Kartu Aksi Perubahan Status --}}
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b"><h3 class="text-lg font-semibold text-gray-800">Ubah Status Akun</h3></div>
                <form action="{{ route('pengelola.nasabah.updateStatus', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6">
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Pilih status baru</label>
                    {{-- [PERBAIKAN] Dropdown Status dengan gaya kustom --}}
                    <div class="relative">
                        <select id="status" name="status" class="w-full h-11 pl-4 pr-10 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 appearance-none">
                            <option value="Aktif" @selected($rekening?->status == 'Aktif')>Aktif</option>
                            <option value="Tidak Aktif" @selected($rekening?->status == 'Tidak Aktif')>Tidak Aktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                    <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

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