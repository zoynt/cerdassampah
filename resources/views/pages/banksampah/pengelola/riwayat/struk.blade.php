<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - CerdasSampah</title>
    {{-- Memuat Tailwind CSS untuk styling struk --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Memastikan warna latar belakang ikut tercetak */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
{{-- 'onload' akan otomatis memicu dialog cetak saat halaman selesai dimuat --}}
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4" onload="window.print()">
    
    {{-- Area Struk yang Akan Dicetak (mengadaptasi layout invoice Anda) --}}
    <div id="invoice-area" class="w-full max-w-2xl bg-white rounded-xl shadow-lg border overflow-hidden">
        {{-- Header Struk --}}
        <div class="bg-green-700 text-white p-6 text-center">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-inner">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo CerdasSampah" class="w-14 h-14">
                </div>
            </div>
            <h2 class="text-2xl font-bold">Struk Setoran Sampah</h2>
            <p class="text-xs text-green-200 mt-2 max-w-md mx-auto">
                Jl. Sultan Adam No.3, Sungai Miai, Banjarmasin Utara, Banjarmasin City, South Kalimantan 70123
            </p>
        </div>

        {{-- Status Transaksi --}}
        <div class="p-3 text-center text-sm font-bold capitalize bg-green-100 text-green-800">
            Transaksi Selesai
        </div>

        <div class="p-6 md:p-8 space-y-8">
            {{-- ID & Tanggal --}}
            <div class="flex justify-between items-center pb-4 border-b border-dashed text-sm">
                <span class="text-gray-500">ID Transaksi</span>
                <span class="font-semibold text-gray-800 tracking-wider">TRX-{{ $transaction->created_at->format('Ymd') }}-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            {{-- Info Bank Sampah & Nasabah --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <p class="font-semibold text-gray-500">DIVERIFIKASI OLEH (BANK SAMPAH):</p>
                    <p class="font-bold text-base text-gray-800">{{ $transaction->rekening->bank->name ?? 'Bank Sampah CerdasSampah' }}</p>
                    <p class="text-gray-600">Petugas: {{ Auth::user()->name }}</p>
                </div>
                <div class="space-y-2 md:text-right">
                    <p class="font-semibold text-gray-500">UNTUK NASABAH:</p>
                    <p class="font-bold text-base text-gray-800">{{ $transaction->rekening->user->name ?? 'N/A' }}</p>
                    <p class="text-gray-600">{{ $transaction->rekening->user->email ?? 'N/A' }}</p>
                    <p class="text-gray-600 mt-2">Tgl. Transaksi: <span class="font-medium">{{ $transaction->created_at->locale('id')->translatedFormat('d F Y') }}</span></p>
                </div>
            </div>

            {{-- Tabel Rincian Setoran --}}
            <div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs">Jenis Sampah</th>
                            <th class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs text-center">Berat</th>
                            <th class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs text-right">Harga /kg</th>
                            <th class="p-2 pb-2 font-semibold text-gray-500 uppercase tracking-wider text-xs text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($transaction->details as $detail)
                        <tr>
                            <td class="p-2 pt-4">
                                <p class="font-semibold text-gray-800">{{ $detail->wasteProduct?->item_name ?? 'Item Dihapus' }}</p>
                            </td>
                            <td class="p-2 pt-4 text-center text-gray-600 tabular-nums">{{ number_format($detail->weight_kg, 2, ',', '.') }} kg</td>
                            <td class="p-2 pt-4 text-right text-gray-600 tabular-nums">Rp {{ number_format($detail->wasteProduct?->price_per_kg ?? 0, 0, ',', '.') }}</td>
                            <td class="p-2 pt-4 text-right font-bold text-gray-800 tabular-nums">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Kalkulasi Total --}}
            <div class="space-y-6 pt-6">
                <div class="flex justify-end">
                    <div class="w-full md:w-1/2 lg:w-2/5 space-y-3 text-sm">
                        <div class="flex justify-between items-start border-t pt-3">
                            <span class="text-gray-600 pr-4">Subtotal</span>
                            <span class="font-semibold text-gray-800 text-right">Rp {{ number_format($transaction->transaction_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-green-700 border-t-2 border-green-700 mt-2 pt-2">
                            <span>Total Pemasukan</span>
                            <span>Rp {{ number_format($transaction->transaction_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-center text-gray-500 text-xs pt-4 border-t">
                    Terima kasih telah berkontribusi menjaga lingkungan bersama CerdasSampah.
                </div>
            </div>
        </div>
    </div>

</body>
</html>
