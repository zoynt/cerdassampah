<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports'; // pastikan nama tabel sesuai
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'latitude',
        'longitude',
        'address',
        'status',
        'image',
        'waktu_lapor',
        'waktu_selesai'
    ];

    public $timestamps = false; // jika kolom created_at dan updated_at tidak digunakan
}
