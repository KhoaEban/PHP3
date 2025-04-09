@extends('layouts.navbar_user') {{-- Hoặc layout bạn đang dùng --}}

@section('title', 'Thanh toán thất bại')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <img src="{{ asset('images/payment_failed.png') }}" alt="Thanh toán thất bại" style="max-width: 150px;">
        <h2 class="mt-4 text-danger">Thanh toán thất bại</h2>
        @if (session('error'))
            <p class="text-muted">{{ session('error') }}</p>
        @else
            <p class="text-muted">Đã xảy ra lỗi trong quá trình thanh toán. Vui lòng thử lại sau.</p>
        @endif

        <a href="{{ route('cart.show') }}" class="btn btn-outline-primary mt-3">
            Quay lại giỏ hàng
        </a>
    </div>
</div>
@endsection
