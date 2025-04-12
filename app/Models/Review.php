<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'variant_id', 'order_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function userHasPurchased()
    {
        return Order::where('user_id', $this->user_id)
            ->whereHas('items', function ($query) {
                $query->where('product_id', $this->product_id);
            })
            ->exists();
    }
}
