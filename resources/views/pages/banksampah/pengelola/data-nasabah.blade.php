@extends('layouts.dashboard')

@section('title', 'Data Nasabah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Data Nasabah</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            
            {{-- Kartu Total Nasabah --}}
            <div class="bg-blue-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-100">Total Nasabah</p>
                    <p class="text-3xl font-bold">{{ $totalNasabah }}</p>
                </div>
            </div>

            {{-- Kartu Nasabah Aktif --}}
            <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-100">Nasabah Aktif</p>
                    <p class="text-3xl font-bold">{{ $nasabahAktif }}</p>
                </div>
            </div>

            {{-- Kartu Total Saldo Nasabah --}}
            <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg flex items-center gap-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-yellow-100">Total Saldo Nasabah</p>
                    <p class="text-3xl font-bold">
                        Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                
                <div class="relative w-full md:w-auto flex-grow">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full h-11 pl-10 pr-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500"
                        placeholder="Cari nama atau nomor profil...">
                </div>

                <div class="relative w-full md:w-48">
                    <select name="status"
                            class="w-full h-11 pl-4 pr-10 py-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 appearance-none">
                        <option value="Semua Status" @selected(request('status', 'Semua Status') == 'Semua Status')>Semua Status</option>
                        <option value="Aktif" @selected(request('status') == 'Aktif')>Aktif</option>
                        <option value="Tidak Aktif" @selected(request('status') == 'Tidak Aktif')>Tidak Aktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="submit"
                            class="w-1/2 md:w-auto h-11 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Cari
                    </button>
                    <a href="{{ url()->current() }}"
                    class="w-1/2 md:w-auto h-11 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <div class="p-4 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">
                    Data Nasabah
                </h2>
                <!-- <a href="{{ route('pengelola.setoran.create') }}" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>Setoran Baru</span>
                </a> -->
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-green-700 text-white">
                        <tr class="border-green-800">
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nomor Profil</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Total Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @forelse($nasabahs as $nasabah)
                        <tr class="border-b hover:bg-green-50 transition-colors duration-200 cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $nasabah->rekening_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $nasabah->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($nasabah->user->created_at)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $nasabah->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $nasabah->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">Rp {{ number_format(max(0, $nasabah->saldo), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $nasabah->transactions_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('pengelola.nasabah.show', $nasabah->user->id) }}" class="inline-flex items-center gap-1.5 bg-blue-500 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-600 text-xs transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-b">
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                Data nasabah tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($nasabahs->hasPages())
            <div class="p-4 border-t bg-gray-50">
                {{ $nasabahs->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection