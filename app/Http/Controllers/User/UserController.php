<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function updateProfileName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Cập nhật thành công');
    }

    public function updateProfileBio(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->description = $request->description;
        $user->save();

        return back()->with('success', 'Cập nhật mô tả thành công');
    }

    public function updateProfilePassword(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'password' => 'required|string|min:6|confirmed',
        ];

        // Nếu tài khoản không phải Google, yêu cầu nhập mật khẩu cũ
        if (!$user->google_id) {
            $rules['current_password'] = 'required';
        }

        $request->validate($rules);

        // Nếu là tài khoản thường thì kiểm tra mật khẩu cũ
        if (!$user->google_id && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        /** @var \App\Models\User $user */
        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('success', 'Cập nhật mật khẩu thành công');

    }



    public function updateProfileEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Cập nhật email thành công');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();
        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
        }

        /** @var \App\Models\User $user */
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
        $user->save();

        return back()->with('success', 'Cập nhật avatar thành công');
    }

    public function changePassword()
    {
        $user = Auth::user();
        return view('user.profile.change-password', compact('user'));
    }
}
