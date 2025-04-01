@extends('layouts.sidebar_profile')

@section('content')
    <div class="content">
        <div class="header">
            <h2>Thông tin cá nhân</h2>
            <button class="close"><a href="{{ route('user.profile') }}"><i class="fas fa-times"></i></a></button>
        </div>
        <p>Quản lý thông tin cá nhân của bạn.</p>
        <h3>Thông tin cơ bản</h3>
        <p>Quản lý tên hiển thị, tên người dùng, bio và avatar của bạn.</p>
        <div class="info">
            <div class="item" data-bs-toggle="modal" data-bs-target="#editNameModal">
                <div>
                    <p>Họ và tên</p>
                    <p class="value text-dark">{{ $user->name }}</p>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="item" data-bs-toggle="modal" data-bs-target="#editNameModal">
                <div>
                    <p>Tên người dùng</p>
                    <p class="value text-dark">{{ $user->name }}</p>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="item">
                <div>
                    <p>Giới thiệu</p>
                    <p class="value text-dark">Chưa cập nhật</p>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="item">
                <div class="avatar">
                    <p>Ảnh đại diện</p>
                    <img src="{{ $user->avatar ?? 'https://placehold.co/96x96' }}" alt="User avatar placeholder"
                        width="40" height="40">
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh Sửa Tên -->
    <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="layoutss"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="editNameModalLabel">Cập nhật tên của bạn</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <p class="description">Tên sẽ được hiển thị trên trang cá nhân, trong các bình luận và bài viết của bạn.</p>
                <form action="{{ route('user.updateProfile') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Họ và tên</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="note">Tên khác được hiển thị bên cạnh họ và tên của bạn trên trang cá nhân.</p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<style>
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
