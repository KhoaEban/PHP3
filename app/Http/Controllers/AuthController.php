<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use League\OAuth2\Client\Provider\Google;


use App\Models\User;

class AuthController extends Controller
{

    protected $provider;

    public function __construct()
    {
        $this->provider = new Google([
            'clientId'     => env('GOOGLE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => env('GOOGLE_REDIRECT_URI'),
        ]);
    }

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

    // Đăng nhập bằng Google
    public function redirectToGoogle()
    {
        $authUrl = $this->provider->getAuthorizationUrl([
            'prompt' => 'select_account'
        ]);

        session(['oauth2state' => $this->provider->getState()]);

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code') || $request->has('error')) {
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại.');
        }

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code')
            ]);

            $googleUser = $this->provider->getResourceOwner($token);

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(str()->random(16)), // Không cần thiết vì dùng Google
                ]
            );

            Auth::login($user);

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công (Admin)!');
            } else {
                return redirect()->route('user.index')->with('success', 'Đăng nhập thành công!');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại.');
        }
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        session()->flush(); // Xoá toàn bộ session
        return redirect('/login')->with('success', 'Bạn đã đăng xuất.');
    }
}
