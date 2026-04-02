<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tipe',
        'deskripsi',
        'detail',
        'jumlah',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah' => 'decimal:2', // Memastikan 'jumlah' selalu dianggap sebagai angka desimal
    ];

    /**
     * Mendefinisikan relasi bahwa satu Transaksi dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
