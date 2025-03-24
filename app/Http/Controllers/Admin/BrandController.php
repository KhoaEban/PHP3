<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        // $brands = Brand::with('children')->whereNull('parent_id')->get();
        $brands = Brand::with('parent')->get();
        return view('admin.brands.index', compact('brands'));
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


    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
