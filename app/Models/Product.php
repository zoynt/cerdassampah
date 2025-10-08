<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Properti yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'store_id',
        'product_category_id',
        'name',
        'price',
        'stock',
        'selling_unit',
        'weight_per_item',
        'status',
        'description',
    ];

    /**
     * Relasi ke Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Relasi ke ProductCategory
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Relasi ke ProductImage
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}