<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Discount;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            // Lấy giỏ hàng từ database nếu người dùng đã đăng nhập
            $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        } else {
            // Lấy giỏ hàng từ session nếu chưa đăng nhập
            $cart = Session::get('cart', []);
        }

        return view('user.cart.show', compact('cart'));
    }

    public function addToCart(Request $request, $productId)
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        // Tìm sản phẩm
        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id'); // ID biến thể nếu có

        // Kiểm tra tồn kho
        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            if ($variant->stock < $quantity) {
                return redirect()->back()->with('error', 'Sản phẩm ' . $product->title . ' (biến thể: ' . $variant->variant_type . ' - ' . $variant->variant_value . ') không đủ hàng. Còn lại: ' . $variant->stock . ' sản phẩm.');
            }
        } else {
            if ($product->stock < $quantity) {
                return redirect()->back()->with('error', 'Sản phẩm ' . $product->title . ' không đủ hàng. Còn lại: ' . $product->stock . ' sản phẩm.');
            }
        }

        // Lấy hoặc tạo giỏ hàng
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Kiểm tra sản phẩm đã có trong giỏ hàng
        if ($variantId) {
            $cartItem = $cart->items()->where('product_id', $productId)->where('variant_id', $variantId)->first();
        } else {
            $cartItem = $cart->items()->where('product_id', $productId)->first();
        }

        if ($cartItem) {
            // Kiểm tra tồn kho khi cập nhật số lượng
            $newQuantity = $cartItem->quantity + $quantity;
            if ($variantId && $variant->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Sản phẩm ' . $product->title . ' (biến thể: ' . $variant->variant_type . ' - ' . $variant->variant_value . ') không đủ hàng. Còn lại: ' . $variant->stock . ' sản phẩm.');
            } elseif (!$variantId && $product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Sản phẩm ' . $product->title . ' không đủ hàng. Còn lại: ' . $product->stock . ' sản phẩm.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Tạo mới mục trong giỏ hàng
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $variantId ? $variant->price : $product->price,
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    public function applyPromoCode(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
        ]);

        // Lấy mã giảm giá
        $discount = Discount::where('code', $request->discount_code)->first();

        if (!$discount) {
            return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ!');
        }

        // Kiểm tra ngày hết hạn
        if ($discount->expires_at) {
            $expiredThreshold = now()->subDays(5); // Ngày cách đây 5 ngày

            if ($discount->expires_at->lte(now()) && $discount->expires_at->gte($expiredThreshold)) {
                return redirect()->back()->with('error', 'Mã giảm giá đã hết hạn hoặc sắp hết hạn trong vòng 5 ngày qua!');
            }
        }

        // Kiểm tra điều kiện áp dụng mã giảm giá (ví dụ: tổng giá trị đơn hàng tối thiểu)
        $cart = Cart::where('user_id', Auth::id())->with('items')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn trống, không thể áp dụng mã giảm giá.');
        }

        $totalBeforeDiscount = $cart->items->sum(fn($item) => $item->quantity * $item->price);

        if ($discount->min_order_value && $totalBeforeDiscount < $discount->min_order_value) {
            return redirect()->back()->with('error', 'Đơn hàng cần tối thiểu ' . number_format($discount->min_order_value, 0, ',', '.') . ' VNĐ để áp dụng mã giảm giá.');
        }

        // Lưu mã giảm giá vào giỏ hàng
        $cart->update(['discount_id' => $discount->id]);

        return redirect()->back()->with('success', 'Mã giảm giá đã được áp dụng!');
    }

    public function showCart()
    {
        $user = Auth::user();
        $cart = $user ? Cart::where('user_id', $user->id)->with('items.product', 'discount')->first() : null;

        if (!$cart) {
            $cart = new Cart();
            $cart->items = collect();
        }

        $totalBeforeDiscount = 0;
        $totalAfterDiscount = 0;
        $discountValue = 0;
        $discountPercentage = 0;

        if ($cart->items->isEmpty()) {
            return view('user.cart.show', compact('cart', 'totalBeforeDiscount', 'totalAfterDiscount', 'discountValue', 'discountPercentage'));
        }

        $totalBeforeDiscount = $cart->items->sum(fn($item) => $item->quantity * $item->price);
        $totalAfterDiscount = $totalBeforeDiscount;

        if ($cart->discount) {
            $discount = $cart->discount;
            foreach ($cart->items as $item) {
                $itemDiscount = ($discount->type === 'percentage')
                    ? ($item->quantity * $item->price * $discount->amount / 100)
                    : min($discount->amount, $item->quantity * $item->price);

                $discountValue += $itemDiscount;
                $totalAfterDiscount -= $itemDiscount;

                $discountPercentage = ($discount->type === 'percentage')
                    ? $discount->amount
                    : (($discountValue / $totalBeforeDiscount) * 100);
            }
        }

        return view('user.cart.show', compact('cart', 'totalBeforeDiscount', 'totalAfterDiscount', 'discountValue', 'discountPercentage'));
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($request->item_id);

        if ($item->cart->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thay đổi sản phẩm này.'], 403);
        }

        // Kiểm tra tồn kho
        $product = $item->product;
        $stock = $item->variant_id ? $item->variant->stock : $product->stock;
        if ($request->quantity > $stock) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho. Còn lại: ' . $stock . ' sản phẩm.'], 400);
        }

        $item->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    public function removeFromCart($itemId)
    {
        $user = Auth::user();
        $cart = $user ? Cart::where('user_id', $user->id)->first() : Session::get('cart');

        if ($user) {
            $item = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->first();
            if ($item) {
                $item->delete();
            }
            if ($cart->items()->count() == 0) {
                $cart->delete();
            }
        } else {
            if (isset($cart[$itemId])) {
                unset($cart[$itemId]);
                Session::put('cart', $cart);
            }
        }

        return redirect()->route('cart.show')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function clearCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cart->items()->delete();
                $cart->delete();
            }
        } else {
            Session::forget('cart');
        }

        return redirect()->route('cart.show')->with('success', 'Giỏ hàng đã được làm mới!');
    }
}
