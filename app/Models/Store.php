<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'phone_number',
        'is_active',
        'latitude',
        'longitude',
        'address',
        'image_path',
        'district',
        'sub_district',
        'operational_days',
        'opening_hour',
        'closing_hour',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'operational_days' => 'array', 
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function getOpeningHourAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }
    public function getClosingHourAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }
    public function reviews()
    {
        return $this->hasMany(StoreReview::class);
    }
    public function getFormattedPhoneNumberAttribute()
    {
        $cleanedNumber = preg_replace('/[^0-9]/', '', $this->phone_number);
        if (substr($cleanedNumber, 0, 1) === '0') {
            return '62' . substr($cleanedNumber, 1);
        }
        return $cleanedNumber;
    }
}