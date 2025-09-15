<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BankSampahUser extends Pivot
{
    /**
     * Menunjukkan bahwa ID tidak auto-increment.
     * @var bool
     */
    public $incrementing = true; // Jika Anda menggunakan `id` sebagai PK di tabel pivot

    /**
     * Nama tabel yang digunakan oleh model.
     * @var string
     */
    protected $table = 'bank_sampah_user';

    // Relasi kembali ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi kembali ke model BankSampah
    public function bankSampah()
    {
        return $this->belongsTo(BankSampah::class, 'id_bank_sampah');
    }
}