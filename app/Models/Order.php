<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'order_number',
        'total_amount',
        'status',
        'delivery_address',    
        'delivery_latitude',   
        'delivery_longitude',  
        'payment_status',
        'snap_token',
        'payment_method',
        ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getPaymentMethodNameAttribute(): string
    {
        $paymentMethod = $this->attributes['payment_method'];

        // Daftar pemetaan dari kode Midtrans ke nama yang lebih ramah
        $paymentMap = [
            'credit_card'   => 'Kartu Kredit',
            'gopay'         => 'GoPay',
            'shopeepay'     => 'ShopeePay',
            'echannel'      => 'Mandiri Bill Payment', // Ini jawaban untuk masalah Anda
            'bank_transfer' => 'Transfer Bank',
            'bca_va'        => 'BCA Virtual Account',
            'bni_va'        => 'BNI Virtual Account',
            'bri_va'        => 'BRI Virtual Account',
            'permata_va'    => 'Permata Virtual Account',
            'cstore'        => 'Bayar di Minimarket',
        ];

        // Jika ada di dalam peta, gunakan nama dari peta. Jika tidak, format nama default.
        return $paymentMap[$paymentMethod] ?? ucwords(str_replace('_', ' ', $paymentMethod));
    }
    public function getTranslatedStatusAttribute(): string
    {
        $statusMap = [
            'completed'  => 'Selesai',
            'canceled'   => 'Dibatalkan',
            'processing' => 'Diproses',
            'pending'    => 'Pending', // Sertakan juga status lain jika ada
        ];

        // Ambil status dari database, cari di peta, jika tidak ada, tampilkan apa adanya.
        return $statusMap[$this->status] ?? ucfirst($this->status);
    }
};