<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;


class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Lấy danh sách thương hiệu cha (parent_id = null)
        $brands = Brand::with('children')->whereNull('parent_id')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })->orderBy('created_at', 'desc')->paginate(10);

        $parentBrands = Brand::whereNull('parent_id')->get(); // Lấy danh sách brand cha để hiển thị trong form thêm

        return view('admin.brands.index', compact('brands', 'parentBrands'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:brands,slug',
            'parent_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        // Xử lý slug tự động nếu không nhập
        if (!$request->filled('slug')) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Xử lý ảnh
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '-' . Str::slug($data['name']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/brands'), $imageName);
            $data['thumbnail'] = $imageName;
        }

        Brand::create($data);
        return redirect()->route('brands.index')->with('success', 'Brand added successfully.');
    }


    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $brands = Brand::where('id', '!=', $id)->get();
        return view('admin.brands.edit', compact('brand', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug,' . $id,
            'parent_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($brand->thumbnail && file_exists(public_path('uploads/brands/' . $brand->thumbnail))) {
                unlink(public_path('uploads/brands/' . $brand->thumbnail));
            }

            // Lưu ảnh mới
            $image = $request->file('thumbnail');
            $imageName = time() . '-' . Str::slug($data['name']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/brands'), $imageName);
            $data['thumbnail'] = $imageName;
        }

        $brand->update($data);
        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function createSubbrand($parent_id)
    {
        $parent = Brand::findOrFail($parent_id);
        $brands = Brand::whereNull('parent_id')->get(); // Lấy danh mục cha

        return view('admin.brands.create_subbrand', compact('parent', 'brands'));
    }

    public function removeParent($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->parent_id = null; // Bỏ danh mục cha
        $brand->save();

        return redirect()->route('brands.index')->with('success', 'Đã bỏ danh mục cha thành công!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Kiểm tra xem thương hiệu có thương hiệu con không
        if ($brand->children()->exists()) {
            // Cập nhật tất cả thương hiệu con thành thương hiệu gốc (parent_id = null)
            $brand->children()->update(['parent_id' => null]);
        }

        // Xóa thương hiệu
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được xóa! Các thương hiệu con đã trở thành thương hiệu độc lập.');
    }
}
