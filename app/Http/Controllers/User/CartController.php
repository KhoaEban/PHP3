<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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

    public function showCart()
    {
        $user = Auth::user();

        if ($user) {
            // Lấy giỏ hàng từ database nếu user đã đăng nhập
            $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        } else {
            // Nếu chưa đăng nhập, tạo giỏ hàng trống để tránh lỗi
            $cart = new Cart();
            $cart->items = collect(); // Đảm bảo `$cart->items` là một tập hợp trống
        }

        $total = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

        return view('user.cart.show', compact('cart', 'total'));
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
