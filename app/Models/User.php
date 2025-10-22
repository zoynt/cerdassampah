<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RekeningBankSampahUser; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Bank;
use App\Models\Store; // [FIX] Ditambahkan untuk relasi store()
use App\Models\Order; // [FIX] Ditambahkan untuk relasi orders() & sales()
use App\Models\StoreReview; // [FIX] Ditambahkan untuk relasi reviews()
use App\Models\UserPoint; // [FIX] Ditambahkan untuk relasi userpoints()

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'alamat',
        'no_telepon',
        'password',
        'profile_photo_path',
        'email_verified_at',
        'points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI-RELASI ---

    /**
     * [PERBAIKAN DITAMBAHKAN]
     * Relasi untuk User yang bertindak sebagai PENGELOLA.
     * Satu User (Pengelola) memiliki satu Bank Sampah.
     * Ini akan memperbaiki Auth::user()->bank
     */
    public function bank()
    {
        return $this->hasOne(Bank::class, 'user_id');
    }

    /**
     * [PERBAIKAN DITAMBAHKAN]
     * Relasi untuk User yang bertindak sebagai PENJUAL.
     * Satu User (Penjual) memiliki satu Toko.
     */
    public function store()
    {
        return $this->hasOne(Store::class);
    }
    
    /**
     * Relasi ke rekening nasabah.
     * Satu User (Nasabah) bisa memiliki banyak rekening.
     */
    public function rekeningBankSampah()
    {
        return $this->hasMany(RekeningBankSampahUser::class, 'user_id');
    }

    /**
     * [PERBAIKAN DIHAPUS] 
     * Relasi 'rekening()' dihapus karena duplikat dengan 'rekeningBankSampah()'.
     */
    // public function rekening() { ... }

    /**
     * Relasi ke poin game.
     */
    public function userpoints()
    {
        return $this->hasMany(UserPoint::class, 'user_id', 'id');
    }

    /**
     * Relasi ke order sebagai pembeli.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Relasi ke order sebagai penjual.
     */
    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Relasi ke ulasan yang dibuat user.
     */
    public function reviews()
    {
        return $this->hasMany(StoreReview::class);
    }
    
    /**
     * Relasi lama (jika masih dipakai).
     * Ini menyiratkan User bisa terdaftar di BANYAK bank sampah sebagai nasabah.
     */
    public function bankSampahs()
    {
        // return $this->belongsToMany(BankSampah::class, 'bank_sampah_user')
        //            ->using(BankSampahUser::class)
        //            ->withPivot('saldo', 'id')
        //            ->withTimestamps();
    }

    // --- ACCESSOR ---

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->profile_photo_path)) {
            return asset('storage/' . ltrim($this->profile_photo_path, '/'));
        }
        $label = $this->name ?: $this->username ?: 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($label) . '&background=random';
    }
}