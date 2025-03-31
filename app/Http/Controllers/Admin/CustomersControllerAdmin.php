<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class CustomersControllerAdmin extends Controller
{
    // Hiển thị danh sách người dùng
    public function index(Request $request)
    {
        // Lấy giá trị tìm kiếm từ form
        $search = $request->input('search');

        // Truy vấn danh sách người dùng, áp dụng tìm kiếm nếu có
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
        })->paginate(10); // Phân trang 10 kết quả mỗi trang

        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'status' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        // Xử lý upload avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Tạo mới người dùng
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'avatar' => $avatarPath,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ]);

        return redirect()->route('customers.index')->with('success', 'Người dùng đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa thông tin người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Xác thực dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'status' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cập nhật avatar nếu có
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            // Giữ nguyên avatar nếu không có file mới
            $avatarPath = $user->avatar;
        }

        // Cập nhật thông tin người dùng, giữ nguyên google_id nếu có
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => $request->role,
            'status' => $request->status,
            'avatar' => $avatarPath,
            'google_id' => $user->google_id,  // Giữ nguyên google_id
        ]);

        return redirect()->route('customers.index')->with('success', 'Người dùng đã được cập nhật thành công!');
    }
}
