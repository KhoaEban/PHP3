<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'amount', 'type', 'expires_at'];
    
    
    protected $casts = [
        'expires_at' => 'date', // Laravel sẽ tự động chuyển đổi chuỗi ngày thành đối tượng Carbon
    ];
}
