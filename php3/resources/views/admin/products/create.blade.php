@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Thêm sản phẩm mới</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold">Chọn danh mục:</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Tên sản phẩm:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Mô tả:</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Giá:</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Số lượng:</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Trạng thái:</label>
                        <select name="status" class="form-control">
                            <option value="1">✅ Hiển thị</option>
                            <option value="0">❌ Ẩn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Hình ảnh:</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
