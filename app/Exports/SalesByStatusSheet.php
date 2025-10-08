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
        return Order::query()
            ->where('seller_id', $store->user_id)
            ->where('status', $this->status)
            ->with(['buyer', 'orderItems.product']);
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
            $order->total_amount,
        ];
    }

    /**
     * Memberi nama pada sheet.
     */
    public function title(): string
    {
        if ($this->status === 'pending') return 'Pesanan Baru';
        if ($this->status === 'completed') return 'Selesai';
        if ($this->status === 'canceled') return 'Dibatalkan'; // Sesuaikan dengan 'cancelled' jika ejaan di DB berbeda

        return 'Lainnya';
    }
}