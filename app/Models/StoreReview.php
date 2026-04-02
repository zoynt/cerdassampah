<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreReview extends Model
{
    use HasFactory;
    
    protected $table = 'store_reviews';

    protected $fillable = ['store_id', 'user_id', 'order_id', 'rating', 'review'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}