<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'price', 'stock', 'status', 'image', 'discount_id'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'product_brands');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function getDiscountedPrice()
    {
        if ($this->discount) {
            if ($this->discount->type === 'percentage') {
                return $this->price - ($this->price * $this->discount->amount / 100);
            } else {
                return max(0, $this->price - $this->discount->amount);
            }
        }
        return $this->price;
    }

    public function getPriceAttribute()
    {
        if ($this->variants->isNotEmpty()) {
            // Nếu có biến thể, lấy giá thấp nhất hoặc giá bạn mong muốn
            return $this->variants->min('price');
        }

        return $this->attributes['price'];  // Giá sản phẩm chính
    }

    public function getQuantityAttribute()
    {
        if ($this->variants->isNotEmpty()) {
            // Nếu có biến thể, bạn có thể tính tổng số lượng của các biến thể
            return $this->variants->sum('stock');
        }

        return $this->attributes['stock'];  // Số lượng sản phẩm chính
    }

    // Tạo slug tự động khi lưu sản phẩm
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->title);
        });
    }
}
