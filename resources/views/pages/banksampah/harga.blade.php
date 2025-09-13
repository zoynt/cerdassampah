@extends('layouts.dashboard')

@section('title', 'Harga Sampah')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Harga Sampah</h1>

        {{-- FILTER --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter</h3>
            <form action="{{ route('digital.harga') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Filter Bank Sampah --}}
                    <div>
                        <select name="bank_id" onchange="this.form.submit()"
                            class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Bank Sampah</option>
                            @foreach ($daftarBank as $bank)
                                <option value="{{ $bank->id }}" @selected(request('bank_id') == $bank->id)>
                                    {{ $bank->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Filter Jenis Sampah (bisa Anda kembangkan nanti) --}}
                    <div>
                        <input type="text" name="search" placeholder="Cari jenis sampah..."
                               value="{{ request('search') }}"
                               class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">Cari</button>
                </div>
            </form>
        </div>

        {{-- DAFTAR HARGA --}}
        <div class="space-y-6">
            @forelse ($hargaDikelompokkan as $kategori => $items)
                <div class="bg-green-600 p-6 rounded-2xl shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4">{{ $kategori }}</h2>
                    <div class="space-y-3">
                        @foreach ($items as $item)
                            <div class="bg-gray-100 p-4 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $item->nama_item }}</p>
                                    <p class="text-sm text-gray-500">per kg</p>
                                </div>
                                <p class="font-semibold text-green-700 text-lg">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                 <div class="text-center p-10 bg-white rounded-2xl shadow-lg">
                    <p class="text-gray-500">Tidak ada data harga sampah yang ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
