<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = [
        'waste_types_id',
        'description_mat',
        'recycle_info'
    ];

    public function waste_types()
    {
        return $this->belongsTo(WasteType::class);
    }
}
