<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;


class UserController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('user.index', compact('categories'));
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = $user ? $user->orders()->with('items.product', 'items.variant.images', 'items.product.brands', 'items.product.categories')->get() : collect();

        // Lấy danh sách sản phẩm đã xem
        if ($user) {
            $viewedProducts = $user ? $user->viewedProducts()->with('product')->get()->pluck('product')->filter() : Product::whereIn('id', session()->get('viewed_products', []))->take(10)->get();
        } else {
            $viewedProductIds = session()->get('viewed_products', []);
            $viewedProducts = Product::whereIn('id', $viewedProductIds)->take(10)->get();
        }

        return view('user.profile.index', compact('user', 'orders', 'viewedProducts'));
    }

    public function orderDetails($order_id)
    {
        $user = Auth::user();

        $order = Order::where('id', $order_id)
            ->where('user_id', $user->id)
            ->with([
                'items.product',
                'items.variant.images',
                'items.product.brands',
                'items.product.categories',
                'reviews.user' // Tải quan hệ user cho reviews
            ])
            ->firstOrFail();

        return view('user.profile.order_details', compact('order'));
    }

    public function filterReviews($order_id, $rating)
    {
        $user = Auth::user();
        $order = Order::where('id', $order_id)
            ->where('user_id', $user->id)
            ->with(['reviews.user', 'reviews.variant'])
            ->firstOrFail();

        $reviews = $rating === 'all' ? $order->reviews : $order->reviews->where('rating', $rating);

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                return [
                    'user_name' => $review->user->name,
                    'avatar' => $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://placehold.co/50x50',
                    'date' => $review->created_at->format('Y-m-d H:i'),
                    'variant' => $review->variant ? 'Phân loại: ' . $review->variant->variant_value : '',
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                ];
            }),
            'avg_rating' => number_format($order->reviews->avg('rating'), 1),
            'total_reviews' => $order->reviews->count(),
            'rating_counts' => [
                5 => $order->reviews->where('rating', 5)->count(),
                4 => $order->reviews->where('rating', 4)->count(),
                3 => $order->reviews->where('rating', 3)->count(),
                2 => $order->reviews->where('rating', 2)->count(),
                1 => $order->reviews->where('rating', 1)->count(),
            ],
        ]);
    }

    public function storeReview(Request $request, $order_id)
    {
        $user = Auth::user();

        // Kiểm tra đơn hàng thuộc về người dùng
        $order = Order::where('id', $order_id)->where('user_id', $user->id)->firstOrFail();

        // Validate dữ liệu
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra sản phẩm có trong đơn hàng không
        $itemExists = $order->items->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id ?? null)
            ->first();
        if (!$itemExists) {
            return back()->with('error', 'Sản phẩm không thuộc đơn hàng này.');
        }

        // Kiểm tra đã đánh giá chưa
        $existingReview = $order->reviews->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id ?? null)
            ->first();
        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        // Lưu đánh giá
        Review::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }

    public function editProfile()
    {
        $user = Auth::user(); // Lấy thông tin người dùng đang đăng nhập

        return view('user.profile.edit', compact('user'));
    }

    public function updateProfileName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Cập nhật thành công');
    }

    public function updateProfileBio(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->description = $request->description;
        $user->save();

        return back()->with('success', 'Cập nhật mô tả thành công');
    }

    public function updateProfilePassword(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'password' => 'required|string|min:6|confirmed',
        ];

        // Nếu tài khoản không phải Google, yêu cầu nhập mật khẩu cũ
        if (!$user->google_id) {
            $rules['current_password'] = 'required';
        }

        $request->validate($rules);

        // Nếu là tài khoản thường thì kiểm tra mật khẩu cũ
        if (!$user->google_id && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        /** @var \App\Models\User $user */
        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('success', 'Cập nhật mật khẩu thành công');
    }



    public function updateProfileEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Cập nhật email thành công');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();
        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
        }

        /** @var \App\Models\User $user */
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
        $user->save();

        return back()->with('success', 'Cập nhật avatar thành công');
    }

    public function changePassword()
    {
        $user = Auth::user();
        return view('user.profile.change-password', compact('user'));
    }
}
