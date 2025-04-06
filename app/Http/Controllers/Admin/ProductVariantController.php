<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;


class ProductVariantController extends Controller
{
    public function index($productId)
    {
        // Kiểm tra quyền (nếu cần)
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Lấy sản phẩm và các biến thể
        $product = Product::with(['variants', 'categories', 'brands'])->findOrFail($productId);

        return view('admin.products.product_variants.index_productVariant', compact('product'));
    }

    public function create($productId)
    {
        // Lấy sản phẩm cha
        $product = Product::findOrFail($productId);

        // Lấy danh sách biến thể của sản phẩm
        $variants = ProductVariant::where('product_id', $productId)->get();

        return view('admin.products.product_variants.create_productVariant', compact('product', 'variants'));
    }

    // Phương thức thêm biến thể cho sản phẩm
    public function store(Request $request, $productId)
    {
        $request->validate([
            'variant_type' => 'required|string|max:255',
            'variant_value' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Lấy sản phẩm cha
        $product = Product::findOrFail($productId);

        // Thêm biến thể
        $variant = new ProductVariant();
        $variant->product_id = $product->id;
        $variant->variant_type = $request->variant_type;
        $variant->variant_value = $request->variant_value;
        $variant->price = $request->price;
        $variant->stock = $request->stock;
        $variant->save();

        // Lưu hình ảnh nếu có
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
        // Kiểm tra quyền
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Tìm biến thể và sản phẩm cha
        $variant = ProductVariant::with('images')->findOrFail($variantId);
        $product = Product::findOrFail($variant->product_id);

        return view('admin.products.product_variants.edit_productVariant', compact('variant', 'product'));
    }

    public function update(Request $request, $variantId)
    {
        $request->validate([
            'variant_type' => 'required|string|max:255',
            'variant_value' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $variant = ProductVariant::with('images')->findOrFail($variantId);

        // Cập nhật thông tin biến thể
        $variant->variant_type = $request->variant_type;
        $variant->variant_value = $request->variant_value;
        $variant->price = $request->price;
        $variant->stock = $request->stock;
        $variant->save();

        // Nếu có ảnh mới => Xóa ảnh cũ trước, sau đó thêm ảnh mới
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ trong thư mục storage và database
            foreach ($variant->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }

            // Thêm ảnh mới
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
        // Kiểm tra quyền (nếu cần)
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Tìm biến thể theo ID
        $variant = ProductVariant::findOrFail($id);

        // Xóa hình ảnh liên quan
        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Xóa biến thể
        $variant->delete();

        return redirect()->back()->with('success', 'Biến thể sản phẩm đã được xóa thành công.');
    }
}
