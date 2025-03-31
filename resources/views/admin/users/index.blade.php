@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <div class="header">
            <h1>Người dùng</h1>
            <div class="buttons mx-2">
                <a href="{{ route('customers.create') }}" class="text-white text-decoration-none d-flex align-items-center">
                    <button>
                        <i class="fas fa-plus me-1"></i>
                        Thêm người dùng
                    </button>
                </a>
            </div>
        </div>
        <div class="filters">
            <div class="">
                <select>
                    <option>
                        Thao tác
                    </option>
                    <option>
                        <a href="#">
                            Sửa
                        </a>
                    </option>
                    <option>
                        <a href="#">
                            Xóa
                        </a>
                    </option>
                    {{-- <a href="{{ route('customers.edit', $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('customers.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                    </form> --}}
                </select>
                <button type="submit">
                    Áp dụng
                </button>
            </div>
            <div class="">
                {{-- Thanh tìm kiếm người dùng --}}
                <form method="GET" action="#" class="search-form d-flex border px-3" style="width: 500px;">
                    <input class="p-2 border-0 w-100" style="outline: none;" name="search" type="search"
                        placeholder="Tìm kiếm sản phẩm" aria-label="Search" value="{{ request('search') }}">
                    <button class="btn bg-transparent text-muted border-0" style="outline: none;" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="responsive-table">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" /></th>
                        <th>Avatar</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td><input type="checkbox" /></td>
                            <td>
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
                                    alt="Avatar" width="50" height="50" class="rounded-circle">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                @if ($user->status)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Bị khóa</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        {{-- Phân trang --}} {{-- <div class="d-flex justify-content-center gap-3">
            {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div> --}}
    </div>
@endsection
<style>
    .responsive-table {
        overflow-x: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 14px;
    }

    input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .search-form {
        border-radius: 5px;
        background: white;
    }

    .search-form input {
        border: none;
    }

    .search-form button {
        cursor: pointer;
    }
</style>
