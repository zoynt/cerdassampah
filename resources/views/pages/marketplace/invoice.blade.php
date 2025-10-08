@extends('layouts.dashboard')

@section('title', 'Invoice ')

@push('head')
    <style>
        @media print {

            body>div,
            body>div>div>header,
            #action-buttons {
                display: none !important;
            }

            #invoice-area {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <h1 class="text-xl md:text-3xl font-bold text-gray-800">Invoice {{ $order->order_number }}</h1>
        <div id="invoice-area" class="bg-white rounded-xl shadow-lg border overflow-hidden">
            <div class="bg-green-700 text-white p-6 md:p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-inner">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo CerdasSampah" class="w-14 h-14">
                    </div>
                </div>
                <h2 class="text-xl md:text-2xl font-bold">Invoice CerdasSampah</h2>
                <p class="text-xs text-green-200 mt-2 max-w-md mx-auto">
                    Jl. Sultan Adam No.3, Sungai Miai, Banjarmasin Utara, Banjarmasin City, South Kalimantan 70123
                </p>
            </div>

            {{-- [UBAH] Status dinamis --}}
            <div
                class="p-3 text-center text-sm font-bold capitalize
                @if ($order->status == 'completed') bg-green-100 text-green-800 @endif
                @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                @if ($order->status == 'canceled') bg-red-100 text-red-800 @endif">
                Status Pesanan: {{ $order->status }}
            </div>

            <div class="p-6 md:p-8 space-y-8">
                <div class="flex justify-between items-center pb-4 border-b border-dashed text-sm">
                    <span class="text-gray-500">ID Pesanan</span>
                    {{-- [UBAH] ID Pesanan dinamis --}}
                    <span class="font-semibold text-gray-800 tracking-wider">{{ $order->order_number }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                    {{-- [UBAH] Info Penjual dinamis --}}
                    <div class="space-y-2">
                        <p class="font-semibold text-gray-500">DITERBITKAN ATAS NAMA:</p>
                        <p class="font-bold text-base text-gray-800">{{ $order->seller?->store?->name ?? 'Toko Dihapus' }}
                        </p>
                        <p class="text-gray-600">{{ $order->seller?->name ?? 'Penjual Dihapus' }}</p>
                        <p class="text-gray-600">{{ $order->seller?->store?->address ?? 'Alamat toko tidak tersedia' }}</p>
                    </div>
                    {{-- [UBAH] Info Pembeli dinamis --}}
                    <div class="space-y-2 md:text-right">
                        <p class="font-semibold text-gray-500">UNTUK:</p>
                        <p class="font-bold text-base text-gray-800">{{ $order->buyer?->name ?? 'Pembeli Dihapus' }}</p>
                        <p class="text-gray-600">{{ $order->delivery_address ?? 'Alamat pengiriman tidak tersedia' }}</p>
                        <p class="text-gray-600 mt-2">Tgl. Pesanan: <span
                                class="font-medium">{{ $order->created_at->locale('id')->translatedFormat('d F Y') }}</span>
                        </p>
                    </div>
                </div>

                {{-- [UBAH] Tabel Item dinamis (Desktop) --}}
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs">Info
                                    Produk</th>
                                <th
                                    class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs text-center">
                                    Jumlah</th>
                                <th
                                    class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs text-right">
                                    Harga Satuan</th>
                                <th
                                    class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs text-right">
                                    Total Harga</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td class="p-2 pt-4">
                                        <p class="font-semibold text-gray-800">
                                            {{ $item->product?->name ?? 'Produk Dihapus' }}</p>
                                        @if ($item->product?->weight_per_item)
                                            <p class="text-gray-500 text-xs">Berat:
                                                {{ (int) $item->product->weight_per_item }}
                                                {{ $item->product->selling_unit }}</p>
                                        @endif
                                    </td>
                                    <td class="p-2 pt-4 text-center text-gray-600">{{ $item->quantity }}</td>
                                    <td class="p-2 pt-4 text-right text-gray-600">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="p-2 pt-4 text-right font-bold text-gray-800">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- [UBAH] Tampilan Item dinamis (Mobile) --}}
                <div class="md:hidden space-y-4">
                    <h3 class="font-semibold text-gray-500 uppercase tracking-wider text-xs border-b pb-2">DETAIL ITEM</h3>
                    @foreach ($order->orderItems as $item)
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->product?->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-gray-500 text-xs">{{ $item->quantity }} x Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-gray-800">Rp
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-6 pt-6">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/2 lg:w-2/5 space-y-3 text-sm">
                            {{-- [UBAH] Total dinamis --}}
                            <div class="flex justify-between items-start border-t pt-3">
                                <span class="text-gray-600 pr-4">Subtotal</span>
                                <span class="font-semibold text-gray-800 text-right">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600 pr-4">Metode Pembayaran</span>
                                <span class="font-semibold text-gray-800 text-right capitalize">
                                    @if ($order->payment_method)
                                        {{-- Mengubah 'bank_transfer' menjadi 'bank transfer' --}}
                                        {{ str_replace('_', ' ', $order->payment_method) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div
                                class="flex justify-between text-base font-bold text-green-700 border-t-2 border-green-700 mt-2 pt-2">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-gray-500 text-xs pt-4 border-t">
                        Terima kasih telah melakukan transaksi di CerdasSampah.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="action-buttons" class="mt-6 flex flex-col sm:flex-row-reverse gap-4">
        <button onclick="printInvoice()"
            class="w-full sm:w-auto px-5 py-2.5 bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-green-600 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-8V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Cetak Invoice
        </button>
        <a href="{{ route('marketplace.purchase.detail', $order) }}"
            class="w-full sm:w-auto text-center px-5 py-2.5 bg-gray-200 text-gray-800 text-sm font-semibold rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>
@endsection
@push('scripts')
    <script>
        function printInvoice() {
            const invoiceArea = document.getElementById('invoice-area').innerHTML;
            const printWindow = window.open('', '', 'height=800,width=800');
            printWindow.document.write('<html><head><title>Cetak Invoice</title>');
            document.querySelectorAll('link[rel="stylesheet"]').forEach(link => {
                printWindow.document.write('<link rel="stylesheet" href="' + link.href + '">');
            });
            printWindow.document.write(
                '<style>body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } @page { size: A4; margin: 0; } </style>'
            );
            printWindow.document.write('</head><body style="background-color: white;">');
            printWindow.document.write(invoiceArea);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
@endpush
