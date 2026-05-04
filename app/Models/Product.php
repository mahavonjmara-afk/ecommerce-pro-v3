<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price',
        'stock_quantity', 'sku', 'image', 'gallery', 'category_id',
        'is_active', 'is_featured'
    ];

    protected $casts = [
        'gallery' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    // Accesseur : prix final (promotion ou non)
    public function getFinalPriceAttribute()
    {
        return ($this->sale_price && $this->sale_price < $this->price) 
            ? $this->sale_price 
            : $this->price;
    }

    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    public function getRouteKeyName()
{
    return 'slug';
}
}