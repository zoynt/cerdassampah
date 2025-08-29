@extends('layouts.dashboard')

@section('title', 'Histori Laporan')

@section('content')

    <div class="bg-white rounded-xl shadow-md p-6 sm:p-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">
            Histori Laporan
        </h1>

        <form action="{{ route('laporan.history') }}" method="GET" class="mb-6">
            <label for="search" class="block text-base font-semibold text-gray-600">Data</label>
            <div class="relative mt-2">
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Cari berdasarkan alamat atau status...">
                <button type="submit" class="absolute inset-y-0 right-0 px-4 text-gray-500 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-green-700 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm rounded-l-lg">No</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Lengkap</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Alamat</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Foto</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm rounded-r-lg">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($reports as $report)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="text-left py-3 px-4">
                                {{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                            <td class="text-left py-3 px-4">{{ $report->name }}</td>
                            <td class="text-left py-3 px-4">{{ $report->email }}</td>
                            <td class="text-left py-3 px-4 w-1/4">{{ Str::limit($report->address, 50) }}</td>
                            <td class="text-left py-3 px-4">
                                {{ \Carbon\Carbon::parse($report->waktu_lapor)->isoFormat('D MMMM YYYY') }}</td>
                            <td class="text-left py-3 px-4">
                                <a href="{{ asset('storage/' . $report->image) }}" target="_blank"
                                    class="text-blue-500 hover:underline font-semibold">Lihat Foto</a>
                            </td>
                            <td class="text-left py-3 px-4">
                                @if ($report->status == 'pending')
                                    <span
                                        class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs font-medium">Pending</span>
                                @elseif($report->status == 'proses')
                                    <span
                                        class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs font-medium">Proses</span>
                                @else
                                    <span
                                        class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs font-medium">Diterima</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6">
                                <p class="text-gray-500">Anda belum membuat laporan, atau data tidak ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $reports->appends(['search' => $search])->links() }}
        </div>
    </div>

@endsection
