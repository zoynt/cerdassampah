@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
@endpush

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Transaksi</h1>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-green-600">
                        <tr>
                            <th scope="col" class="px-6 py-4">No. Pesanan</th>
                            <th scope="col" class="px-6 py-4">Tanggal</th>
                            <th scope="col" class="px-6 py-4">Produk</th>
                            <th scope="col" class="px-6 py-4">Total Pembayaran</th>
                            <th scope="col" class="px-6 py-4 text-center">Status</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    #{{ $order->order_number }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->created_at->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $firstItem = $order->orderItems->first();
                                    @endphp
                                    <span class="font-semibold text-gray-800">{{ optional(optional($firstItem)->product)->name ?? 'Produk Dihapus' }}</span>
                                    @if($order->orderItems->count() > 1)
                                        <span class="text-xs text-gray-500 block">+{{ $order->orderItems->count() - 1 }} produk lainnya</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <div x-data="{ tooltip: false }" class="relative flex justify-center">
                                        <div @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                            @class([
                                                'flex items-center justify-center w-8 h-8 rounded-full',
                                                'bg-yellow-100 text-yellow-800' => $order->status == 'pending',
                                                'bg-blue-100 text-blue-800' => $order->status == 'processing',
                                                'bg-green-100 text-green-800' => $order->status == 'completed',
                                                'bg-red-100 text-red-800' => $order->status == 'canceled',
                                            ])>
                                            @switch($order->status)
                                                @case('pending')
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    @break
                                                @case('processing')
                                                    {{-- [PERUBAHAN] Ikon Roda Gigi diganti Ikon Kotak/Paket --}}
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                                    @break
                                                @case('completed')
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                    @break
                                                @case('canceled')
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    @break
                                            @endswitch
                                        </div>
                                        <div x-show="tooltip" x-transition class="absolute -top-8 z-10 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap capitalize">
                                            {{ str_replace('processing', 'diproses', $order->status) }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div x-data="{ tooltip: false }" class="relative flex justify-center">
                                        <a href="{{ route('marketplace.purchase.detail', ['order' => $order->order_number]) }}" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="p-2 text-gray-500 rounded-full hover:bg-gray-200 hover:text-green-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <div x-show="tooltip" x-transition class="absolute -top-8 z-10 w-auto px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap">
                                            Lihat Detail
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">
                                    <p class="text-gray-500">Anda belum memiliki riwayat transaksi apa pun.</p>
                                    <a href="{{ route('marketplace.products.all') }}" class="mt-4 inline-block bg-green-700 text-white font-bold py-2 px-5 rounded-lg hover:bg-green-800">
                                        Mulai Belanja
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($orders->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $orders->links() }}
                </div>
            @endif
            
        </div>
    </div>
@endsection