<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
        ]);

        return redirect()->route('login.form')->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Lấy thông tin user đang đăng nhập

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công (Admin)!');
            } else {
                return redirect()->route('user.index')->with('success', 'Đăng nhập thành công!');
            }
        }

        return back()->withErrors(['email' => 'Sai thông tin đăng nhập.']);
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form')->with('success', 'Bạn đã đăng xuất.');
    }
}
