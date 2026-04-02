@extends('layouts.dashboard')

@section('title', 'Riwayat Setoran')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Setoran</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-blue-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                fill="currentColor" viewBox="0 0 24 24" >
                <path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path><path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zM14 12a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-blue-100">Total Pemasukan</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-green-100">Setoran Hari Ini</p>
                <p class="text-3xl font-bold">{{ $setoranHariIni }}</p>
            </div>
        </div>

        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-yellow-100">Sampah Terkumpul</p>
                <p class="text-3xl font-bold">
                    {{ $formattedSampahValue }}
                    <span class="text-xl font-medium ml-1">{{ $formattedSampahUnit }}</span>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-4">
        <form action="{{ route('pengelola.riwayat.index') }}" method="GET" class="flex items-center gap-3">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full h-11 pl-10 pr-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500" placeholder="Cari berdasarkan nama atau username nasabah...">
            </div>
            <button type="submit" class="h-11 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-6 text-center">Cari</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <div class="p-4 flex justify-between items-center border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">
                Riwayat Setoran
            </h2>
            {{-- Tombol ini akan mengarah ke halaman form tambah setoran --}}
            <a href="{{ route('pengelola.setoran.create') }}" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                <span>Setoran Baru</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-green-700 text-white">
                    <tr class="border-green-800">
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nama Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Jenis Sampah</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Berat</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($transactions as $detail)
                    <tr class="border-b hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->transaction->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->transaction->rekening->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->transaction->rekening->user->username ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->wasteProduct->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->weight_kg }} kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- Tombol Detail disamakan dengan halaman Data Nasabah --}}
                            <a href="{{ route('pengelola.riwayat.show', $detail->transaction->uuid) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-b">
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            Data riwayat setoran tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($transactions->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection