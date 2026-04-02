@extends('layouts.dashboard')

@section('title', 'Detail Riwayat Pembayaran')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
<div class="space-y-6">
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('pengelola.pembayaran.index') }}" class="text-gray-400 hover:text-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pembayaran</h1>
                <p class="text-sm text-gray-500">Lihat rincian lengkap dari transaksi pembayaran.</p>
            </div>
        </div>
    </div>

    {{-- Ringkasan Transaksi --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">ID Transaksi</p>
                <h2 class="text-lg font-bold text-gray-800">TRX-{{ $payment->created_at->format('Ymd') }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="sm:text-right">
                <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                <h2 class="text-lg font-medium text-gray-800">{{ $payment->created_at->format('d F Y, H:i') }} WIB</h2>
            </div>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">Status</p>
                @if($payment->status == 'Selesai')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"><svg class="-ml-1 mr-1.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg> Selesai</span>
                @elseif($payment->status == 'Gagal')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800"><svg class="-ml-1 mr-1.5 h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg> Gagal</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"><svg class="-ml-1 mr-1.5 h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg> Pending</span>
                @endif
            </div>
            <div class="sm:text-right">
                <p class="text-sm text-gray-500">Total Pembayaran</p>
                {{-- [PERBAIKAN] Warna diubah menjadi hijau --}}
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format(abs($payment->transaction_amount), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Detail Nasabah & Rincian Pembayaran --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6 self-start">
            <h3 class="text-xl font-bold text-gray-800 border-b pb-4 mb-4">Detail Nasabah</h3>
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                <img class="w-16 h-16 rounded-full object-cover flex-shrink-0" 
                    src="{{ $payment->rekening->user->profile_photo_path ? asset('storage/' . $payment->rekening->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($payment->rekening->user->name) . '&background=random&color=fff&size=128' }}" 
                    alt="Avatar">
                {{-- Info Teks --}}
                <div class="text-sm">
                    <p class="font-bold text-base text-gray-900 leading-relaxed">{{ $payment->rekening->user->name ?? 'N/A' }}</p>
                    <p class="text-gray-500 leading-relaxed">{{ $payment->rekening->user->username ?? 'N/A' }}</p>
                    <p class="text-gray-500 leading-relaxed">{{ $payment->rekening->user->email ?? 'N/A' }}</p>
                    <div class="pt-4">
                    <a href="{{ route('pengelola.nasabah.show', $payment->rekening->user->id) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600 hover:green-blue-800 transition-colors">
                        <span>Lihat Profil Nasabah</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 border-b pb-4 mb-4">Rincian Pembayaran</h3>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Metode Penarikan</dt>
                    <dd class="font-semibold text-gray-900 capitalize">{{ str_replace('Penarikan via ', '', $payment->description) }}</dd>
                </div>
                
                {{-- [PERBAIKAN] Tampilkan info rekening hanya jika bukan tunai --}}
                @if(strpos($payment->description, 'Tunai') === false)
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nomor Rekening/E-Wallet</dt>
                    <dd class="font-semibold text-gray-900">{{ $payment->rekening->rekening_number ?? 'N/A' }}</dd>
                </div>
                @endif
                
                <div class="flex justify-between pt-4 border-t">
                    <dt class="text-base font-bold text-gray-800">Total Dibayarkan</dt>
                    <dd class="text-base font-bold text-green-600">Rp {{ number_format(abs($payment->transaction_amount), 0, ',', '.') }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    {{-- [BARU] Tombol Aksi Tambahan --}}
    <div class="pt-6 border-t flex items-center justify-end gap-3">
        @if($payment->status == 'Pending')
            <form action="{{ route('pengelola.pembayaran.update', $payment->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Gagal">
                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold text-sm px-4 py-2.5 rounded-lg">Tolak Permintaan</button>
            </form>
            <form action="{{ route('pengelola.pembayaran.update', $payment->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Selesai">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold text-sm px-4 py-2.5 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    Selesaikan Transaksi
                </button>
            </form>
        @else
            <a href="{{ route('pengelola.riwayat.cetak', $payment->uuid) }}" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold text-sm px-4 py-2.5 rounded-lg">Cetak Ulang Bukti</a>
        @endif
    </div>
</div>
@endsection