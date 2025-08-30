<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Material;

class WasteType extends Model
{
    /** @use HasFactory<\Database\Factories\WasteTypeFactory> */
    use HasFactory;

    protected $guarded = ['id'];


    public function materials()
    {
        return $this->hasMany(Material::class, 'waste_types_id');
    }
}
