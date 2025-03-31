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
                <select id="bulk-action">
                    <option value="">Thao tác</option>
                    <option value="edit">Sửa</option>
                    <option value="delete">Xóa</option>
                </select>
                <button type="button" onclick="applyBulkAction()">
                    Áp dụng
                </button>
            </div>

            <div class="">
                {{-- Thanh tìm kiếm người dùng --}}
                <form method="GET" action="{{ route('customers.index') }}" class="search-form d-flex border"
                    style="width: 500px;">
                    <input class="p-2 border-0 w-100" style="outline: none;" name="search" type="search"
                        placeholder="Tìm kiếm người dùng" aria-label="Search" value="{{ request('search') }}">
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
                        <th><input type="checkbox" id="select-all" /></th>
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
                            <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}" /></td>
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

                            <select class="form-select d-none" onchange="handleAction(this, {{ $user->id }})">
                                <option value="">Thao tác</option>
                                <option value="edit">Sửa</option>
                                <option value="delete">Xóa</option>
                            </select>

                            <!-- Form xóa ẩn -->
                            <form id="delete-form-{{ $user->id }}" action="{{ route('customers.destroy', $user->id) }}"
                                method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script>
    function handleAction(select, userId) {
        if (select.value === "edit") {
            window.location.href = "/admin/customers/edit/" + userId;
        } else if (select.value === "delete") {
            if (confirm("Xác nhận xóa?")) {
                document.getElementById("delete-form-" + userId).submit();
            }
        }
        select.value = ""; // Reset lại select sau khi chọn
    }

    // Chức năng áp dụng thao tác cho nhiều user
    function applyBulkAction() {
        let selectedAction = document.getElementById("bulk-action").value;
        let selectedUsers = document.querySelectorAll(".user-checkbox:checked");

        if (selectedAction === "edit") {
            if (selectedUsers.length === 1) {
                let userId = selectedUsers[0].value;
                window.location.href = "/admin/customers/edit/" + userId;
            } else {
                alert("Vui lòng chọn đúng 1 người dùng để sửa!");
            }
        } else if (selectedAction === "delete") {
            if (selectedUsers.length > 0 && confirm("Xác nhận xóa những người dùng đã chọn?")) {
                selectedUsers.forEach(user => {
                    document.getElementById("delete-form-" + user.value).submit();
                });
            }
        }
    }

    // Chọn tất cả checkbox
    document.getElementById("select-all").addEventListener("change", function() {
        let checkboxes = document.querySelectorAll(".user-checkbox");
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>

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
