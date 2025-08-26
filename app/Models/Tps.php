<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    /** @use HasFactory<\Database\Factories\TpsFactory> */
    use HasFactory;

    protected $fillable = [
        'tps_name',
        'tps_longitude',
        'tps_latitude',
        'tps_status',
        'tps_description',
        'kecamatan',
        'tps_day',
        'tps_start_time',
        'tps_end_time',
        'tps_transport',
    ];

    public function surungs()
    {
        return $this->hasMany(Surung::class);
    }
};
