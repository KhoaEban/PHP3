@extends('layouts.navbar_user')

@section('content')
    <div class="text-center mt-5">
        <h2>🎉 Cảm ơn bạn đã đặt hàng!</h2>
        <p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Về trang chủ</a>
    </div>
@endsection
