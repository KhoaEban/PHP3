<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        $search = $request->input('search');

        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10); // Thêm paginate() để hỗ trợ phân trang

        $category = Category::all();
        return view('admin.category.index', compact('categories', 'request'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate ảnh
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public'); // Lưu vào storage/app/public/categories
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('category.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }


    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate ảnh
        ]);

        // Nếu có ảnh mới, lưu ảnh mới và xóa ảnh cũ
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image) {
                Storage::delete('public/' . $category->image);
            }

            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('categories', 'public');
        } else {
            $imagePath = $category->image; // Giữ nguyên ảnh cũ nếu không có ảnh mới
        }

        // Cập nhật danh mục
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('category.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa.');
    }
}
