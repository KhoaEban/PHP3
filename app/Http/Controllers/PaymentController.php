<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;

class PaymentController extends Controller
{
    public function showPaymentForm($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $product = Product::where('slug', $slug)->firstOrFail();
        return view('user.checkout.success', compact('product'));
    }

    public function processPayment(Request $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $product = Product::where('slug', $slug)->firstOrFail();
        session(['product_slug' => $slug]);

        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'vnpay') {
            // Cấu hình VNPay
            $vnp_TmnCode = config('vnpay.tmn_code');
            $vnp_HashSecret = config('vnpay.hash_secret');
            $vnp_Url = config('vnpay.url');
            $vnp_ReturnUrl = route('vnpay.callback');

            $vnp_TxnRef = uniqid();
            session(['vnp_TxnRef' => $vnp_TxnRef]);
            $vnp_OrderInfo = "Thanh toán sản phẩm: " . $product->name;
            $vnp_Amount = $product->price * 100;
            $vnp_Locale = config('vnpay.locale');
            $vnp_IpAddr = $request->ip();
            $vnp_CreateDate = date('YmdHis');

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
                "vnp_OrderType" => "billpayment",
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

            $vnp_Url = $vnp_Url . "?" . $query;
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

            return redirect()->away($vnp_Url);
        }

        // Thanh toán bằng phương thức khác
        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'amount' => $product->price,
            'payment_method' => $paymentMethod,
            'status' => 'completed',
            'transaction_id' => uniqid(),
        ]);

        return redirect()->route('checkout.success')->with('success', 'Bạn đã mua sản phẩm thành công.');
    }

    public function vnpayCallback(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_Amount = $request->input('vnp_Amount') / 100;
        $vnp_TransactionNo = $request->input('vnp_TransactionNo');

        $productSlug = session('product_slug');
        $product = Product::where('slug', $productSlug)->firstOrFail();

        if ($vnp_ResponseCode == '00') {
            Payment::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'amount' => $vnp_Amount,
                'payment_method' => 'vnpay',
                'status' => 'completed',
                'transaction_id' => $vnp_TxnRef,
            ]);

            return redirect()->route('payment.success');
        } else {
            Payment::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'amount' => $vnp_Amount,
                'payment_method' => 'vnpay',
                'status' => 'failed',
                'transaction_id' => $vnp_TxnRef,
            ]);

            return redirect()->route('payment.failure');
        }
    }
}
