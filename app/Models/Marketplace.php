<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_marketplace',
        'hari_operasional',
        'alamat_lengkap',
        'kecamatan',
        'kelurahan',
        'jam_mulai',
        'jam_berakhir',
        'deskripsi',
        'latitude',
        'longitude',
        'image_path',
        'status', // <-- TAMBAHKAN INI
    ];

    protected $casts = [
        'hari_operasional' => 'array',
        'status' => 'boolean', // <-- TAMBAHKAN INI
    ];
}
