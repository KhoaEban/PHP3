<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;

class UserController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('user.index', compact('categories'));
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

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Cập nhật thành công');
    }
}
