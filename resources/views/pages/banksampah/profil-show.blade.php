@extends('layouts.dashboard')

@section('title', 'Profil Bank Sampah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-3xl font-bold text-gray-800">{{ $bank->bank_name }}</h1>
            
            {{-- Tombol Edit (Hanya untuk pemilik) --}}
            @if (Auth::check() && Auth::id() === $bank->user_id)
                <a href="{{ route('pengelola.bank-profil.edit') }}"
                   class="w-full sm:w-auto px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" ...><path ... /></svg>
                    <span>Edit Profil</span>
                </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            {{-- Banner --}}
            <div class="h-48 bg-gray-200">
                @if ($bank->image_path)
                    <img src="{{ asset('storage/' . $bank->image_path) }}" alt="Foto {{ $bank->bank_name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-green-50 to-teal-100 flex items-center justify-center">
                        <span class="text-gray-400">Tidak ada foto</span>
                    </div>
                @endif
            </div>
            
            <div class="p-6 md:p-8 space-y-6">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $bank->bank_name }}</h2>
                    <span @class([
                        'px-3 py-1 text-xs font-medium rounded-full',
                        'bg-green-100 text-green-800' => $bank->is_active,
                        'bg-red-100 text-red-800' => !$bank->is_active,
                    ])>
                        {{ $bank->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </div>

                <div class="border-t pt-6 space-y-4 text-sm">
                    <div class="flex">
                        <p class="w-40 font-semibold text-gray-500 shrink-0">Alamat</p>
                        <p class="text-gray-800">{{ $bank->address }}</p>
                    </div>
                    <div class="flex">
                        <p class="w-40 font-semibold text-gray-500 shrink-0">Kecamatan / Kelurahan</p>
                        <p class="text-gray-800">{{ $bank->district }}, {{ $bank->sub_district }}</p>
                    </div>
                    <div class="flex">
                        <p class="w-40 font-semibold text-gray-500 shrink-0">Hari Operasional</p>
                        <p class="text-gray-800">{{ is_array($bank->operational_days) ? implode(', ', $bank->operational_days) : '' }}</p>
                    </div>
                    <div class="flex">
                        <p class="w-40 font-semibold text-gray-500 shrink-0">Jam Operasional</p>
                        <p class="text-gray-800">{{ $bank->opening_hour }} - {{ $bank->closing_hour }}</p>
                    </div>
                    <div class="flex items-center">
                        <p class="w-40 font-semibold text-gray-500 shrink-0">Telepon / WA</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bank->phone_number) }}" target="_blank"
                           class="flex items-center gap-2 text-green-600 hover:underline">
                            <svg class="w-4 h-4" ...><path ... /></svg>
                            <span>{{ $bank->phone_number }}</span>
                        </a>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <p class="font-semibold text-gray-500 text-sm">Deskripsi</p>
                    <p class="text-gray-800 mt-1 prose prose-sm max-w-none">{{ $bank->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>
        </div>
        
        {{-- Tombol untuk melihat daftar item --}}
        <div class="mt-6 text-center">
             <a href="{{ route('bank-sampah.item.index', $bank->slug) }}"
               class="inline-block px-8 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-800 transition-colors">
                Lihat Item Sampah yang Diterima
            </a>
        </div>
    </div>
@endsection