<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
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

        $query = Product::with('category'); // Lấy thông tin danh mục kèm theo sản phẩm

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
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : null;

        Product::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'slug' => \Illuminate\Support\Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'image' => $imagePath
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->firstOrFail(); // Tìm sản phẩm theo slug
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $product->image);
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image;
        }

        $product->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'image' => $imagePath
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    public function destroy(Product $product)
    {
        Storage::delete('public/' . $product->image);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa!');
    }
}
