<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- 1. Import class Str untuk UUID

class BankTransaction extends Model
{
    use HasFactory; // <-- 2. Tambahkan ini (praktik terbaik)

    protected $fillable = [
        'rekening_id',
        'transaction_code',
        'transaction_amount',
        'transaction_type',
        'description',
        'status',
        // 'uuid' tidak perlu ada di sini karena akan dibuat otomatis
    ];

    /**
     * Boot the model.
     * Secara otomatis membuat UUID saat transaksi baru akan dibuat.
     */
    protected static function boot()
    {
        parent::boot(); // Jangan lupa panggil parent boot

        // 3. Listener 'creating' akan berjalan sebelum data disimpan ke database
        static::creating(function ($model) {
            // Jika kolom uuid masih kosong, buatkan UUID baru
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Mengubah kunci rute default dari 'id' menjadi 'uuid'.
     * Ini akan membuat Laravel menggunakan UUID saat mencari model dari URL.
     * Contoh: /.../riwayat-setoran/{uuid}
     */
    public function getRouteKeyName()
    {
        // 4. Beritahu Laravel untuk menggunakan kolom 'uuid' untuk URL
        return 'uuid';
    }

    /**
     * Relasi ke model RekeningBankSampahUser.
     */
    public function rekening()
    {
        return $this->belongsTo(RekeningBankSampahUser::class, 'rekening_id');
    }

    /**
     * Relasi ke model BankTransactionDetail.
     */
    public function details()
    {
        return $this->hasMany(BankTransactionDetail::class, 'transaction_id');
    }
}