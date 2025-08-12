<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    /** @use HasFactory<\Database\Factories\WasteTypeFactory> */
    use HasFactory;

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
