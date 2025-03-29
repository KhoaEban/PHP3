<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductControllerUser extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $selectedCategory = null;

        // Lọc theo danh mục
        if ($request->has('category')) {
            $selectedCategory = Category::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        // Sắp xếp
        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort == 'latest') {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('user.products.product', compact('products', 'categories', 'selectedCategory'));
    }


    public function show($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Lấy tên danh mục của sản phẩm
        $categoryName = $product->category ? $product->category->name : 'Chưa có danh mục';

        // Lấy các sản phẩm liên quan có cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('user.products.show', compact('product', 'relatedProducts', 'categoryName'));
    }





    public function boot()
    {
        Paginator::useBootstrap();
    }
}
