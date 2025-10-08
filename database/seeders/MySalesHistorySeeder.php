<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class SalesHistoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $category;

    public function __construct($category = null)
    {
        $this->category = $category;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $store = Auth::user()->store;
        if (!$store) {
            return collect([]); // Kembalikan koleksi kosong jika tidak ada toko
        }

        // Query dasar yang sama dengan di controller Anda
        $query = OrderItem::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->whereHas('order', function ($q) {
            $q->where('status', 'completed');
        })->with(['order.buyer', 'product.category'])->latest();

        // Terapkan filter kategori jika ada
        if ($this->category) {
            $query->whereHas('product.category', function ($q) {
                $q->where('name', $this->category);
            });
        }

        // Ambil semua data tanpa paginasi
        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini adalah judul kolom di file Excel
        return [
            'Tanggal',
            'Pembeli',
            'Produk',
            'Kategori',
            'Harga Satuan',
            'Jumlah Terjual',
            'Total Penjualan',
        ];
    }

    /**
     * @param OrderItem $item
     * @return array
     */
    public function map($item): array
    {
        // Ini adalah data untuk setiap baris di file Excel
        return [
            $item->created_at->format('Y-m-d'),
            $item->order->buyer->name ?? 'Pembeli Dihapus',
            $item->product->name ?? 'Produk Dihapus',
            optional($item->product->category)->name ?? '-',
            $item->price,
            $item->quantity,
            $item->price * $item->quantity,
        ];
    }
}