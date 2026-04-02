<?php

namespace App\Models;
use App\Models\BankWasteCategory;

use Illuminate\Database\Eloquent\Model;

class BankWasteProduct extends Model
{
    protected $fillable = [
        'bank_id',
        'waste_category_id',
        'item_name',
        'price_per_kg',
        'description',
        'status', // <-- TAMBAHKAN INI
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function wasteCategory()
    {
        return $this->belongsTo(BankWasteCategory::class, 'waste_category_id');
    }

    public function category() // <-- NAMA YANG BENAR
    {
        return $this->belongsTo(BankWasteCategory::class, 'waste_category_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(BankTransactionDetail::class, 'bank_waste_product_id');
    }
}
