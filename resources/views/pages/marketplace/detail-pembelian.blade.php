@extends('layouts.dashboard')

@section('title', 'Detail Pembelian')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style> #map { height: 200px; z-index: 10; } </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Detail Pembelian</h1>
        </div>

        {{-- KARTU NO ORDER --}}
        <div class="bg-green-600 text-white flex justify-between items-center px-6 py-3 rounded-2xl shadow-lg">
            <span class="font-medium">No Order</span>
            {{-- Ganti dengan ID transaksi dari database --}}
            <span class="font-bold">#{{ str_pad($penjualan->id, 8, '0', STR_PAD_LEFT) }}</span>
        </div>

        {{-- KARTU INFORMASI TAGIHAN --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg space-y-4">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Informasi Tagihan</h2>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500">Status Transaksi</span>
                <span class="px-3 py-1 bg-green-200 text-green-800 font-medium rounded-full text-xs">
                    {{ $penjualan->status }}
                </span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500">Total Order</span>
                <span class="font-bold text-gray-800">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500">Maks</span>
                <span class="text-gray-800">{{ \Carbon\Carbon::parse($penjualan->created_at)->addDay()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        {{-- KARTU INFORMASI PEMBELIAN --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg space-y-4">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Informasi Pembelian</h2>
            <p class="font-medium text-gray-800">Pembeli : {{ $penjualan->pembeli }}</p>

            <div class="border rounded-xl p-4 flex justify-between items-start">
                <div>
                    {{-- Relasi ke produk diperlukan di sini --}}
                    <p class="font-bold text-gray-900">{{ $penjualan->produk->nama }}</p>
                    <p class="text-sm text-gray-500">Kategori : {{ $penjualan->produk->kategori }}</p>
                    <p class="text-sm text-gray-500">Berat/Bobot : {{ $penjualan->jumlah_terjual }} {{ $penjualan->produk->satuan_berat }}</p>
                    <p class="text-sm text-gray-500">Harga : Rp {{ number_format($penjualan->produk->harga, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 7.756a4.5 4.5 0 100 8.488M7.5 10.5h5.25m-5.25 3h5.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div>
                <p class="font-medium text-gray-800 mb-2">Alamat Pengambilan</p>
                <p class="text-sm text-gray-500">{{ $penjualan->produk->alamat }}</p>
            </div>

            <div>
                <p class="font-medium text-gray-800 mb-2">Peta</p>
                <div id="map" class="w-full rounded-lg"></div>
            </div>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="pt-4">
            <a href="{{ route('marketplace.riwayat') }}" class="block w-full text-center py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                Kembali
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script untuk Peta Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ganti dengan data latitude & longitude dari database
            const lat = -3.316694;
            const lng = 114.590111;
            const map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.marker([lat, lng]).addTo(map);
        });
    </script>
@endpush
