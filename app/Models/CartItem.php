<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];


    // Tính tổng tiền của từng sản phẩm trong giỏ hàng
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Liên kết với sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Liên kết với giỏ hàng
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
