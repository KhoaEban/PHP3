<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'status',
        'avatar',
        'description',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function viewedProducts()
    {
        return $this->hasMany(ViewedProduct::class)
            ->with('product')
            ->orderBy('updated_at', 'desc')
            ->limit(10);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Lấy địa chỉ mặc định
    public function defaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first() ?? $this->addresses()->first();
    }

    // Kiểm tra xem người dùng đã mua sản phẩm chưa
    public function hasPurchasedProduct($productId)
    {
        return $this->orders()
            ->where('status', 'completed') // Chỉ tính đơn hàng đã hoàn thành
            ->whereHas('items', function ($query) use ($productId) { // Sửa từ orderDetails thành items
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    protected $appends = ['avatar'];

    public function getAvatarAttribute()
    {
        return $this->attributes['avatar'] ?? 'default-avatar.jpg';
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}
