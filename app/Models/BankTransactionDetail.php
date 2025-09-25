<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'bank_waste_product_id', // Ini adalah foreign key yang paling penting
        'weight_kg',
        'price_per_kg',          // "Snapshot" harga saat transaksi
        'subtotal',              // Hasil dari berat x harga
    ];

    public function transaction()
    {
        return $this->belongsTo(BankTransaction::class, 'transaction_id');
    }

    public function wasteProduct()
    {
        return $this->belongsTo(BankWasteProduct::class, 'bank_waste_product_id');
    }
}
