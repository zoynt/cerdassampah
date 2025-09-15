<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /** @use HasFactory<\Database\Factories\BankFactory> */
    use HasFactory;

    protected $guarded = ['id'];
    // protected $fillable = [
    //     'bank_name',
    //     'bank_latitude',
    //     'bank_longitude',
    //     'kecamatan'
    // ];

    protected $casts = [
        'bank_day' => 'array', 
    ];

    public function users()
    {
        // ✨ Jauh lebih sederhana
        return $this->belongsToMany(User::class, 'bank_sampah_user')
                    ->using(BankSampahUser::class)
                    ->withPivot('saldo', 'id')
                    ->withTimestamps();
    }
}
