@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-warning">Sửa sản phẩm</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="fw-bold">Chọn danh mục:</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tên sản phẩm:</label>
                        <input type="text" name="title" class="form-control" value="{{ $product->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Mô tả:</label>
                        <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Giá:</label>
                        <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Số lượng:</label>
                        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Trạng thái:</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>✅ Hiển thị</option>
                            <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>❌ Ẩn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Hình ảnh hiện tại:</label>
                        <div>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                width="100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Hình ảnh mới:</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-warning">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function previewFile() {
        var preview = document.getElementById('previewImage');
        var imageLabel = document.getElementById('imageLabel');
        var file = document.getElementById('image').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.classList.remove('d-none'); // Hiển thị ảnh mới
            imageLabel.classList.remove('d-none');
        }

        if (file) {
            reader.readAsDataURL(file); // Đọc file ảnh mới
        } else {
            preview.src = "";
            preview.classList.add('d-none'); // Ẩn nếu không chọn ảnh
            imageLabel.classList.add('d-none');
        }
    }
</script>
