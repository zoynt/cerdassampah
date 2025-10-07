@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl shadow-lg p-6 bg-gradient-to-br from-green-600 to-teal-600 text-white">
            <h2 class="text-xl font-semibold mb-1">Ringkasan Transaksi</h2>

            @if($bankSampahTerpilih)
                <p class="text-sm opacity-80 mb-2">{{ $bankSampahTerpilih->bank_name }}</p>
            @else
                <p class="text-sm opacity-80 mb-2">Semua Bank Sampah</p>
            @endif

            <p class="text-4xl font-bold mb-4">{{ $totalTransaksiCount }}</p>
            <p class="text-sm opacity-80 mb-6">Total Transaksi Sesuai Filter</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-yellow-400 p-4 rounded-xl relative">
                    <p class="text-sm font-medium text-yellow-900">Total Saldo</p>
                    {{-- PERBAIKAN: Tampilkan total saldo dari semua rekening --}}
                    <p class="text-xl font-bold text-yellow-900">Rp {{ number_format($waktuSaldoTerakhir, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-400 p-4 rounded-xl relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuMasukTerakhir }}</span>
                    <p class="text-sm font-medium text-green-900">Total Masuk</p>
                    <p class="text-xl font-bold text-green-900">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-500 p-4 rounded-xl text-white relative">
                    <span class="absolute top-2 right-2 text-xs bg-black/20 text-white px-2 py-0.5 rounded-full">{{ $waktuKeluarTerakhir }}</span>
                    <p class="text-sm font-medium">Total Keluar</p>
                    <p class="text-xl font-bold">Rp {{ number_format(abs($totalKeluar), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Transaksi</h3>
            <form id="filter-form" action="{{ route('digital.riwayat', ['bank' => $bankSampahTerpilih->slug ?? null]) }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bank_slug" class="block text-sm font-medium text-gray-600 mb-1">Bank Sampah</label>
                    <select name="bank_slug" id="bank_slug" onchange="window.location.href = this.value;"
                        class="block w-full pl-4 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="{{ route('digital.riwayat') }}">Semua Bank Sampah</option>
                        @foreach ($daftarBank as $bank)
                            <option value="{{ route('digital.riwayat', ['bank' => $bank->slug]) }}" @selected($bankSampahTerpilih && $bankSampahTerpilih->id == $bank->id)>{{ $bank->bank_name }}</option>
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

                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
                        </div>

        <div class="space-y-6">
            @forelse ($semuaTransaksi as $transaksi)
                <div @class(['p-4 rounded-xl border', 'bg-green-50 border-green-200' => $transaksi->transaction_type == 'pemasukan', 'bg-red-50 border-red-200' => $transaksi->transaction_type == 'penarikan'])>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-800">{{ $transaksi->description }}</p>
                            <p class="text-sm text-gray-500">{{ $transaksi->rekening->bank->bank_name }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y, H:i') }}</p>

                            @if($transaksi->details->isNotEmpty())
                                <div class="mt-2 text-xs text-gray-600 border-l-2 border-gray-200 pl-2 space-y-1">
                                    @foreach($transaksi->details as $detail)
                                        <p>
                                            {{ $detail->wasteProduct->item_name }}
                                            ({{ $detail->weight_kg }} kg)
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>



                        @if ($transaksi->transaction_type == 'pemasukan')
                            <p class="font-semibold text-green-600 whitespace-nowrap">+ Rp {{ number_format($transaksi->transaction_amount, 0, ',', '.') }}</p>
                        @else
                            <p class="font-semibold text-red-600 whitespace-nowrap">- Rp {{ number_format(abs($transaksi->transaction_amount), 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <p class="text-gray-500">Tidak ada transaksi ditemukan sesuai filter.</p>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $semuaTransaksi->links() }}
            </div>
        </div>
    </div>
@endsection
