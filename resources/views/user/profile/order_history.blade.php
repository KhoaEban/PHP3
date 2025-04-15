@extends('layouts.navbar_user')

@section('content')
    <div class="container mt-4">
        <a href="{{ route('user.profile') }}" class="btn btn-secondary mt-4"><i class="fas fa-arrow-left"></i> Quay lại hồ
            sơ</a>
        <h2 class="mt-4">Lịch Sử Đặt Hàng</h2>

        @if ($orders->isEmpty())
            <p class="no-orders">Bạn chưa có đơn hàng nào.</p>
        @else
            <table class="order-table">
                <thead>
                    <tr>
                        <th>MÃ ĐƠN HÀNG</th>
                        <th>NGÀY ĐẶT</th>
                        <th>TỔNG TIỀN</th>
                        <th>PHƯƠNG THỨC THANH TOÁN</th>
                        <th>TRẠNG THÁI</th>
                        <th>HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                            <td>
                                {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng' : ($order->payment_method == 'vnpay' ? 'VNPay' : 'MoMo') }}
                            </td>
                            <td>
                                @if ($order->status == 'unpaid')
                                    <span class="status-unpaid">Chưa thanh toán</span>
                                @elseif($order->status == 'pending')
                                    <span class="status-pending">Chờ xử lý</span>
                                @elseif($order->status == 'completed')
                                    <span class="status-completed">Hoàn thành</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="status-cancelled">Đã hủy</span>
                                @elseif($order->status == 'failed')
                                    <span class="status-failed">Thất bại</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('user.order.details', $order->id) }}" class="btn-details">Xem chi tiết</a>
                                @if ($order->status == 'failed' || $order->status == 'unpaid')
                                    <a href="{{ route('order.buy-again', $order->id) }}" class="btn-pay">Mua lại</a>
                                    <a href="#" class="btn-cancel"
                                        onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn hủy đơn hàng này?')) { document.getElementById('cancel-form-{{ $order->id }}').submit(); }">Hủy
                                        đơn hàng</a>
                                    <form id="cancel-form-{{ $order->id }}"
                                        action="{{ route('order.cancel', $order->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('POST')
                                    </form>
                                @elseif($order->status == 'cancelled')
                                    <a href="{{ route('order.buy-again', $order->id) }}" class="btn-pay">Mua lại</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination d-flex justify-content-center align-items-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
<style>
    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 14px;
    }

    .order-table th,
    .order-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .order-table th {
        background-color: #f4f4f4;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-unpaid {
        color: #ff0000;
        /* Đỏ, khớp với hình ảnh */
    }

    .status-pending {
        color: #ff9900;
        /* Cam, khớp với hình ảnh */
    }

    .status-completed {
        color: #008000;
        /* Xanh lá, khớp với hình ảnh */
    }

    .status-cancelled {
        color: #ff0000;
        /* Đỏ */
    }

    .status-failed {
        color: #ff0000;
        /* Đỏ, khớp với hình ảnh */
    }

    .btn-details,
    .btn-pay,
    .btn-cancel {
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 4px;
        display: inline-block;
        font-size: 14px;
        margin-right: 5px;
    }

    .btn-details {
        background-color: #007bff;
        color: white;
    }

    .btn-details:hover {
        background-color: #0056b3;
    }

    .btn-pay {
        background-color: #28a745;
        color: white;
    }

    .btn-pay:hover {
        background-color: #218838;
    }

    .btn-cancel {
        background-color: #dc3545;
        color: white;
    }

    .btn-cancel:hover {
        background-color: #c82333;
    }

    .no-orders {
        text-align: center;
        margin-top: 20px;
        color: #666;
    }

    .pagination {
        margin-top: 20px;
    }

    .pagination p {
        margin: 0;
        margin-right: 10px;
    }
</style>
