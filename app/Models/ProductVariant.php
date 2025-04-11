<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_type',
        'variant_value',
        'price',
        'stock',
        'sku',
    ];

    public function images()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountedPrice()
    {
        if ($this->product->discount) {
            if ($this->product->discount->type === 'percentage') {
                return $this->price - ($this->price * $this->product->discount->amount / 100);
            } else {
                return max(0, $this->price - $this->product->discount->amount);
            }
        }
        return $this->price;
    }


    // Quan hệ với bảng products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
