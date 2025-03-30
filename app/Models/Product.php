<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'price', 'stock', 'status', 'image'];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }
    // public function brand()
    // {
    //     return $this->belongsTo(Brand::class);
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'product_brands');
    }


    // Liên kết với nhiều hình ảnh
    public function images()
    {
        return $this->hasMany(ProductImage::class);
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
