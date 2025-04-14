<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Tổng doanh thu
        $totalRevenue = Order::sum('total');

        // Đơn hàng chờ xử lý
        $pendingOrders = Order::where('status', 'pending')->count();

        // Tổng sản phẩm bán ra
        $totalItemsSold = OrderItem::sum('quantity');

        // Kiểm tra xem order_items có variant_id không
        $hasVariantsInOrders = OrderItem::whereNotNull('variant_id')->exists();

        // Tính số sản phẩm sắp hết hàng
        if ($hasVariantsInOrders) {
            // Nếu có variant_id, tính cả sản phẩm đơn và sản phẩm biến thể
            $lowStockProductsCount = Product::where('stock', '<', 10)->count();
            $lowStockVariantsCount = ProductVariant::where('stock', '<', 10)->count();
            $lowStockProducts = $lowStockProductsCount + $lowStockVariantsCount;
        } else {
            // Nếu không có variant_id, chỉ tính sản phẩm đơn
            $lowStockProducts = Product::where('stock', '<', 10)->count();
        }

        // Danh sách đơn hàng
        $orders = Order::with('user')->get();

        // Thống kê số đơn hàng theo ngày
        $ordersByDay = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Thống kê doanh thu theo ngày
        $revenueByDay = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Chuẩn bị dữ liệu cho biểu đồ
        $dates = ['2025-04-08', '2025-04-09', '2025-04-10', '2025-04-11'];
        $orderCounts = [];
        $revenueTotals = [];
        foreach ($dates as $date) {
            $orderCounts[] = isset($ordersByDay[$date]) ? $ordersByDay[$date] : 0;
            $revenueTotals[] = isset($revenueByDay[$date]) ? $revenueByDay[$date] : 0;
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'pendingOrders',
            'totalItemsSold',
            'lowStockProducts',
            'orders',
            'dates',
            'orderCounts',
            'revenueTotals'
        ));
    }
}
