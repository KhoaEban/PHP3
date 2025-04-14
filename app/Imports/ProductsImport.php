<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $products = [];

    public function rules(): array
    {
        return [
            'title' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',      
            'image' => 'required',
            'slug' => 'required',
            'variant_price' => 'nullable|numeric|min:0',
            'variant_stock' => 'nullable|numeric|min:0',
        ];
    }

    public function model(array $row)
    {
        // Làm sạch và xử lý ký tự tiếng Việt
        $productTitle = trim($row['title']);
        $productTitle = mb_convert_encoding($productTitle, 'UTF-8', 'UTF-8');
        $productTitle = preg_replace('/[\x00-\x1F\x7F]/u', '', $productTitle);

        if (empty($productTitle)) {
            throw new \Exception('Tên sản phẩm không hợp lệ sau khi làm sạch.');
        }

        // Làm sạch các trường khác
        $description = !empty($row['description']) ? trim($row['description']) : null;
        $description = mb_convert_encoding($description, 'UTF-8', 'UTF-8');
        $description = preg_replace('/[\x00-\x1F\x7F]/u', '', $description);

        $status = trim($row['status']);
        $image = trim($row['image']);
        $slug = trim($row['slug']);

        // Kiểm tra trùng lặp sản phẩm (dựa trên title)
        if (!request()->has('overwrite')) {
            $existingProduct = Product::where('title', $productTitle)->first();
            if ($existingProduct) {
                throw new \Exception('Sản phẩm đã tồn tại: ' . $productTitle . '. Vui lòng chọn "Overwrite" để ghi đè.');
            }
        }

        // Tìm hoặc tạo/cập nhật sản phẩm
        $product = Product::where('title', $productTitle)->first();
        if ($product && request()->has('overwrite')) {
            // Cập nhật sản phẩm nếu đã tồn tại và có tùy chọn overwrite
            $product->update([
                'title' => $productTitle,
                'description' => $description,
                'price' => $row['price'],
                'stock' => $row['stock'],
                'status' => $status,
                'image' => $image,
                'slug' => $slug,
            ]);
            $this->products[$productTitle] = $product->id;
        } else {
            // Tạo mới sản phẩm
            $product = Product::create([
                'title' => $productTitle,
                'description' => $description,
                'price' => $row['price'],
                'stock' => $row['stock'],
                'status' => $status,
                'image' => $image,
                'slug' => $slug,
                'discount_id' => null, // Để null vì không có trong file Excel
            ]);
            $this->products[$productTitle] = $product->id;
        }

        // Nếu có biến thể, thêm vào bảng product_variants
        if (!empty($row['variant_type'])) {
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_type' => $row['variant_type'],
                'variant_value' => $row['variant_value'] ?? null,
                'price' => $row['variant_price'],
                'stock' => $row['variant_stock'],
            ]);
        }

        return null;
    }
}
