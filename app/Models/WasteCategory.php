<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    /** @use HasFactory<\Database\Factories\WasteCategoryFactory> */
    use HasFactory;
    public function banks()
    {
        return $this->belongsToMany(Bank::class, 'bank_waste_categories')
                    ->withPivot('price_per_kg')
                    ->withTimestamps();
    }
}
