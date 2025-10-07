@extends('layouts.dashboard')

@section('title', 'Profil Marketplace')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <h1 class="text-3xl font-bold text-gray-800">Profil Marketplace</h1>
        <a href="{{ route('marketplace.profile.edit') }}" class="w-full sm:w-auto px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm flex items-center justify-center gap-2 transition-colors">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" /></svg>
            <span>Edit Profil</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="h-48 bg-gray-200">
            @if($marketplace->image_path)
                <img src="{{ asset('storage/' . $marketplace->image_path) }}" alt="Foto {{ $marketplace->nama_marketplace }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-green-50 to-teal-100 flex items-center justify-center">
                    <span class="text-gray-400">Tidak ada foto</span>
                </div>
            @endif
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ $marketplace->nama_marketplace }}</h2>
                <span @class(['px-3 py-1 text-xs font-medium rounded-full', 'bg-green-100 text-green-800' => $marketplace->status, 'bg-red-100 text-red-800' => !$marketplace->status])>
                    {{ $marketplace->status ? 'Aktif' : 'Non-aktif' }}
                </span>
            </div>

            {{-- ======================================================= --}}
            {{-- BAGIAN INFORMASI (DIRAPIKAN & DITAMBAH KONTAK) --}}
            {{-- ======================================================= --}}
            <div class="border-t pt-6 space-y-4 text-sm">
                <div class="flex">
                    <p class="w-40 font-semibold text-gray-500 shrink-0">Alamat</p>
                    <p class="text-gray-800">{{ $marketplace->alamat_lengkap }}</p>
                </div>
                <div class="flex">
                    <p class="w-40 font-semibold text-gray-500 shrink-0">Kecamatan / Kelurahan</p>
                    <p class="text-gray-800">{{ $marketplace->kecamatan }}, {{ $marketplace->kelurahan }}</p>
                </div>
                <div class="flex">
                    <p class="w-40 font-semibold text-gray-500 shrink-0">Hari Operasional</p>
                    <p class="text-gray-800">{{ implode(', ', $marketplace->hari_operasional) }}</p>
                </div>
                 <div class="flex">
                    <p class="w-40 font-semibold text-gray-500 shrink-0">Jam Operasional</p>
                    <p class="text-gray-800">{{ \Carbon\Carbon::parse($marketplace->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($marketplace->jam_berakhir)->format('H:i') }}</p>
                </div>
                <div class="flex items-center">
                    <p class="w-40 font-semibold text-gray-500 shrink-0">Telepon / WA</p>
                    <a href="https://wa.me/{{ $marketplace->nomor_telepon }}" target="_blank" class="flex items-center gap-2 text-green-600 hover:underline">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 12c0 1.74.45 3.48 1.34 5l-1.4 5.13 5.26-1.38c1.45.81 3.12 1.25 4.71 1.25h.01c5.46 0 9.9-4.45 9.9-9.9S17.5 2 12.04 2zM9.57 8.52c0-.23.12-.35.24-.35h.58c.12 0 .33.02.5.3.17.28.58 1.4.66 1.5.08.12.12.2.04.33-.08.12-.12.2-.24.32-.12.12-.24.2-.36.32-.12.1-.2.18-.08.35.12.17.52.7 1.1 1.25.78.73 1.45 1 1.65 1.12.2.12.32.1.4-.04.08-.12.35-.4.47-.52.12-.12.24-.1.35-.04.12.04 1.12.53 1.32.6.2.08.32.12.36.18.04.08.02.43-.06.81-.08.37-.52.68-1.2.93-.68.24-1.3.26-1.92.14-.63-.12-1.3-.2-2.32-1.23-1.2-1.2-2-2.65-2.08-3.1-.08-.43.06-.83.06-.83z"/>
                        </svg>
                        <span>{{ $marketplace->nomor_telepon }}</span>
                    </a>
                </div>
            </div>

            <div class="border-t pt-6">
                <p class="font-semibold text-gray-500 text-sm">Deskripsi</p>
                <p class="text-gray-800 mt-1 prose prose-sm max-w-none">{{ $marketplace->deskripsi }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
