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
        // Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id'); // Kiểm tra nếu có biến thể

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            $cartItem = $cart->items()->where('product_id', $productId)->where('variant_id', $variantId)->first();
        } else {
            $cartItem = $cart->items()->where('product_id', $productId)->first();
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
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
            'discount_code' => 'required|string|exists:discounts,code',
        ]);

        $discount = Discount::where('code', $request->discount_code)->first();

        if (!$discount) {
            return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ!');
        }

        // Cập nhật mã giảm giá cho từng sản phẩm trong giỏ hàng
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        foreach ($cart->items as $item) {
            if (!$item->product->discount_id) {
                $item->product->update(['discount_id' => $discount->id]);
            }
        }

        return redirect()->back()->with('success', 'Mã giảm giá đã được áp dụng!');
    }

    public function showCart()
    {
        $user = Auth::user();
        $cart = $user ? Cart::where('user_id', $user->id)->with('items.product.discount')->first() : null;

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

        $totalBeforeDiscount = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
        $totalAfterDiscount = $totalBeforeDiscount;
        $discountValue = 0;
        $discountPercentage = 0;

        foreach ($cart->items as $item) {
            if ($item->product->discount) {
                $discount = $item->product->discount;

                $itemDiscount = ($discount->type === 'percentage')
                    ? ($item->quantity * $item->product->price * $discount->amount / 100)
                    : min($discount->amount, $item->quantity * $item->product->price);

                $discountValue += $itemDiscount;
                $totalAfterDiscount -= $itemDiscount;

                // Cập nhật phần trăm giảm giá (dùng cho hiển thị)
                $discountPercentage = ($discount->type === 'percentage') ? $discount->amount : (($discountValue / $totalBeforeDiscount) * 100);
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
