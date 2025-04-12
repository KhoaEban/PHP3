@extends('layouts.navbar_user')

@section('content')
    <div class="checkout-container mt-4">
        <form action="{{ route('checkout.confirm') }}" method="POST">
            @csrf
            <div class="row">
                <!-- THÔNG TIN THANH TOÁN -->
                <div class="col-md-6 payment-info">
                    <h3 class="section-title">THÔNG TIN THANH TOÁN</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                        <textarea name="address" class="form-control" id="address" rows="3" required></textarea>
                    </div>
                </div>

                <!-- ĐƠN HÀNG CỦA BẠN -->
                <div class="col-md-6 order-summary">
                    <h3 class="section-title">ĐƠN HÀNG CỦA BẠN</h3>

                    <table class="order-table">
                        @foreach ($cart->items as $item)
                            <tr>
                                <td>{{ $item->product->title }} x {{ $item->quantity }}</td>
                                <td class="text-end">
                                    {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VND
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Tạm tính</td>
                            <td class="text-end">{{ number_format($totalBeforeDiscount, 0, ',', '.') }} VND</td>
                        </tr>
                        @if ($discountValue > 0)
                            <tr>
                                <td>Giảm giá ({{ round($discountPercentage, 2) }}%)</td>
                                <td class="text-end text-success">- {{ number_format($discountValue, 0, ',', '.') }} VND
                                </td>
                            </tr>
                        @endif
                        <tr class="total-row">
                            <td><strong>Tổng cộng</strong></td>
                            <td class="text-end text-danger fw-bold">
                                {{ number_format($totalAfterDiscount, 0, ',', '.') }} VND
                            </td>
                        </tr>
                    </table>

                    <div class="payment-methods mt-3">
                        <p>Chọn phương thức thanh toán:</p>
                        <select name="payment_method" class="form-select" required>
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="vnpay">VNPAY</option>
                            <option value="momo">MOMO</option>
                        </select>

                        <!-- Nút đặt hàng nằm bên dưới phần lựa chọn phương thức -->
                        <button type="submit" class="bg-dark text-white d-block text-center py-2 w-100 border-0 mt-3">
                            ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

<style>
    .checkout-container {
        max-width: 960px;
        margin: auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .order-table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .total-row td {
        font-size: 18px;
        font-weight: bold;
    }

    .payment-methods {
        text-align: center;
        margin-top: 20px;
    }

    .payment-methods img {
        max-width: 100%;
    }

    .btn-success {
        padding: 10px;
        font-size: 16px;
        text-transform: uppercase;
    }
</style>
