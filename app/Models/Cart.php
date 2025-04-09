<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    // Liên kết với bảng CartItem
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Liên kết với người dùng (nếu có)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tính tổng số lượng sản phẩm trong giỏ hàng
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Tính tổng giá tiền của giỏ hàng
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->price);
    }
    
}
