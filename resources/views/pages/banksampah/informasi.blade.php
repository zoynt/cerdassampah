@extends('layouts.dashboard')

@section('title', 'Informasi Bank Sampah')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Bank Sampah Digital</h1>

            {{-- ======================================================= --}}
            {{-- PERUBAHAN 1: DROPDOWN MENJADI FORM YANG BERFUNGSI --}}
            {{-- ======================================================= --}}
            <form id="bank-filter-form" action="{{ route('digital.informasi') }}" method="GET">
                <div class="relative">
                    <select name="bank_id" onchange="this.form.submit()"
                        class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Bank Sampah</option>
                        @foreach ($daftarBank as $bank)
                            <option value="{{ $bank->id }}" @selected($bankSampahTerpilih && $bankSampahTerpilih->id == $bank->id)>
                                {{ $bank->nama }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.24a.75.75 0 011.06.04l2.7 2.92 2.7-2.92a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
            </form>
        </div>

        {{-- Kartu Saldo Utama --}}
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
                        <p class="text-xs opacity-80">{{ $bankSampahTerpilih->nama }}</p>
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
                <a href="{{ route('digital.harga') }}" class="block text-center w-full py-3 bg-amber-400 text-amber-900 font-semibold rounded-lg hover:bg-amber-500 transition shadow">Cek Harga Sampah</a>
                <a href="{{ route('digital.tarik-saldo.form', request()->query()) }}" class="block text-center w-full py-3 bg-amber-400 text-amber-900 font-semibold rounded-lg hover:bg-amber-500 transition shadow">Tarik Saldo</a>
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

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Transaksi Terbaru</h2>
                {{-- ======================================================= --}}
                {{-- PERUBAHAN 2: LINK SEKARANG AKTIF --}}
                {{-- ======================================================= --}}
                <a href="{{ route('digital.riwayat') }}" class="text-sm font-medium text-green-600 hover:text-green-800">
                    Tampilkan Semua
                </a>
            </div>

            @forelse ($transaksiTerbaru as $transaksi)
                <div @class(['flex justify-between items-center p-4 rounded-xl border', 'bg-green-50 border-green-200' => $transaksi->tipe == 'pemasukan', 'bg-red-50 border-red-200' => $transaksi->tipe == 'penarikan'])>
                    <div>
                        <p class="font-bold text-gray-800">{{ $transaksi->deskripsi }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $transaksi->detail }}</p>
                    </div>
                    @if ($transaksi->tipe == 'pemasukan')
                        <p class="font-semibold text-green-600">+ Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                    @else
                        <p class="font-semibold text-red-600">- Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                    @endif
                </div>
            @empty
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <p class="text-gray-500">Belum ada transaksi.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
