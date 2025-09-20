<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    // ← Tambahkan ini: otomatis ikut saat toArray()/toJson()
    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userpoints()
    {
        return $this->hasMany(UserPoint::class, 'user_id', 'id');
    }

    // ← Tambahkan accessor ini: mengikuti logika di dashboard.blade.php
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->profile_photo_path)) {
            // sama seperti di Blade: asset('storage/...')
            return asset('storage/' . ltrim($this->profile_photo_path, '/'));
        }

        // fallback UI Avatars menggunakan name; kalau kosong pakai username
        $label = $this->name ?: $this->username ?: 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($label) . '&background=random';
    }

    public function bankSampahs()
    {
        // ✨ Tidak perlu lagi menyebutkan foreign keys secara eksplisit
        return $this->belongsToMany(BankSampah::class, 'bank_sampah_user')
                    ->using(BankSampahUser::class)
                    ->withPivot('saldo', 'id')
                    ->withTimestamps();
    }

    public function stores()
    {
        return $this->hasOne(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(StoreReview::class);
    }
}
