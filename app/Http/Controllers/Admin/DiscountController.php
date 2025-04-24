<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discounts,code',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'expires_at' => 'nullable|date',
        ]);

        Discount::create($request->all());

        if ($request->expires_at <= now()) {
            
        }

        return redirect()->route('discounts.index')->with('success', 'Mã giảm giá đã được thêm!');
    }

    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'code' => 'required|unique:discounts,code,' . $discount->id,
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'expires_at' => 'nullable|date',
        ]);

        $discount->update($request->all());

        return redirect()->route('discounts.index')->with('success', 'Mã giảm giá đã được cập nhật!');
    }

    public function destroy(Discount $discount)
    {
        // Kiểm tra xem mã giảm giá có đang được sử dụng không
        // if ($discount->orders()->exists()) {
        //     return redirect()->route('discounts.index')->with('error', 'Mã giảm giá này không thể xóa vì đang được sử dụng!');
        // }

        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Mã giảm giá đã bị xóa!');
    }
}
