@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h4>Chỉnh sửa biến thể cho sản phẩm: <strong>{{ $product->title }}</strong></h4>
        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('product_variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Loại biến thể</label>
                <input type="text" name="variant_type" class="form-control"
                    value="{{ old('variant_type', $variant->variant_type) }}" required>
            </div>

            <div class="mb-3">
                <label>Giá trị biến thể</label>
                <input type="text" name="variant_value" class="form-control"
                    value="{{ old('variant_value', $variant->variant_value) }}" required>
            </div>

            <div class="mb-3">
                <label>Giá</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $variant->price) }}">
            </div>

            <div class="mb-3">
                <label>Số lượng</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $variant->stock) }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Thêm hình ảnh mới (nếu chọn ảnh mới thì ảnh cũ sẽ bị xóa)</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <div class="mb-3">
                <label>Hình ảnh hiện tại:</label><br>
                @foreach ($variant->images as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" width="80" class="me-2 mb-2" />
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('product_variants.index', $product->id) }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
