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
        // Kiểm tra quyền truy cập
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('404')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Lấy thông tin tìm kiếm
        $search = $request->input('search');

        // Truy vấn danh mục (tìm cả danh mục cha & con)
        $categories = Category::with('parent', 'children')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhereHas('parent', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('children', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('admin.category.index', compact('categories', 'request'));
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.show', compact('category'));
    }


    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.category.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'existing_category' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:2048',
        ]);

        if ($request->existing_category) {
            // Nếu chọn danh mục có sẵn, cập nhật parent_id của danh mục này
            $category = Category::find($request->existing_category);
            $category->parent_id = $request->parent_id;
            $category->save();
        } else {
            // Nếu không chọn danh mục có sẵn, tạo danh mục mới
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id ?? null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = 'storage/' . $imagePath; // Lưu đường dẫn chuẩn
            }

            $category->save();
        }

        return redirect()->route('category.index')->with('success', 'Danh mục đã được thêm thành công!');
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

    public function createSubcategory($parent_id)
    {
        $parent = Category::findOrFail($parent_id);
        $categories = Category::whereNull('parent_id')->get(); // Lấy danh mục cha

        return view('admin.category.create_subcategory', compact('parent', 'categories'));
    }

    public function removeParent($id)
    {
        $category = Category::findOrFail($id);
        $category->parent_id = null; // Bỏ danh mục cha
        $category->save();

        return redirect()->route('category.index')->with('success', 'Đã bỏ danh mục cha thành công!');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có danh mục con không
        if ($category->children()->exists()) {
            // Cập nhật tất cả danh mục con thành danh mục cấp cao nhất (parent_id = null)
            $category->children()->update(['parent_id' => null]);
        }

        // Xóa danh mục
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa! Các danh mục con đã trở thành danh mục độc lập.');
    }
}
