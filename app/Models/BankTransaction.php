<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = [
        'rekening_id',
        'transaction_code',
        'transaction_amount',
        'transaction_type',
        'description',
    ];

    public function rekening()
    {
        return $this->belongsTo(RekeningBankSampahUser::class, 'rekening_id');
    }

    public function details()
    {
        return $this->hasMany(BankTransactionDetail::class, 'transaction_id');
    }
}
