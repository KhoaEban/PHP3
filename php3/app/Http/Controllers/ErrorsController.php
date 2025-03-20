<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Comment;
use App\Models\Rating;

class ErrorsController extends Controller
{
    public function index()
    {
        return view('404');
    }
}
// <!-- Errors Controller -->