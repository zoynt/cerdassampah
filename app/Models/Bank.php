<?php

namespace App\Models;

use App\Models\BankWasteCategory;
use App\Models\BankWasteProduct;
use App\Models\CompanyWallet;
use App\Models\RekeningBankSampahUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    /**
     * [PERBAIKAN] Mengganti $guarded dengan $fillable.
     * Ini akan mengizinkan kolom-kolom ini untuk disimpan dari form.
     */
    protected $fillable = [
        'user_id',
        'bank_name',
        'slug',
        'address',
        'district',
        'sub_district',
        'latitude',
        'longitude',
        'operational_days',
        'opening_hour',
        'closing_hour',
        'phone_number',
        'description',
        'image_path',
        'is_active',
    ];

    /**
     * [PERBAIKAN] Menyesuaikan nama kolom 'bank_day' menjadi 'operational_days'.
     * Jika nama kolom di database Anda adalah 'bank_day', ganti 'operational_days' di sini.
     */
    protected $casts = [
        'operational_days' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke User (pemilik/pengelola bank sampah).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke item sampah yang diterima bank ini.
     */
    public function wasteProducts()
    {
        return $this->hasMany(BankWasteProduct::class, 'bank_id');
    }

    /**
     * Relasi ke kategori sampah (jika kategori spesifik per bank).
     */
    public function wasteCategories()
    {
        return $this->hasMany(BankWasteCategory::class, 'bank_id');
    }

    /**
     * Relasi ke dompet perusahaan (jika ada).
     */
    public function companyWallet()
    {
        return $this->hasOne(CompanyWallet::class, 'bank_id');
    }

    /**
     * Relasi ke semua rekening nasabah yang terdaftar di bank ini.
     */
    public function rekening()
    {
        return $this->hasMany(RekeningBankSampahUser::class, 'bank_id');
    }
}