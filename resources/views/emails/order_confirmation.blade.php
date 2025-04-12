<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
</head>
<body>
    <h1>Xin chào {{ $order->name }},</h1>
    <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi! Dưới đây là chi tiết đơn hàng của bạn:</p>

    <h2>Đơn hàng #{{ $order->id }}</h2>
    <p><strong>Phương thức thanh toán:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</p>
    <p><strong>Tổng cộng:</strong> {{ number_format($order->total, 0, ',', '.') }} VND</p>

    <h3>Chi tiết sản phẩm</h3>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất có thể. Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
    <p>Trân trọng,<br>Cửa hàng của bạn</p>
</body>
</html>