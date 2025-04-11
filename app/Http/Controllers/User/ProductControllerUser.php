<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

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
        // Lấy sản phẩm kèm biến thể & ảnh biến thể
        $product = Product::where('slug', $slug)
            ->with(['categories', 'brands', 'variants.images'])
            ->firstOrFail();

        // Ghi lại sản phẩm đã xem
        if (Auth::check()) {
            $user = Auth::user();
            $exists = $user->viewedProducts()->where('product_id', $product->id)->exists();
            if (!$exists) {
                $user->viewedProducts()->create(['product_id' => $product->id]);
            } else {
                $user->viewedProducts()->where('product_id', $product->id)->update(['updated_at' => now()]);
            }
            $viewedCount = $user->viewedProducts()->count();
            if ($viewedCount > 10) {
                $user->viewedProducts()->orderBy('updated_at', 'asc')->first()->delete();
            }
        } else {
            $viewed = session()->get('viewed_products', []);
            if (!in_array($product->id, $viewed)) {
                array_unshift($viewed, $product->id);
                if (count($viewed) > 10) {
                    array_pop($viewed);
                }
                session()->put('viewed_products', $viewed);
            }
        }

        // Lấy danh mục & thương hiệu đầu tiên của sản phẩm
        $categoryName = optional($product->categories->first())->name ?? 'Chưa có danh mục';
        $brandName = optional($product->brands->first())->name ?? 'Chưa có tác giả';

        // Xử lý ảnh biến thể để hiển thị
        $variantImages = [];
        foreach ($product->variants as $variant) {
            $variantImages[$variant->id] = $variant->images;
        }

        // Lấy sản phẩm liên quan
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->whereHas('categories', function ($q) use ($product) {
                    $q->whereIn('categories.id', $product->categories->pluck('id'));
                })
                    ->orWhereHas('brands', function ($q) use ($product) {
                        $q->whereIn('brands.id', $product->brands->pluck('id'));
                    });
            })
            ->limit(4)
            ->get();

        return view('user.products.show', compact('product', 'relatedProducts', 'categoryName', 'brandName', 'variantImages'));
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
