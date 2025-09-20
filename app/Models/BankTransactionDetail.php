<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransactionDetail extends Model
{
    protected $fillable = [
        'bank_waste_category_id',
        'transaction_id',
        'weight_kg',
        'price_per_kg',
        'detail_description',
        'detail_amount',
    ];

    public function transaction()
    {
        return $this->belongsTo(BankTransaction::class, 'transaction_id');
    }

    public function wasteCategory()
    {
        return $this->belongsTo(BankWasteCategory::class, 'bank_waste_category_id');
    }
}
