<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surung extends Model
{
    /** @use HasFactory<\Database\Factories\SurungFactory> */
    use HasFactory;

    protected $fillable = [
        'tps_id',
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

    // Surung.php (Model)
    public function setSurungStartTimeAttribute($value)
    {
        $this->attributes['surung_start_time'] = \Carbon\Carbon::createFromFormat('H:i', $value)->format('H:i:s');
    }

    public function setSurungEndTimeAttribute($value)
    {
        $this->attributes['surung_end_time'] = \Carbon\Carbon::createFromFormat('H:i', $value)->format('H:i:s');
    }

    // protected $casts = [
    //     'kecamatan' => 'enum:banjarmasin utara,banjarmasin selatan,banjarmasin tengah,banjarmasin barat,banjarmasin timur',
    // ];
}
