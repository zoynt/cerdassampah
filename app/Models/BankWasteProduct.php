<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankWasteProduct extends Model
{
    protected $fillable = [
        'bank_id',
        'waste_category_id',
        'price_per_kg',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function wasteCategory()
    {
        return $this->belongsTo(BankWasteCategory::class, 'waste_category_id');
    }   
}
