<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Order;      // Model đơn hàng của bạn
use App\Models\OrderItem;  // Model chi tiết đơn hàng
use App\Models\Cart;       // Giả sử bạn lưu giỏ hàng của user

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang checkout (form đặt hàng)
     */
    public function show()
    {
        $user = Auth::user();
        $cart = $user ? Cart::where('user_id', $user->id)->with('items.product.discount')->first() : null;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $totalBeforeDiscount = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
        $discountValue = 0;
        $discountPercentage = 0;
        foreach ($cart->items as $item) {
            if ($item->product->discount) {
                $discount = $item->product->discount;
                $itemDiscount = ($discount->type === 'percentage')
                    ? ($item->quantity * $item->product->price * $discount->amount / 100)
                    : min($discount->amount, $item->quantity * $item->product->price);
                $discountValue += $itemDiscount;
                $discountPercentage = ($discount->type === 'percentage') ? $discount->amount : (($discountValue / $totalBeforeDiscount) * 100);
            }
        }
        $totalAfterDiscount = $totalBeforeDiscount - $discountValue;

        $orderId = time();

        return view('user.checkout.show', compact(
            'cart',
            'totalBeforeDiscount',
            'discountValue',
            'discountPercentage',
            'totalAfterDiscount',
            'orderId'
        ));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cod,vnpay,momo', // Ensure 'momo' is here
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)
            ->with('items.product.discount', 'items.variant')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $totalBeforeDiscount = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
        $discountValue = 0;
        foreach ($cart->items as $item) {
            if ($item->product->discount) {
                $discount = $item->product->discount;
                $itemDiscount = ($discount->type === 'percentage')
                    ? ($item->quantity * $item->product->price * $discount->amount / 100)
                    : min($discount->amount, $item->quantity * $item->product->price);
                $discountValue += $itemDiscount;
            }
        }
        $totalAfterDiscount = $totalBeforeDiscount - $discountValue;
        $orderId = $request->input('order_id') ?: time();

        // Tạo đơn hàng
        $order = Order::create([
            'id'         => $orderId,
            'user_id'    => $user->id,
            'name'       => $request->name,
            'phone'      => $request->phone,
            'address'    => $request->address,
            'total'      => $totalAfterDiscount,
            'payment_method' => $request->payment_method, // 'momo' is passed here
            'status'     => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // Xử lý MoMo
        if ($request->payment_method === 'momo') {
            // Không xóa giỏ hàng ở đây, để MoMo callback xử lý
            return redirect()->route('momo.payment', ['amount' => $totalAfterDiscount, 'order_id' => $order->id]);
        }

        // Xoá giỏ hàng cho COD và VNPay
        $cart->items()->delete();
        $cart->delete();

        // Xử lý VNPay
        if ($request->payment_method === 'vnpay') {
            $vnp_TmnCode = config('vnpay.tmn_code');
            $vnp_HashSecret = config('vnpay.hash_secret');
            $vnp_Url = config('vnpay.url');
            $vnp_ReturnUrl = config('vnpay.return_url');

            $vnp_TxnRef = $order->id;
            $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->id;
            $vnp_Amount = $order->total * 100;
            $vnp_Locale = config('vnpay.locale');
            $vnp_IpAddr = $request->ip();
            $vnp_CreateDate = now()->format('YmdHis');

            $inputData = [
                "vnp_Version" => config('vnpay.version'),
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => config('vnpay.command'),
                "vnp_CreateDate" => $vnp_CreateDate,
                "vnp_CurrCode" => config('vnpay.currency'),
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => "other",
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef,
            ];

            ksort($inputData);
            $query = "";
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $paymentUrl = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

            session(['vnp_TxnRef' => $vnp_TxnRef]);

            return redirect()->away($paymentUrl);
        }

        // Xử lý COD
        if ($request->payment_method === 'cod') {
            foreach ($order->items as $item) {
                if ($item->variant_id && $item->variant) {
                    if ($item->variant->stock >= $item->quantity) {
                        $item->variant->decrement('stock', $item->quantity);
                        Log::info("Đã trừ {$item->quantity} biến thể ID: {$item->variant_id}");
                    } else {
                        Log::warning("Không đủ tồn kho cho biến thể ID: {$item->variant_id}");
                    }
                } else if ($item->product) {
                    if ($item->product->stock >= $item->quantity) {
                        $item->product->decrement('stock', $item->quantity);
                        Log::info("Đã trừ {$item->quantity} sản phẩm ID: {$item->product_id}");
                    } else {
                        Log::warning("Không đủ tồn kho cho sản phẩm ID: {$item->product_id}");
                    }
                }
            }
            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new PaymentSuccessMail($order));
                } catch (\Exception $e) {
                    Log::error('Lỗi gửi email: ' . $e->getMessage());
                }
            } else {
                Log::warning('Không có email để gửi thông báo cho đơn hàng ID: ' . $order->id);
            }
            $this->sendPaymentSuccessEmail($order);
            return redirect()->route('checkout.success')->with('success', 'Đơn hàng của bạn đã được đặt thành công. Vui lòng chờ xử lý.');
        }
    }

    public function vnpayCallback(Request $request)
    {
        Log::info('VNPay Callback:', $request->all());

        $orderId = $request->input('vnp_TxnRef');

        $order = Order::with('items.variant', 'items.product')->find($orderId);

        if (!$order) {
            Log::error("Không tìm thấy đơn hàng: $orderId");
            return redirect()->route('checkout.failed')->with('error', 'Không tìm thấy đơn hàng.');
        }

        Auth::loginUsingId($order->user_id);

        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TxnRef = $request->input('vnp_TxnRef');

        if ($vnp_ResponseCode === '00') {
            $order->update([
                'status'         => 'completed',
                'payment_method' => 'vnpay',
                'payment_id'     => $vnp_TxnRef,
                'paid_at'        => now(),
            ]);

            foreach ($order->items as $item) {
                // Nếu có biến thể thì ưu tiên trừ tồn kho biến thể
                if ($item->variant_id && $item->variant) {
                    if ($item->variant->stock >= $item->quantity) {
                        $item->variant->decrement('stock', $item->quantity);
                        Log::info("Đã trừ {$item->quantity} biến thể ID: {$item->variant_id}");
                    } else {
                        Log::warning("Không đủ tồn kho cho biến thể ID: {$item->variant_id}");
                    }
                }
                // Nếu không có biến thể thì trừ trực tiếp sản phẩm
                else if ($item->product) {
                    if ($item->product->stock >= $item->quantity) {
                        $item->product->decrement('stock', $item->quantity);
                        Log::info("Đã trừ {$item->quantity} sản phẩm ID: {$item->product_id}");
                    } else {
                        Log::warning("Không đủ tồn kho cho sản phẩm ID: {$item->product_id}");
                    }
                }
            }
            try {
                Mail::to(Auth::user()->email)->send(new PaymentSuccessMail($order));
            } catch (\Exception $e) {
                Log::error('Lỗi gửi email: ' . $e->getMessage());
            }
            $this->sendPaymentSuccessEmail($order);
            return redirect()->route('checkout.success')->with('success', 'Thanh toán VNPay thành công!');
        } else {
            $order->update([
                'status'         => 'failed',
                'payment_method' => 'vnpay',
                'payment_id'     => $vnp_TxnRef,
            ]);

            return redirect()->route('checkout.failed')->with('error', 'Thanh toán không thành công.');
        }
    }

    protected function sendPaymentSuccessEmail($order)
    {
        $user = Auth::user();
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new PaymentSuccessMail($order));
                Log::info('Gửi email thành công cho đơn hàng ID: ' . $order->id);
            } catch (\Exception $e) {
                Log::error('Lỗi gửi email cho đơn hàng ID: ' . $order->id . ': ' . $e->getMessage());
            }
        } else {
            Log::warning('Không có email để gửi thông báo cho đơn hàng ID: ' . $order->id);
        }
    }

    protected function reduceStock($order)
    {
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->decrement('stock', $item->quantity);
            } elseif ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }
    }

    public function success()
    {
        return view('user.checkout.success');
    }
    public function failed()
    {
        return view('user.checkout.failed');
    }
}
