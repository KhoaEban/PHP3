<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán thành công</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0"
        style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td
                style="background-color: #4CAF50; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Xác nhận thanh toán thành công</h1>
            </td>
        </tr>
        <!-- Content -->
        <tr>
            <td style="padding: 20px;">
                <p style="font-size: 16px; margin: 0 0 10px;">Kính chào <strong>{{ $order->name }}</strong>,</p>
                <p style="font-size: 16px; margin: 0 0 20px;">Cảm ơn bạn đã đặt hàng! Dưới đây là chi tiết đơn hàng của
                    bạn:</p>

                <!-- Order Info -->
                <h2 style="font-size: 18px; color: #4CAF50; margin: 20px 0 10px;">Thông tin đơn hàng</h2>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 14px;">
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Mã đơn hàng:</td>
                        <td>{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Thời gian đặt hàng:</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Phương thức thanh toán:</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                    </tr>
                </table>

                <!-- Customer Info -->
                <h2 style="font-size: 18px; color: #4CAF50; margin: 20px 0 10px;">Thông tin khách hàng</h2>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 14px;">
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Họ tên:</td>
                        <td>{{ $order->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Số điện thoại:</td>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Địa chỉ giao hàng:</td>
                        <td>{{ $order->address }}</td>
                    </tr>
                </table>

                <!-- Order Items -->
                <h2 style="font-size: 18px; color: #4CAF50; margin: 20px 0 10px;">Chi tiết sản phẩm</h2>
                <table width="100%" border="0" cellpadding="10" cellspacing="0"
                    style="font-size: 14px; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th style="text-align: left; border-bottom: 1px solid #ddd;">Sản phẩm</th>
                            <th style="text-align: center; border-bottom: 1px solid #ddd;">Số lượng</th>
                            <th style="text-align: right; border-bottom: 1px solid #ddd;">Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td style="border-bottom: 1px solid #ddd;">
                                    @if ($item->product)
                                        {{ $item->product->title ?? 'Sản phẩm không xác định' }}
                                        @if ($item->variant_id && $item->variant)
                                            <br><small style="color: #777;">({{ $item->variant->variant_type }}: {{ $item->variant->variant_value }})</small>
                                        @endif
                                    @else
                                        Sản phẩm không xác định
                                    @endif
                                </td>
                                <td style="text-align: center; border-bottom: 1px solid #ddd;">{{ $item->quantity }}
                                </td>
                                <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                    {{ number_format($item->price) }} VND
                                    @if ($item->product && $item->product->discount)
                                        <br><small style="color: #e74c3c; text-decoration: line-through;">
                                            {{ number_format($item->product->price) }} VND
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Order Summary -->
                <h2 style="font-size: 18px; color: #4CAF50; margin: 20px 0 10px;">Tổng quan đơn hàng</h2>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 14px;">
                    <tr>
                        <td style="width: 70%; text-align: right;">Tổng tiền hàng:</td>
                        <td style="text-align: right;">
                            {{ number_format($order->items->sum(fn($item) => $item->quantity * $item->price)) }} VND
                        </td>
                    </tr>
                    @if (
                        $order->items->sum(fn($item) => $item->product && $item->product->discount
                                ? ($item->quantity * $item->product->price * $item->product->discount->amount) / 100
                                : 0) > 0)
                        <tr>
                            <td style="text-align: right;">Giảm giá:</td>
                            <td style="text-align: right; color: #e74c3c;">
                                -{{ number_format($order->items->sum(fn($item) => $item->product && $item->product->discount ? ($item->quantity * $item->product->price * $item->product->discount->amount) / 100 : 0)) }}
                                VND
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Tổng thanh toán:</td>
                        <td style="text-align: right; font-weight: bold; color: #4CAF50;">
                            {{ number_format($order->total) }} VND</td>
                    </tr>
                </table>

                <!-- Footer -->
                <p style="font-size: 14px; margin: 20px 0 0; color: #777;">
                    Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất có thể. Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ
                    với chúng tôi qua:
                </p>
                <p style="font-size: 14px; color: #777; margin: 5px 0;">
                    <strong>Email:</strong> khoaebanypk03641@gmail.com<br>
                    <strong>Hotline:</strong> 0389 195 765<br>
                    <strong>Website:</strong> <a href="https://VinaBook.com"
                        style="color: #4CAF50; text-decoration: none;">vinabook.com</a>
                </p>
            </td>
        </tr>
        <!-- Bottom Footer -->
        <tr>
            <td
                style="background-color: #f9f9f9; padding: 10px; text-align: center; font-size: 12px; color: #777; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                © {{ date('Y') }} Your Store. All rights reserved.
            </td>
        </tr>
    </table>
</body>

</html>
