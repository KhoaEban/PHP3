<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'receiver_name', 'phone', 'email', 'province', 'district', 'ward', 'address', 'is_default'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
