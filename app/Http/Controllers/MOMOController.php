<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Cart;

class MoMoController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $amount = $request->input('amount');
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            return redirect()->route('user.checkout')->with('error', 'Không có quyền truy cập đơn hàng này.');
        }

        $payUrl = $this->generateMomoUrl($order, $amount);

        Log::info("Redirecting to MoMo: $payUrl");

        return redirect()->away($payUrl);
    }

    private function generateMomoUrl($order, $amount)
    {
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $endpoint = env('MOMO_URL', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $returnUrl = route('momo.callback');

        if (empty($partnerCode) || empty($accessKey) || empty($secretKey)) {
            Log::error('MoMo configuration is missing in .env');
            abort(500, 'Cấu hình MoMo không hợp lệ');
        }

        $orderId = $order->id . 'MOMOPAY' . rand(10000, 99999);
        $orderInfo = "Thanh toán đơn hàng #$order->id";
        $requestId = $partnerCode . time();
        $requestType = "payWithATM"; // Hoặc "payWithMethod" nếu cần
        $extraData = "";

        $rawSignature = "accessKey=$accessKey" .
            "&amount=$amount" .
            "&extraData=$extraData" .
            "&ipnUrl=$returnUrl" .
            "&orderId=$orderId" .
            "&orderInfo=$orderInfo" .
            "&partnerCode=$partnerCode" .
            "&redirectUrl=$returnUrl" .
            "&requestId=$requestId" .
            "&requestType=$requestType";

        $signature = hash_hmac("sha256", $rawSignature, $secretKey);

        $data = [
            "partnerCode" => $partnerCode,
            "accessKey" => $accessKey,
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $orderInfo,
            "redirectUrl" => $returnUrl,
            "ipnUrl" => $returnUrl,
            "extraData" => $extraData,
            "requestType" => $requestType,
            "signature" => $signature,
            "lang" => "vi"
        ];

        $response = $this->execPostRequest($endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (!empty($result['payUrl'])) {
            return $result['payUrl'];
        } else {
            Log::error('MoMo payment URL creation failed', ['response' => $result]);
            abort(500, 'Không thể tạo URL thanh toán MoMo');
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function momoCallback(Request $request)
    {
        $data = $request->all();
        $secretKey = env('MOMO_SECRET_KEY');

        Log::info('MoMo Callback Data: ', $data);

        if (!isset($data['orderId']) || !isset($data['resultCode'])) {
            Log::error('Invalid MoMo callback data', $data);
            return redirect()->route('checkout.failed')->with('error', 'Dữ liệu callback không hợp lệ');
        }

        $orderIdParts = explode('MOMOPAY', $data['orderId']);
        $originalOrderId = $orderIdParts[0];

        $rawSignature = "accessKey=" . env('MOMO_ACCESS_KEY') .
            "&amount={$data['amount']}" .
            "&extraData={$data['extraData']}" .
            "&message={$data['message']}" .
            "&orderId={$data['orderId']}" .
            "&orderInfo={$data['orderInfo']}" .
            "&orderType={$data['orderType']}" .
            "&partnerCode={$data['partnerCode']}" .
            "&payType={$data['payType']}" .
            "&requestId={$data['requestId']}" .
            "&responseTime={$data['responseTime']}" .
            "&resultCode={$data['resultCode']}" .
            "&transId={$data['transId']}";

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        $order = Order::with('items.variant', 'items.product')->find($originalOrderId);

        if (!$order) {
            Log::error("Không tìm thấy đơn hàng: $originalOrderId");
            return redirect()->route('checkout.failed')->with('error', 'Không tìm thấy đơn hàng.');
        }

        Auth::loginUsingId($order->user_id);

        if ($calculatedSignature === $data['signature'] && $data['resultCode'] == '0') {
            // Payment successful
            $order->update([
                'status'         => 'completed',
                'payment_id'     => $data['transId'],
                'paid_at'        => now(),
            ]);

            // Giảm tồn kho
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

            // Xóa giỏ hàng
            Cart::where('user_id', Auth::id())->delete();

            return redirect()->route('checkout.success')->with('success', 'Thanh toán MoMo thành công!');
        } else {
            // Payment failed
            $order->update([
                'status' => 'failed',
            ]);

            return redirect()->route('checkout.failed')->with('error', 'Thanh toán MoMo không thành công!');
        }
    }
}
