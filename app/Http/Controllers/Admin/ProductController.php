<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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
        $categories = Category::all();
        $brands = Brand::all();

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

        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category); // Chỉ định rõ bảng
            });
        }

        if ($request->has('brand') && !empty($request->brand)) {
            $query->whereHas('brands', function ($q) use ($request) {
                $q->where('brands.id', $request->brand); // Chỉ định rõ bảng
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.products.index', compact('products', 'discounts', 'categories', 'brands'));
    }



    public function importForm()
    {
        //  'id','desc','price', 'desc' ,'created_at', 'desc'
        return view('admin.products.import_product');
    }

    public function importProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ], [
            'excel_file.required' => 'Vui lòng chọn file Excel.',
            'excel_file.mimes' => 'File phải có định dạng xlsx hoặc xls.',
            'excel_file.max' => 'File không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Excel::import(new ProductsImport, $request->file('excel_file'));
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được nhập thành công!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Lỗi khi nhập sản phẩm: ' . $e->getMessage());
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
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'brand_ids' => 'required|array',
            'brand_ids.*' => 'exists:brands,id',
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0|max:10000000', // Thêm max:10000000
            'stock' => 'required|integer|min:0|max:10000',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ], [
            'category_ids.required' => 'Vui lòng chọn ít nhất một danh mục.',
            'category_ids.*.exists' => 'Danh mục được chọn không hợp lệ.',
            'brand_ids.required' => 'Vui lòng chọn ít nhất một thương hiệu.',
            'brand_ids.*.exists' => 'Thương hiệu được chọn không hợp lệ.',
            'title.required' => 'Tên sản phẩm là bắt buộc.',
            'title.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'image.required' => 'Vui lòng chọn hình ảnh chính.',
            'image.image' => 'Hình ảnh phải là file ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'price.max' => 'Giá sản phẩm không được vượt quá 10,000,000 VNĐ.', // Thông báo lỗi tùy chỉnh
            'stock.required' => 'Số lượng sản phẩm là bắt buộc.',
            'stock.integer' => 'Số lượng sản phẩm phải là số nguyên.',
            'stock.min' => 'Số lượng sản phẩm không được nhỏ hơn 0.',
            'stock.max' => 'Số lượng không được quá 10,000.',
            'status.required' => 'Trạng thái sản phẩm là bắt buộc.',
            'status.in' => 'Trạng thái sản phẩm không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $imagePath,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        $product->categories()->sync($request->category_ids);
        $product->brands()->sync($request->brand_ids);

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $product->variants()->create([
                    'price' => $variant['price'] ?? $product->price,
                    'stock' => $variant['stock'] ?? $product->stock,
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
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'brand_ids' => 'required|array',
            'brand_ids.*' => 'exists:brands,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0|max:10000000', // Thêm max:10000000
            'stock' => 'required|integer|min:0|max:10000',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ], [
            'category_ids.required' => 'Vui lòng chọn ít nhất một danh mục.',
            'category_ids.*.exists' => 'Danh mục được chọn không hợp lệ.',
            'brand_ids.required' => 'Vui lòng chọn ít nhất một thương hiệu.',
            'brand_ids.*.exists' => 'Thương hiệu được chọn không hợp lệ.',
            'title.required' => 'Tên sản phẩm là bắt buộc.',
            'title.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'image.image' => 'Hình ảnh phải là file ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'price.max' => 'Giá sản phẩm không được vượt quá 10,000,000 VNĐ.', // Thông báo lỗi tùy chỉnh
            'stock.required' => 'Số lượng sản phẩm là bắt buộc.',
            'stock.integer' => 'Số lượng sản phẩm phải là số nguyên.',
            'stock.min' => 'Số lượng sản phẩm không được nhỏ hơn 0.',
            'stock.max' => 'Số lượng không được quá 10,000.',
            'status.required' => 'Trạng thái sản phẩm là bắt buộc.',
            'status.in' => 'Trạng thái sản phẩm không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'title',
            'description',
            'status',
            'price',
            'stock',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        $product->categories()->sync($request->category_ids);
        $product->brands()->sync($request->brand_ids);

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $existingVariant = $product->variants()->find($variant['id']);
                if ($existingVariant) {
                    $existingVariant->update([
                        'price' => $variant['price'] ?? $product->price,
                        'stock' => $variant['stock'] ?? $product->stock,
                    ]);
                } else {
                    $product->variants()->create([
                        'price' => $variant['price'] ?? $product->price,
                        'stock' => $variant['stock'] ?? $product->stock,
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật.');
    }

    public function addDiscountPage(Product $product)
    {
        $discounts = Discount::all();
        return view('admin.products.add_discount', compact('product', 'discounts'));
    }

    public function applyDiscount(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discounts,id',
        ], [
            'discount_id.required' => 'Vui lòng chọn mã giảm giá.',
            'discount_id.exists' => 'Mã giảm giá không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Lấy thông tin mã giảm giá
        $discount = Discount::find($request->discount_id);

        // Kiểm tra nếu có ngày hết hạn
        if ($discount->expires_at) {
            $expiredThreshold = now()->subDays(5); // Ngày cách đây 5 ngày

            // Kiểm tra nếu mã giảm giá hết hạn hôm nay hoặc trước 5 ngày
            if ($discount->expires_at->lte(now()) && $discount->expires_at->gte($expiredThreshold)) {
                return redirect()->back()->withErrors([
                    'discount_id' => 'Mã giảm giá đã hết hạn hoặc sắp hết hạn trong vòng 5 ngày qua.'
                ])->withInput();
            }
        }

        // Áp dụng mã giảm giá
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
