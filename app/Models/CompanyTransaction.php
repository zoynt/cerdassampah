<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'transaction_code',
        'amount',
        'description',
    ];

    public function wallet()
    {
        return $this->belongsTo(CompanyWallet::class, 'wallet_id');
    }
}
