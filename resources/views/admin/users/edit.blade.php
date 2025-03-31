@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h2>Chỉnh sửa Người Dùng</h2>

        <form action="{{ route('customers.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Mật khẩu (Để trống nếu không muốn đổi)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Vai trò</label>
                        <select name="role" class="form-control">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Avatar</label>
                <input type="file" name="avatar" class="form-control">
                @if ($user->avatar)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" width="100">
                    </div>
                @endif
                @error('avatar')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection
