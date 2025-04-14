<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Discount;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        $discounts = Discount::all();

        $query = Product::with(['categories', 'brands', 'variants']);

        if ($request->has('search') && !empty($request->search)) {
            $search = trim($request->search);
            $keywords = explode(' ', $search);
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhereRaw("title REGEXP ?", ["[[:<:]]{$word}[[:>:]]"])
                        ->orWhereRaw("description REGEXP ?", ["[[:<:]]{$word}[[:>:]]"]);
                }
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.products.index', compact('products', 'discounts'));
    }

    public function importForm()
    {
        return view('admin.products.import_product');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('excel_file'));
            return redirect()->route('products.index')->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'brand_ids' => 'required|array',
            'title' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0', // Validate giá sản phẩm
            'stock' => 'required|integer|min:0', // Validate số lượng sản phẩm
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        // Tạo sản phẩm mới
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $imagePath,
            'price' => $request->price, // Lưu giá sản phẩm
            'stock' => $request->stock, // Lưu số lượng sản phẩm
        ]);

        // Liên kết danh mục và thương hiệu
        $product->categories()->sync($request->category_ids);
        $product->brands()->sync($request->brand_ids);

        // Nếu sản phẩm có biến thể, lưu biến thể với giá và số lượng tương ứng
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $product->variants()->create([
                    'price' => $variant['price'] ?? $product->price, // Dùng giá của sản phẩm chính nếu không có giá biến thể
                    'stock' => $variant['stock'] ?? $product->stock, // Dùng số lượng của sản phẩm chính nếu không có số lượng biến thể
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'brand_ids' => 'required|array',
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0', // Validate giá sản phẩm
            'stock' => 'required|integer|min:0', // Validate số lượng sản phẩm
        ]);

        $data = $request->only([
            'title',
            'description',
            'status',
            'price',  // Cập nhật giá
            'stock',  // Cập nhật số lượng
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Cập nhật sản phẩm
        $product->update($data);

        // Cập nhật danh mục và thương hiệu
        $product->categories()->sync($request->category_ids);
        $product->brands()->sync($request->brand_ids);

        // Nếu có thêm biến thể mới, xử lý các biến thể
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Kiểm tra nếu biến thể đã có trong cơ sở dữ liệu, nếu chưa thì tạo mới
                $existingVariant = $product->variants()->find($variant['id']);
                if ($existingVariant) {
                    // Cập nhật biến thể nếu đã tồn tại
                    $existingVariant->update([
                        'price' => $variant['price'] ?? $product->price, // Nếu không có giá, lấy giá sản phẩm chính
                        'stock' => $variant['stock'] ?? $product->stock, // Nếu không có số lượng, lấy số lượng sản phẩm chính
                    ]);
                } else {
                    // Tạo mới biến thể nếu chưa có
                    $product->variants()->create([
                        'price' => $variant['price'] ?? $product->price, // Dùng giá sản phẩm chính nếu không có
                        'stock' => $variant['stock'] ?? $product->stock, // Dùng số lượng sản phẩm chính nếu không có
                        // Các thông tin khác của biến thể
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật.');
    }

    public function addDiscountPage(Product $product)
    {
        $discounts = Discount::all(); // Lấy danh sách tất cả mã giảm giá
        return view('admin.products.add_discount', compact('product', 'discounts'));
    }

    public function applyDiscount(Request $request, Product $product)
    {
        $request->validate([
            'discount_id' => 'required|exists:discounts,id',
        ]);

        $product->update(['discount_id' => $request->discount_id]);

        return redirect()->route('products.index')->with('success', 'Mã giảm giá đã được áp dụng!');
    }

    public function destroy(Product $product)
    {
        Storage::delete('public/' . $product->image);
        $product->categories()->detach();
        $product->brands()->detach();
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa!');
    }
}
