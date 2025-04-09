@extends('layouts.navbar_user')

@section('content')
    <div class="container">
        <p>Đang chuyển hướng đến trang thanh toán...</p>
        <form id="paymentForm" action="{{ route('payment.create') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="amount" value="{{ $totalAfterDiscount }}">
            <!-- Thêm các trường ẩn khác nếu cần, ví dụ: bank_code -->
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('paymentForm').submit();
</script>
@endsection
