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
        // Kiem tra xem nguoi dung da dang nhap hay chua
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Lấy thống tin tìm kiếm
        $search = $request->input('search');

        // Thêm paginate() để hỗ trợ phân trang
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10); // Thêm paginate() để hỗ trợ phân trang

        // Phân danh mục cha danh mục con
        $categories = Category::with('parent')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->paginate(10);

        // Lay danh sach danh muc
        $category = Category::all();

        return view('admin.category.index', compact('categories', 'request'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.category.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('category.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        // Lấy danh sách danh mục cha, loại trừ danh mục hiện tại để tránh lặp vô hạn
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();

        return view('admin.category.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id, // Không cho danh mục làm cha chính nó
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
            'parent_id' => $request->parent_id, // Cập nhật danh mục cha
        ]);

        return redirect()->route('category.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa.');
    }
}
