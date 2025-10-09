<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesByStatusSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * Mengambil data pesanan dari database berdasarkan status.
     */
    public function query()
    {
        $store = Auth::user()->store;
        $query = Order::query()
            ->where('seller_id', $store->user_id)
            ->with(['buyer', 'orderItems.product']);

        // Jika status yang diminta adalah 'pending' (untuk sheet 'Pesanan Baru'),
        // kita sertakan juga status 'processing'.
        if ($this->status === 'pending') {
            $query->whereIn('status', ['pending', 'processing']);
        } else {
            // Untuk sheet lain, gunakan status yang spesifik.
            $query->where('status', $this->status);
        }

        return $query;
    }

    /**
     * Menentukan judul kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Tanggal Pesanan',
            'Nama Pembeli',
            'Produk Dibeli',
            'Jumlah Item',
            'Total Harga',
            'Status', // Menambahkan kolom status untuk kejelasan
        ];
    }

    /**
     * Memetakan setiap baris data.
     */
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('d-m-Y H:i'),
            optional($order->buyer)->name ?? 'Pembeli Dihapus',
            $order->orderItems->pluck('product.name')->join(', '),
            $order->orderItems->sum('quantity'),
            number_format($order->total_amount, 0, ',', '.'),
            ucfirst($order->status), // Menambahkan data status di setiap baris
        ];
    }

    /**
     * Memberi nama pada sheet.
     */
    public function title(): string
    {
        if ($this->status === 'pending') return 'Pesanan Baru';
        if ($this->status === 'completed') return 'Selesai';
        if ($this->status === 'canceled') return 'Dibatalkan';

        return 'Lainnya';
    }
}