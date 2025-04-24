<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;

class ProductVariantController extends Controller
{
    public function index($productId)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        $product = Product::with(['variants', 'categories', 'brands'])->findOrFail($productId);

        return view('admin.products.product_variants.index_productVariant', compact('product'));
    }

    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        $variants = ProductVariant::where('product_id', $productId)->get();

        return view('admin.products.product_variants.create_productVariant', compact('product', 'variants'));
    }

    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'variant_type' => 'required|string|max:255',
            'variant_value' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0|max:10000000', // Thêm max:10000000
            'stock' => 'required|integer|min:0|max:10000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'variant_type.required' => 'Loại biến thể là bắt buộc.',
            'variant_type.max' => 'Loại biến thể không được vượt quá 255 ký tự.',
            'variant_value.required' => 'Giá trị biến thể là bắt buộc.',
            'variant_value.max' => 'Giá trị biến thể không được vượt quá 255 ký tự.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được nhỏ hơn 0.',
            'price.max' => 'Giá không được vượt quá 10,000,000 VNĐ.',
            'stock.required' => 'Số lượng là bắt buộc.',
            'stock.integer' => 'Số lượng phải là số nguyên.',
            'stock.min' => 'Số lượng không được nhỏ hơn 0.',
            'stock.max' => 'Số lượng không được vượt quá 10,000.',
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, svg hoặc webp.',
            'images.*.max' => 'Hình ảnh không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($productId);

        $variant = new ProductVariant();
        $variant->product_id = $product->id;
        $variant->variant_type = $request->variant_type;
        $variant->variant_value = $request->variant_value;
        $variant->price = $request->price;
        $variant->stock = $request->stock;
        $variant->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('productVariants', 'public');
                ProductVariantImage::create([
                    'product_variant_id' => $variant->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Biến thể sản phẩm đã được thêm thành công.');
    }

    public function edit($variantId)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        $variant = ProductVariant::with('images')->findOrFail($variantId);
        $product = Product::findOrFail($variant->product_id);

        return view('admin.products.product_variants.edit_productVariant', compact('variant', 'product'));
    }

    public function update(Request $request, $variantId)
    {
        $validator = Validator::make($request->all(), [
            'variant_type' => 'required|string|max:255',
            'variant_value' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0|max:10000000', // Thêm max:10000000
            'stock' => 'required|integer|min:0|max:10000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'variant_type.required' => 'Loại biến thể là bắt buộc.',
            'variant_type.max' => 'Loại biến thể không được vượt quá 255 ký tự.',
            'variant_value.required' => 'Giá trị biến thể là bắt buộc.',
            'variant_value.max' => 'Giá trị biến thể không được vượt quá 255 ký tự.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được nhỏ hơn 0.',
            'price.max' => 'Giá không được vượt quá 10,000,000 VNĐ.',
            'stock.required' => 'Số lượng là bắt buộc.',
            'stock.integer' => 'Số lượng phải là số nguyên.',
            'stock.min' => 'Số lượng không được nhỏ hơn 0.',
            'stock.max' => 'Số lượng không được vượt quá 10,000.',
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, svg hoặc webp.',
            'images.*.max' => 'Hình ảnh không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $variant = ProductVariant::with('images')->findOrFail($variantId);

        $variant->variant_type = $request->variant_type;
        $variant->variant_value = $request->variant_value;
        $variant->price = $request->price;
        $variant->stock = $request->stock;
        $variant->save();

        if ($request->hasFile('images')) {
            foreach ($variant->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('productVariants', 'public');
                ProductVariantImage::create([
                    'product_variant_id' => $variant->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('product_variants.index', $variant->product_id)
            ->with('success', 'Cập nhật biến thể thành công, ảnh đã được thay thế.');
    }

    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        $variant = ProductVariant::findOrFail($id);

        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $variant->delete();

        return redirect()->back()->with('success', 'Biến thể sản phẩm đã được xóa thành công.');
    }
}
