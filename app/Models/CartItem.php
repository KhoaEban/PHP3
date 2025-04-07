<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'quantity', 'price'];

    // Liên kết với sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Liên kết với biến thể sản phẩm (nếu có)
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Liên kết với giỏ hàng
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Tính tổng tiền của từng sản phẩm trong giỏ hàng
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
}
