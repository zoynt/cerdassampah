@extends('layouts.dashboard')

@section('title', 'Tarik Saldo')

@section('content')
    <div class="space-y-6">
        {{-- KARTU SALDO (Sama seperti di halaman informasi) --}}
        <div class="rounded-2xl shadow-lg p-6 bg-gradient-to-br from-green-600 to-teal-600 text-white">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center overflow-hidden border-2 border-white/50">
                        @if (Auth::user()->profile_photo_path)
                            <img class="w-full h-full object-cover"
                                 src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                 alt="Foto Profil">
                        @else
                            <img class="w-full h-full object-cover"
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                                 alt="Avatar User">
                        @endif
                    </div>
                </div>
                <div class="flex-grow">
                    <p class="text-sm opacity-80">Saldo Tersedia</p>
                    <p class="text-3xl sm:text-4xl font-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                    <p class="text-xs opacity-80 mt-1">Nomor Rekening: {{ $nomorRekening }}</p>
                    @if($bankSampahTerpilih)
                        <p class="text-xs opacity-80">{{ $bankSampahTerpilih->nama }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- FORM TARIK SALDO --}}
        <form action="{{ route('digital.tarik-saldo.store') }}" method="POST">
            @csrf
            <div class="bg-white p-6 rounded-2xl shadow-lg space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Tarik Saldo</h2>
                </div>

                {{-- Input Jumlah Penarikan --}}
                <div>
                    <label for="jumlah" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Penarikan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">Rp</span>
                        <input type="number" name="jumlah" id="jumlah"
                               class="block w-full pl-10 pr-4 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="0">
                    </div>
                </div>

                {{-- Input Metode Penarikan --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Metode Penarikan</h3>
                    <div class="relative mb-4">
                        <select name="metode"
                            class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="" disabled selected>Pilih Metode Penarikan (Tunai, E-Wallet dan Bank)</option>
                            <option value="tunai">Tunai (di cabang)</option>
                            <option value="bank">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet (GoPay, OVO, DANA)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                    <div>
                         <input type="text" name="nomor_tujuan"
                               class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Nomor Rekening / Nomor E-Wallet">
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-4 mt-6">
                <a href="{{ route('digital.informasi') }}" class="px-6 py-2.5 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">Ajukan Penarikan</button>
            </div>
        </form>
    </div>
@endsection
