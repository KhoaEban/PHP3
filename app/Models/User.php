<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
}
