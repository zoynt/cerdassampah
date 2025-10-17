@extends('layouts.dashboard')

@section('title', 'Detail Riwayat Setoran')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
<div class="space-y-6">
    {{-- Header Halaman --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('pengelola.riwayat.index') }}" class="text-gray-400 hover:text-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Setoran</h1>
                <p class="text-sm text-gray-500">Lihat rincian lengkap dari transaksi setoran.</p>
            </div>
        </div>
        {{-- Tombol Aksi dipindahkan ke bawah agar lebih rapi --}}
    </div>

    {{-- Ringkasan Transaksi --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">ID Transaksi</p>
                <h2 class="text-lg font-bold text-gray-800">TRX-{{ $transaction->created_at->format('Ymd') }}-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="sm:text-right">
                <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                <h2 class="text-lg font-medium text-gray-800">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</h2>
            </div>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="-ml-1 mr-1.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                    Selesai
                </span>
            </div>
            <div class="sm:text-right">
                <p class="text-sm text-gray-500">Total Harga</p>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($transaction->transaction_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Detail Nasabah & Rincian Setoran --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Detail Nasabah --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6 self-start">
            <h3 class="text-xl font-bold text-gray-800 border-b pb-4 mb-4">Detail Nasabah</h3>
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                <img class="w-16 h-16 rounded-full object-cover flex-shrink-0"
                     {{-- [PERBAIKAN] Menggunakan $transaction --}}
                     src="{{ $transaction->rekening->user->profile_photo_path ? asset('storage/' . $transaction->rekening->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($transaction->rekening->user->name) . '&background=random&color=fff&size=128' }}"
                     alt="Avatar">
                {{-- Info Teks --}}
                <div class="text-sm">
                    {{-- [PERBAIKAN] Menggunakan $transaction --}}
                    <p class="font-bold text-base text-gray-900 leading-relaxed">{{ $transaction->rekening->user->name ?? 'N/A' }}</p>
                    <p class="text-gray-500 leading-relaxed">{{ $transaction->rekening->user->username ?? 'N/A' }}</p>
                    <p class="text-gray-500 leading-relaxed">{{ $transaction->rekening->user->email ?? 'N/A' }}</p>
                    <div class="pt-4">
                        <a href="{{ route('pengelola.nasabah.show', $transaction->rekening->user->id) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600 hover:green-blue-800 transition-colors">
                            <span>Lihat Profil Nasabah</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Rincian Setoran --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 border-b pb-4 mb-4">Rincian Sampah</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="text-left text-sm text-gray-500">
                        <tr>
                            <th class="font-medium p-2">Jenis Sampah</th>
                            <th class="font-medium p-2 text-right">Berat</th>
                            <th class="font-medium p-2 text-right">Harga /kg</th>
                            <th class="font-medium p-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        {{-- PERBAIKAN: Menggunakan @foreach untuk menampilkan semua detail --}}
                        @foreach($transaction->details as $detail)
                        <tr class="border-b">
                            <td class="p-2 font-semibold">{{ $detail->wasteProduct->item_name ?? 'N/A' }}</td>
                            <td class="p-2 text-right">{{ number_format($detail->weight_kg, 2, ',', '.') }} kg</td>
                            <td class="p-2 text-right">Rp {{ number_format($detail->wasteProduct->price_per_kg ?? 0, 0, ',', '.') }}</td>
                            <td class="p-2 text-right font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-gray-800 font-bold">
                        <tr>
                            <td colspan="3" class="p-2 pt-4 text-right">Total</td>
                            <td class="p-2 pt-4 text-right text-lg">Rp {{ number_format($transaction->transaction_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Tombol Aksi --}}
    <div class="flex items-center justify-end gap-3 pt-6 border-t">
        <form action="{{ route('pengelola.riwayat.destroy', $transaction->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');" class="contents">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold text-sm px-4 py-2.5 rounded-lg flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                <span>Hapus Transaksi</span>
            </button>
        </form>
        <a href="{{ route('pengelola.riwayat.cetak', $transaction->uuid) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold text-sm px-4 py-2.5 rounded-lg flex items-center gap-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v3a2 2 0 002 2h6a2 2 0 002-2v-3h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v3h6v-3z" clip-rule="evenodd" /></svg>
            <span>Cetak Struk</span>
        </a>
    </div>
</div>
@endsection