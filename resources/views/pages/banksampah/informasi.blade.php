@extends('layouts.dashboard')

@section('title', 'Informasi Bank Sampah')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Bank Sampah Digital</h1>

            <form id="bank-filter-form" action="{{ route('digital.informasi') }}" method="GET">
                <div class="relative">
                    {{-- PERBAIKAN: Gunakan javascript untuk redirect ke URL slug --}}
                    <select name="bank_slug"
                            onchange="if (this.value) { window.location.href = '{{ url('/bank-sampah/informasi') }}/' + this.value; }"
                            class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">

                        @foreach ($daftarBank as $bank)
                            {{-- PERBAIKAN: 'value' dari option sekarang adalah slug --}}
                            <option value="{{ $bank->slug }}" @selected($bankSampahTerpilih && $bankSampahTerpilih->id == $bank->id)>
                                {{ $bank->bank_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
            </form>
        </div>

        <div class="rounded-2xl shadow-lg p-6 bg-gradient-to-br from-green-600 to-teal-600 text-white space-y-6">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center overflow-hidden border-2 border-white/50">
                        @if (Auth::user()->profile_photo_path)
                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil">
                        @else
                            <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar User">
                        @endif
                    </div>
                </div>
                <div class="flex-grow">
                    <p class="text-sm opacity-80">Saldo Tersedia</p>
                    <p class="text-3xl sm:text-4xl font-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                    <p class="text-xs opacity-80 mt-1">Nomor Rekening: {{ $nomorRekening }}</p>
                    @if($bankSampahTerpilih)
                        {{-- PERBAIKAN: Gunakan bank_name --}}
                        <p class="text-xs opacity-80">{{ $bankSampahTerpilih->bank_name }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm">Masuk</p>
                        <p class="font-bold text-lg">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-xs bg-black/20 text-white px-2 py-0.5 rounded-full whitespace-nowrap">{{ $waktuMasukTerakhir }}</span>
                </div>
                <div class="bg-red-500 rounded-xl p-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm">Keluar</p>
                        <p class="font-bold text-lg">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-xs bg-black/20 text-white px-2 py-0.5 rounded-full whitespace-nowrap">{{ $waktuKeluarTerakhir }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('digital.harga', ['bank' => $bankSampahTerpilih->slug]) }}" class="block text-center w-full py-3 bg-amber-400 text-amber-900 font-semibold rounded-lg hover:bg-amber-500 transition shadow">Cek Harga Sampah</a>
                <a href="{{ route('digital.tarik-saldo.form', ['bank' => $bankSampahTerpilih->slug]) }}" class="block text-center w-full py-3 bg-amber-400 text-amber-900 font-semibold rounded-lg hover:bg-amber-500 transition shadow">Tarik Saldo</a>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                     <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">Silahkan datang langsung ke cabang dengan membawa sampah yang dipilah.</p>
                </div>
            </div>
        </div>

            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Transaksi Terbaru</h2>
                <a href="{{ route('digital.riwayat', ['bank' => $bankSampahTerpilih->slug]) }}" class="text-sm font-medium text-green-600 hover:text-green-800">
                    Tampilkan Semua
                </a>
            </div>

            {{-- PERBAIKAN UTAMA: Tampilkan detail transaksi --}}
            @forelse ($transaksiTerbaru as $transaksi)
                <div @class(['p-4 rounded-xl border', 'bg-green-50 border-green-200' => $transaksi->transaction_type == 'pemasukan', 'bg-red-50 border-red-200' => $transaksi->transaction_type == 'penarikan'])>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-800">{{ $transaksi->description }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y, H:i') }}</p>

                            {{-- Tampilkan detail item jika ini adalah transaksi pemasukan --}}
                            @if($transaksi->transaction_type == 'pemasukan' && $transaksi->details->isNotEmpty())
                                <div class="mt-2 text-xs text-gray-600 border-l-2 border-gray-200 pl-2 space-y-1">
                                    @foreach($transaksi->details as $detail)
                                        <p>
                                            {{ $detail->wasteProduct->item_name }}
                                            ({{ $detail->weight_kg }} kg x Rp {{ number_format($detail->price_per_kg, 0, ',', '.') }})
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- PERBAIKAN: Gunakan transaction_type dan transaction_amount --}}
                        @if ($transaksi->transaction_type == 'pemasukan')
                            <p class="font-semibold text-green-600 whitespace-nowrap">+ Rp {{ number_format($transaksi->transaction_amount, 0, ',', '.') }}</p>
                        @else
                            <p class="font-semibold text-red-600 whitespace-nowrap">- Rp {{ number_format($transaksi->transaction_amount, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <p class="text-gray-500">Belum ada transaksi di bank sampah ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
