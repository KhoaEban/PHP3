<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->get();

        // Số lượng sản phẩm trong giỏ hàng
        $totalItems = $carts->sum(function ($cart) {
            return $cart->items->sum('quantity'); // Tính tổng số lượng từng sản phẩm
        });

        return view('user.cart.show', compact('carts', 'totalItems'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request, $productId)
    {
        // Kiểm tra sản phẩm có tồn tại
        $product = Product::findOrFail($productId);

        // Nếu người dùng chưa đăng nhập, giỏ hàng sẽ được lưu trong session
        $user = Auth::user(); // Nếu người dùng đã đăng nhập
        $cart = $user ? $user->cart : Session::get('cart');

        if (!$cart) {
            // Nếu chưa có giỏ hàng, tạo mới
            $cart = Cart::create(['user_id' => $user ? $user->id : null]);
            if (!$user) {
                // Lưu giỏ hàng vào session nếu người dùng chưa đăng nhập
                Session::put('cart', $cart);
            }
        }

        // Kiểm tra số lượng và giá
        $quantity = $request->input('quantity', 1);

        // Thêm sản phẩm vào giỏ hàng
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            // Nếu sản phẩm đã có trong giỏ hàng, chỉ cần tăng số lượng
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, tạo mới
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    public function showCart()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form');
        }

        // Lấy giỏ hàng kèm các sản phẩm
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        // Nếu giỏ hàng không tồn tại, tạo mới
        if (!$cart) {
            $cart = new Cart();
            $cart->items = collect(); // Đảm bảo có items để tránh lỗi
        }

        // Tính tổng tiền giỏ hàng
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('user.cart.show', compact('cart', 'total'));
    }

    public function removeFromCart($itemId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form');
        }

        // Tìm giỏ hàng của user
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->route('cart.show')->with('error', 'Giỏ hàng không tồn tại.');
        }

        // Tìm item trong giỏ hàng
        $item = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->first();

        if ($item) {
            $item->delete();
        }

        // Kiểm tra nếu giỏ hàng trống sau khi xóa item
        if ($cart->items()->count() == 0) {
            $cart->delete(); // Xóa luôn giỏ hàng
        }

        return redirect()->route('cart.show')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
}
