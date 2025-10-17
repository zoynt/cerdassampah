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

    // public function users()
    // {
    //     // ✨ Jauh lebih sederhana
    //     return $this->belongsToMany(User::class, 'bank_sampah_user')
    //                 ->using(BankSampahUser::class)
    //                 ->withPivot('saldo', 'id')
    //                 ->withTimestamps();
    // }


    public function wasteProducts()
    {
        // Sesuaikan 'App\Models\BankWasteProduct' jika nama model Anda berbeda
        return $this->hasMany(BankWasteProduct::class, 'bank_id');
    }

    public function wasteCategories()
    {
        // Asumsi nama modelnya adalah 'BankWasteCategory'
        // dan foreign key di tabel 'bank_waste_categories' adalah 'bank_id'
        return $this->hasMany(BankWasteCategory::class, 'bank_id');
    }

    public function companyWallet()
    {
        return $this->hasOne(CompanyWallet::class, 'bank_id');
    }

    public function rekening()
    {
        return $this->hasMany(RekeningBankSampahUser::class, 'bank_id');
    }
}
