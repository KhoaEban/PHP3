<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
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
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->filled('min_price') ? (int) $request->min_price : 0;
            $maxPrice = $request->filled('max_price') ? (int) $request->max_price : 10000000;

            // Lọc sản phẩm chính và sản phẩm biến thể theo giá
            $query->where(function ($query) use ($minPrice, $maxPrice) {
                // Lọc sản phẩm chính
                $query->whereBetween('price', [$minPrice, $maxPrice])
                    // Lọc sản phẩm biến thể
                    ->orWhereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                        $q->whereBetween('price', [$minPrice, $maxPrice]);
                    });
            });
        }

        // Lọc theo sắp xếp
        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort == 'latest') {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $brands = Brand::all();  // Khai báo danh sách thương hiệu

        return view('user.products.product', compact('products', 'categories', 'brands', 'selectedCategory', 'selectedBrand'));
    }

    public function show($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Lấy tất cả các biến thể của sản phẩm
        $ProductVariants = $product->variants;

        // Lấy các ảnh phụ từ các biến thể
        $variantImages = [];
        foreach ($ProductVariants as $variant) {
            $variantImages[$variant->id] = $variant->images; // Lưu các ảnh phụ theo ID biến thể
        }

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

        return view('user.products.show', compact('product', 'relatedProducts', 'categoryName', 'brandName', 'ProductVariants', 'variantImages'));
    }

    public function getVariantImage(Request $request)
    {
        $type = $request->query('type');
        $value = $request->query('value');

        $variant = ProductVariant::where('variant_type', $type)
            ->where('variant_value', $value)
            ->first();

        if ($variant && $variant->images->isNotEmpty()) {
            return response()->json([
                'image_url' => asset('storage/' . $variant->images->first()->image_path),
            ]);
        }

        return response()->json(['image_url' => null]);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            return redirect()->route('user.products')->with('error', 'Vui lòng nhập từ khóa.');
        }

        $categories = Category::all(); // Truyền danh mục vào view
        $brands = Brand::all();
        $products = Product::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(12);

        return view('user.products.product', compact('products', 'categories', 'brands', 'query'));
    }


    public function boot()
    {
        Paginator::useBootstrap();
    }
}
