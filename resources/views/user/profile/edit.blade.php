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
            <div class="item">
                <div>
                    <p>Họ và tên</p>
                    <p class="value text-dark">{{ $user->name }}</p>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="item">
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
                    <img src="{{ $user->avatar ?? 'https://placehold.co/96x96' }}" alt="User avatar placeholder" width="40" height="40">
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>
@endsection
