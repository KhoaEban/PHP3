<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function profile()
    {
        $user = Auth::user(); // Lấy thông tin người dùng đang đăng nhập

        return view('user.profile.index', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user(); // Lấy thông tin người dùng đang đăng nhập

        return view('user.profile.edit', compact('user'));
    }
}
