<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name', // Kiểm tra trùng tên
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'name.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên người dùng đã được sử dụng.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công (Admin)!');
            } else {
                return redirect()->route('user.index')->with('success', 'Đăng nhập thành công!');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Sai thông tin đăng nhập.'])->withInput();
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

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(str()->random(16)),
                ]);
            } elseif (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

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
        session()->flush();
        return redirect('/login')->with('success', 'Bạn đã đăng xuất.');
    }
}
