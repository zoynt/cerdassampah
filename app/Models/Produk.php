<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'kategori',
        'harga',
        'stok',
        'bobot', // <-- TAMBAHKAN INI
        'satuan_berat',
        'status', // <-- TAMBAHKAN INI
        'alamat',
        'image_path',
    ];
}
