<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductControllerUser extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $selectedCategory = null;
        $selectedBrand = null;

        // Lọc theo danh mục
        if ($request->has('category')) {
            $selectedCategory = Category::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $query->whereHas('categories', function ($q) use ($selectedCategory) {
                    $q->where('categories.id', $selectedCategory->id);
                });
            }
        }

        // Lọc theo thương hiệu
        if ($request->has('brand')) {
            $selectedBrand = Brand::where('slug', $request->brand)->first();
            if ($selectedBrand) {
                $query->whereHas('brands', function ($q) use ($selectedBrand) {
                    $q->where('brands.id', $selectedBrand->id);
                });
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
        $brands = Brand::all();

        return view('user.products.product', compact('products', 'categories', 'brands', 'selectedCategory', 'selectedBrand'));
    }

    public function show($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Lấy danh mục đầu tiên của sản phẩm (nếu có)
        $category = $product->categories()->first();
        $brand = $product->brands()->first();
        $categoryName = $category ? $category->name : 'Chưa có danh mục';
        $brandName = $brand ? $brand->name : 'Chưa có tác giả';

        // Lấy các sản phẩm liên quan cùng danh mục
        $relatedProducts = collect(); // Mặc định là rỗng nếu không có danh mục
        if ($category) {
            $relatedProducts = Product::whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
                ->where('id', '!=', $product->id)
                ->limit(4)
                ->get();
        }

        return view('user.products.show', compact('product', 'relatedProducts', 'categoryName', 'brandName'));
    }


    public function search(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            return redirect()->route('user.products')->with('error', 'Vui lòng nhập từ khóa.');
        }

        $categories = Category::all(); // Truyền danh mục vào view
        $products = Product::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(12);

        return view('user.products.product', compact('products', 'categories', 'query'));
    }


    public function boot()
    {
        Paginator::useBootstrap();
    }
}
