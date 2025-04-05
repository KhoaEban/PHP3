@extends('layouts.sidebar_profile')

@section('content')
    <div class="content">
        <div class="header">
            <h2>Mật khẩu và bảo mật</h2>
            <button class="close"><a href="{{ route('user.profile') }}"><i class="fas fa-times"></i></a></button>
        </div>
        <p>Quản lý mật khẩu và cài đặt bảo mật.</p>
        <h3>Đăng nhập & khôi phục</h3>
        <p>Quản lý mật khẩu</p>
        <div class="info py-2">
            <div class="item" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                <div>
                    <p>Đặt lại mật khẩu</p>
                    <p class="value text-dark">{{ $user->name }}</p>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    <!-- Modal thay đổi mật khẩu -->
    <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="editPasswordModalLabel">Đổi mật khẩu</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <form action="{{ route('user.updatePassword') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if (!$user->google_id)
                            <div class="form-group">
                                <label for="current_password">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" id="current_password" class="form-control">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="password">Mật khẩu mới</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Nhập lại mật khẩu mới</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<style>
    .form_code {
        position: relative;
        background: #fff;
        border: 1.5px solid var(--divider-color);
        border-radius: 44px;
        overflow: hidden;
        height: 44px;
        display: flex;
    }

    .form_code input {
        display: block;
        width: 100%;
        height: 100%;
        padding: 12px 42px 12px 20px;
        border: none;
        outline: none;
        font-size: 14px;
        background-color: transparent;
        background-color: #16182329;
    }

    .form_code input:focus {
        display: block;
        width: 100%;
        height: 100%;
        padding: 12px 42px 12px 20px;
        border: none;
        outline: none;
        font-size: 14px;
        background-color: transparent;
        background-color: #16182329;
    }

    .form_code button {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        padding: 0;
        width: 100px;
        /* height: 36px; */
        border: none;
        outline: none;
        background-color: transparent;
        cursor: pointer;
        font-size: 14px;
    }

    .layoutss {
        position: absolute;
        z-index: -1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .layoutss::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -1;
        background: #fff;
    }

    .layoutss::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -1;
        opacity: .08;
        background-image: radial-gradient(#ffffff40, #fff0 40%), radial-gradient(hsl(44, 100%, 66%) 30%, hsl(338, 68%, 65%), hsla(338, 68%, 65%, .4) 41%, transparent 52%), radial-gradient(hsl(272, 100%, 60%) 37%, transparent 46%), linear-gradient(155deg, transparent 65%, hsl(142, 70%, 49%) 95%), linear-gradient(45deg, #0065e0, #0f8bff);
        background-size: 200% 200%, 285% 500%, 285% 500%, cover, cover;
        background-position: bottom left, 109% 68%, 109% 68%, center, center;
    }

    .modal-content {
        border-radius: 10px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: none;
        padding-bottom: 0;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
    }

    .btn-close {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-close:hover {
        color: #374151;
    }

    .description {
        color: #6b7280;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        color: #374151;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }

    .note {
        color: #6b7280;
        margin-bottom: 16px;
    }

    .btn-primary {
        background: linear-gradient(to right, #3b82f6, #06b6d4);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #2563eb, #0891b2);
    }

    .btn-primary:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
</style>

