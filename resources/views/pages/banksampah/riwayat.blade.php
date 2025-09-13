@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl shadow-lg p-6 bg-gradient-to-br from-green-600 to-teal-600 text-white">
            <h2 class="text-xl font-semibold mb-1">Ringkasan Transaksi</h2>

            @if($bankSampahTerpilih)
                <p class="text-sm opacity-80 mb-2">{{ $bankSampahTerpilih->nama }}</p>
            @else
                <p class="text-sm opacity-80 mb-2">Semua Bank Sampah</p>
            @endif

            <p class="text-4xl font-bold mb-4">{{ $totalTransaksiCount }}</p>
            <p class="text-sm opacity-80 mb-6">Total Transaksi</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-yellow-400 p-4 rounded-xl relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuSaldoTerakhir }}</span>
                    <p class="text-sm font-medium text-yellow-900">Saldo Tersedia</p>
                    <p class="text-xl font-bold text-yellow-900">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-400 p-4 rounded-xl relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuMasukTerakhir }}</span>
                    <p class="text-sm font-medium text-green-900">Masuk</p>
                    <p class="text-xl font-bold text-green-900">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-500 p-4 rounded-xl text-white relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuKeluarTerakhir }}</span>
                    <p class="text-sm font-medium">Keluar</p>
                    <p class="text-xl font-bold">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Data Transaksi</h3>

            {{-- ======================================================= --}}
            {{-- PERUBAHAN TATA LETAK FILTER DI SINI --}}
            {{-- ======================================================= --}}
            <form id="filter-form" action="{{ route('digital.riwayat') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bank_id" class="block text-sm font-medium text-gray-600 mb-1">Bank Sampah</label>
                    <select name="bank_id" id="bank_id" onchange="this.form.submit()"
                        class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Bank Sampah</option>
                        @foreach ($daftarBank as $bank)
                            <option value="{{ $bank->id }}" @selected(request('bank_id') == $bank->id)>{{ $bank->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-600 mb-1">Tipe Transaksi</label>
                    <select name="tipe" id="tipe" onchange="this.form.submit()"
                        class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Tipe</option>
                        <option value="pemasukan" @selected(request('tipe') == 'pemasukan')>Transaksi Masuk</option>
                        <option value="penarikan" @selected(request('tipe') == 'penarikan')>Transaksi Keluar</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="space-y-3">
            @forelse ($semuaTransaksi as $transaksi)
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
                    <p class="text-gray-500">Tidak ada transaksi ditemukan.</p>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $semuaTransaksi->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
