@extends('layouts.dashboard')

@section('title', 'Harga Sampah')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Harga Sampah</h1>

        {{-- FILTER --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter</h3>
            {{-- PERBAIKAN: Form sekarang hanya untuk pencarian teks --}}
            <form action="{{ route('digital.harga', ['bank' => $bankTerpilih->slug ?? null]) }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                    {{-- Filter Bank Sampah (Dropdown dengan redirect JS) --}}
                    <div class="lg:col-span-1">
                        <label for="bank_slug" class="block text-sm font-medium text-gray-600 mb-1">Bank Sampah</label>
                        {{-- PERBAIKAN: Menggunakan onchange untuk redirect ke URL slug --}}
                        <select id="bank_slug" name="bank_slug"
                                onchange="window.location.href = this.value;"
                                class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">

                            {{-- Opsi untuk melihat semua bank --}}
                            <option value="{{ route('digital.harga') }}">Semua Bank Sampah</option>

                            @foreach ($daftarBank as $bank)
                                {{-- PERBAIKAN: Value adalah URL lengkap dengan slug --}}
                                <option value="{{ route('digital.harga', ['bank' => $bank->slug]) }}" @selected(isset($bankTerpilih) && $bankTerpilih->id == $bank->id)>
                                    {{ $bank->bank_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Nama Sampah (Input teks biasa) --}}
                    <div class="lg:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-1">Nama Sampah</label>
                        <input type="text" id="search" name="search" placeholder="Cari jenis sampah..."
                            value="{{ request('search') }}"
                            class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">Cari</button>
                        <a href="{{ route('digital.harga') }}" class="w-full text-center px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- DAFTAR HARGA --}}
        <div class="space-y-6">
            @forelse ($hargaDikelompokkan as $kategori => $items)
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">{{ $kategori }}</h2>
                    <div class="space-y-3">
                        @foreach ($items as $item)
                            <div class="bg-gray-50 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    {{-- PERBAIKAN: Gunakan item_name --}}
                                    <p class="font-bold text-gray-800">{{ $item->item_name }}</p>
                                    {{-- Tambahan: Tampilkan nama bank agar lebih jelas --}}
                                    <p class="text-sm text-gray-500">{{ $item->bank->bank_name }}</p>
                                </div>
                                <p class="font-semibold text-green-700 text-lg whitespace-nowrap">
                                    {{-- PERBAIKAN: Gunakan price_per_kg --}}
                                    Rp {{ number_format($item->price_per_kg, 0, ',', '.') }} / kg
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                 <div class="text-center p-10 bg-white rounded-2xl shadow-lg">
                     <p class="text-gray-500">Tidak ada data harga sampah yang ditemukan sesuai filter Anda.</p>
                 </div>
            @endforelse
        </div>
    </div>
@endsection
