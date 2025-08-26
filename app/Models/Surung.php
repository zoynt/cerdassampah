<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surung extends Model
{
    /** @use HasFactory<\Database\Factories\SurungFactory> */
    use HasFactory;

    protected $fillable = [
        'surung_name',
        'surung_longitude',
        'surung_latitude',
        'kecamatan',
        'worker_name',
        'worker_no',
        'area',
        'surung_day',
        'surung_start_time',
        'surung_end_time',
        'surung_description',
    ];

    public function tps()
    {
        return $this->belongsTo(Tps::class);
    }
}
