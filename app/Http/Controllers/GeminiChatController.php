<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        Log::info('Chat send request received', ['input' => $request->all()]);
        try {
            $user = Auth::user();
            $name = $user ? ($user->name ?? 'bạn') : 'bạn';
            $userMessage = trim($request->input('message', ''));

            if (empty($userMessage)) {
                Log::warning('Empty message received');
                return response()->json(['error' => 'Vui lòng nhập tin nhắn.'], 400);
            }

            if (Auth::check() && $user) {
                Log::info('Saving user message', ['user_id' => $user->id]);
                ChatMessage::create([
                    'user_id' => $user->id,
                    'message' => $userMessage,
                    'is_user' => true,
                ]);
            }

            $prompt = <<<PROMPT
                Bạn là một trợ lý ảo thông minh cho một cửa hàng sách trực tuyến và vui vẻ và thân thiện. Phân tích câu hỏi và trả về JSON đúng định dạng: { "intent": "..." }.
                Không thêm giải thích hoặc ký tự thừa.
                Các intent hợp lệ gồm: 'product_count', 'category_count', 'list_products', 'product_info', 'order_status', 'payment_count', 'other'.
                Câu: "$userMessage"
            PROMPT;

            $aiIntentResponse = $this->queryGemini($prompt);
            $intent = $this->extractIntent($aiIntentResponse);

            Log::info('Intent detected', ['intent' => $intent]);

            if (!Auth::check() && $intent !== 'other') {
                return $this->reply('Bạn cần đăng nhập để sử dụng chatbot.', $name, false);
            }

            $intentHandlers = [
                'product_count' => fn() => $this->handleProductCount($name),
                'category_count' => fn() => $this->handleCategoryCount($name),
                'payment_count' => fn() => $this->handlePaymentCount($name),
                'list_products' => fn() => $this->listProducts($name),
                'product_info' => fn() => $this->productInfo($userMessage, $name),
                'order_status' => fn() => $this->orderStatus($user, $name),
                'other' => fn() => $this->chatWithGemini($userMessage, $name),
            ];

            $handler = $intentHandlers[$intent] ?? $intentHandlers['other'];
            return $handler();
        } catch (\Exception $e) {
            Log::error('Chat send error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra. Vui lòng thử lại sau.'], 500);
        }
    }

    private function reply(string $message, string $name, bool $isUser = false)
    {
        if (Auth::check() && !$isUser) {
            try {
                $user = Auth::user();
                if ($user) {
                    Log::info('Saving bot message', ['user_id' => $user->id]);
                    ChatMessage::create([
                        'user_id' => $user->id,
                        'message' => $message,
                        'is_user' => false,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to save bot message', ['message' => $e->getMessage()]);
            }
        }
        return response()->json(['reply' => "Chào $name! $message"]);
    }

    private function handleProductCount(string $name)
    {
        try {
            $count = Product::count();
            return $this->reply("Hiện tại, chúng tôi có $count sách.", $name, false);
        } catch (\Exception $e) {
            Log::error('Product count error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể đếm số sách.', $name, false);
        }
    }

    private function handleCategoryCount(string $name)
    {
        try {
            $count = Category::count();
            return $this->reply("Hiện tại, chúng tôi có $count danh mục sách.", $name, false);
        } catch (\Exception $e) {
            Log::error('Category count error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể đếm danh mục sách.', $name, false);
        }
    }

    private function handlePaymentCount(string $name)
    {
        try {
            $count = Payment::count();
            return $this->reply("Hiện tại, chúng tôi có $count giao dịch thanh toán.", $name, false);
        } catch (\Exception $e) {
            Log::error('Payment count error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể đếm giao dịch thanh toán.', $name, false);
        }
    }

    private function listProducts(string $name, string $searchTerm = '')
    {
        try {
            $query = Product::select('title', 'price', 'description', 'image')
                ->where('status', '1');

            if (!empty($searchTerm)) {
                $keywords = array_filter(explode(' ', trim($searchTerm)));
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('title', 'LIKE', '%' . $keyword . '%');
                    }
                });
            }

            $products = $query->take(10)->get();

            if ($products->isEmpty()) {
                $message = empty($searchTerm)
                    ? 'Hiện tại chưa có sách nào.'
                    : "Không tìm thấy sách nào phù hợp với từ khóa '$searchTerm'.";
                return $this->reply($message, $name, false);
            }

            $response = empty($searchTerm)
                ? "Các sách hiện có:\n"
                : "Các sách hiện có với từ khóa '$searchTerm':\n";
            foreach ($products as $index => $product) {
                $price = number_format($product->price, 0, ',', '.') . ' VNĐ';
                $description = $product->description ?: 'Không có mô tả';
                $image = $product->image && file_exists(storage_path('app/public/' . $product->image))
                    ? asset('storage/products' . $product->image)
                    : 'Không có hình ảnh';

                $response .= ($index + 1) . ". {$product->title}\n";
                $response .= "- Giá: $price\n";
                $response .= "- Mô tả: $description\n";
                $response .= "- Hình ảnh: $image\n\n";
            }

            return $this->reply($response, $name, false);
        } catch (\Exception $e) {
            Log::error('List products error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể tải danh sách sách.', $name, false);
        }
    }

    private function productInfo(string $message, string $name)
    {
        try {
            $keywords = explode(' ', strtolower(trim($message)));
            $product = Product::where('status', '1')
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhere('title', 'LIKE', "%{$keyword}%");
                    }
                })
                ->first();

            if (!$product) {
                $prompt = <<<PROMPT
                    Bạn là trợ lý của cửa hàng sách trực tuyến. Người dùng đang hỏi về thông tin một cuốn sách.
                    Yêu cầu:
                    - Giải thích rõ ràng, ngắn gọn, dễ hiểu.
                    - Nếu không rõ sách cụ thể, yêu cầu làm rõ.
                    Câu hỏi của người dùng:
                    "$message"
                PROMPT;

                $aiResponse = $this->queryGemini($prompt);
                $reply = $this->extractText($aiResponse) ?? 'Xin lỗi, mình chưa hiểu cuốn sách bạn đang hỏi. Vui lòng cung cấp thêm chi tiết.';
                return $this->reply($reply, $name, false);
            }

            $reply = "Thông tin về sách '{$product->title}':\n- Giá: " . number_format($product->price) . " VNĐ\n- Mô tả: {$product->description}\n- Tình trạng: " . ($product->stock > 0 ? "Còn hàng ({$product->stock})" : "Hết hàng");
            return $this->reply($reply, $name, false);
        } catch (\Exception $e) {
            Log::error('Product info error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể lấy thông tin sách.', $name, false);
        }
    }

    private function orderStatus($user, string $name)
    {
        try {
            if (!$user || !Auth::check()) {
                return $this->reply('Bạn cần đăng nhập để xem trạng thái đơn hàng.', $name, false);
            }

            $orders = Order::where('user_id', $user->id)->latest()->take(5)->get();
            if ($orders->isEmpty()) {
                return $this->reply('Bạn chưa có đơn hàng nào.', $name, false);
            }

            $response = "Trạng thái đơn hàng gần đây:\n";
            foreach ($orders as $index => $order) {
                $response .= ($index + 1) . ". Đơn hàng #{$order->id} - Tổng: " . number_format($order->total) . " VNĐ - Trạng thái: {$order->status}\n";
            }

            return $this->reply($response, $name, false);
        } catch (\Exception $e) {
            Log::error('Order status error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể kiểm tra trạng thái đơn hàng.', $name, false);
        }
    }

    private function chatWithGemini(string $message, string $name)
    {
        try {
            if (!Auth::check()) {
                return $this->reply('Bạn cần đăng nhập để sử dụng đầy đủ chức năng của chatbot.', $name, false);
            }

            $response = $this->queryGemini($message);
            $reply = $this->extractText($response) ?? 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn.';

            return $this->reply($reply, $name, false);
        } catch (\Exception $e) {
            Log::error('Chat with Gemini error', ['message' => $e->getMessage()]);
            return $this->reply('Không thể xử lý câu hỏi.', $name, false);
        }
    }

    private function queryGemini(string $prompt)
    {
        try {
            $apiUrl = env('GEMINI_API_URL');
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiUrl || !$apiKey) {
                Log::error('Missing Gemini API configuration');
                return null;
            }

            $response = Http::timeout(10)->post($apiUrl . '?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $json = $response->json();
            if (empty($json['candidates'])) {
                Log::warning('Empty candidates in Gemini response', ['response' => $json]);
                return null;
            }

            return $json;
        } catch (\Exception $e) {
            Log::error('Gemini API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    private function extractText($data): ?string
    {
        if (empty($data['candidates'][0]['content']['parts'][0]['text'])) {
            Log::warning('No text in Gemini response', ['data' => $data]);
            return null;
        }
        return $data['candidates'][0]['content']['parts'][0]['text'];
    }

    private function extractIntent($aiResponse): string
    {
        if (!$aiResponse) {
            Log::warning('Invalid Gemini response for intent', ['response' => $aiResponse]);
            return 'other';
        }

        $text = $this->extractText($aiResponse);
        if (!$text) {
            Log::warning('No text for intent extraction', ['response' => $aiResponse]);
            return 'other';
        }

        preg_match('/\{.*?\}/s', $text, $matches);
        if (empty($matches[0])) {
            Log::warning('No JSON intent found', ['text' => $text]);
            return 'other';
        }

        $json = json_decode($matches[0], true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($json['intent'])) {
            Log::warning('Failed to parse intent JSON', ['json' => $matches[0]]);
            return 'other';
        }

        return $json['intent'];
    }

    public function history()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Bạn cần đăng nhập để xem lịch sử chat.'], 401);
            }

            $user = Auth::user();
            $history = $user->chatMessages()->orderBy('created_at')->get();
            return response()->json([
                'history' => $history->map(function ($message) {
                    return [
                        'message' => (string) $message->message,
                        'is_user' => (bool) $message->is_user,
                        'created_at' => $message->created_at->toIso8601String(),
                    ];
                })->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Chat history error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Không thể tải lịch sử chat.'], 500);
        }
    }
}
