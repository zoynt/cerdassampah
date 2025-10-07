<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningBankSampahUser extends Model
{
    protected $fillable = [
        'user_id',
        'bank_id',
        'account_number',
        'account_holder_name',
        'rekening_number',
        'saldo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class, 'rekening_id');
    }
}
