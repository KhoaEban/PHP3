<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        $query = Product::with(['categories', 'brands']);

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

        return view('admin.products.index', compact('products'));
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
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        $product->categories()->sync($request->category_ids);
        $product->brands()->sync($request->brand_ids);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create(['product_id' => $product->id, 'image' => $path]);
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
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        $product->categories()->sync($request->category_ids);
        $product->brands()->sync($request->brand_ids);

        if ($request->hasFile('images')) {
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create(['product_id' => $product->id, 'image' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật.');
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
