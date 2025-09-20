<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'phone_number',
        'is_active',
        'latitude',
        'longitude',
        'address',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(StoreReview::class);
    }
}
