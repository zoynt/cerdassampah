<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // [TAMBAH] Untuk lebar kolom otomatis
use Illuminate\Support\Facades\Auth;

// [UBAH] Tambahkan interface WithHeadings, WithMapping, dan ShouldAutoSize
class SalesHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $category;

    public function __construct($category = null)
    {
        $this->category = $category;
    }

    /**
    * Mengambil data dari database.
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $store = Auth::user()->store;
        if (!$store) {
            return collect([]);
        }

        $query = OrderItem::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->whereHas('order', function ($q) {
            $q->where('status', 'completed');
        })->with(['order.buyer', 'product.category'])->latest();

        if ($this->category) {
            $query->whereHas('product.category', function ($q) {
                $q->where('name', $this->category);
            });
        }

        return $query->get();
    }

    /**
     * [BARU] Menentukan judul untuk setiap kolom.
     * Method ini akan otomatis dijalankan karena kita menambahkan `WithHeadings`.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Nama Pembeli',
            'Nama Produk',
            'Kategori',
            'Harga Satuan (Rp)',
            'Jumlah Terjual',
            'Total Penjualan (Rp)',
        ];
    }

    /**
     * [BARU] Mengubah setiap baris data mentah menjadi format yang kita inginkan.
     * Method ini akan otomatis dijalankan untuk setiap baris karena kita menambahkan `WithMapping`.
     * @param OrderItem $item
     * @return array
     */
    public function map($item): array
    {
        return [
            $item->created_at->format('Y-m-d H:i:s'),
            $item->order->buyer->name ?? 'Pembeli Dihapus',
            $item->product->name ?? 'Produk Dihapus',
            optional($item->product->category)->name ?? '-',
            $item->price,
            $item->quantity,
            $item->price * $item->quantity,
        ];
    }
}