<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWallet extends Model
{
    protected $fillable = [
        'bank_id',
        'balance',
        'price_per_kg',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function transactions()
    {
        return $this->hasMany(CompanyTransaction::class, 'wallet_id');
    }
}
