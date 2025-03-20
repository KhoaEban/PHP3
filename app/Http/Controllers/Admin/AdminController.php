<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }
        return view('admin.dashboard');
    }


    // public function dashboard()
    // {
    //     $totalProducts = Product::count();
    //     $totalCustomers = User::where('role', 'user')->count();
    //     $totalOrders = Order::count();
    //     $totalRevenue = Order::where('status', 'completed')->sum('total_price');

    //     // Lấy doanh thu 7 ngày gần nhất
    //     $dates = [];
    //     $revenues = [];
    //     for ($i = 6; $i >= 0; $i--) {
    //         $date = now()->subDays($i)->format('Y-m-d');
    //         $dates[] = $date;
    //         $revenues[] = Order::whereDate('created_at', $date)->where('status', 'completed')->sum('total_price');
    //     }

    //     return view('admin.dashboard', compact('totalProducts', 'totalCustomers', 'totalOrders', 'totalRevenue', 'dates', 'revenues'));
    // }
}
