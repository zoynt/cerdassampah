<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSampah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'alamat',
    ];

    /**
     * Mendefinisikan relasi bahwa satu Bank Sampah memiliki banyak User.
     * (Relasi ini opsional, tambahkan jika Anda memerlukannya)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
