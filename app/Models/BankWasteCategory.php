<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankWasteCategory extends Model
{
    /** @use HasFactory<\Database\Factories\BankWasteCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        
        'name',
        'description',
    ];

    public function bank_product()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
    
}
