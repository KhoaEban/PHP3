<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Nếu bảng có tên orders thì có thể không cần khai báo lại
    // protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'total',
        'payment_method',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
