@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Thêm sản phẩm mới</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="fw-bold">Chọn danh mục:</label>
                                <select name="category_ids[]" class="form-control select2-category" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if (isset($product) && $product->categories->contains($category->id)) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold">Chọn thương hiệu:</label>
                                <select name="brand_ids[]" class="form-control select2-brand" multiple>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            @if (isset($product) && $product->brands->contains($brand->id)) selected @endif>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tên sản phẩm:</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Mô tả:</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label for="price">Giá sản phẩm</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                            </div>
                            <div class="col-6">
                                <label for="stock">Số lượng sản phẩm</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Trạng thái:</label>
                        <select name="status" class="form-control">
                            <option value="1">✅ Hiển thị</option>
                            <option value="0">❌ Ẩn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="fw-bold">Hình ảnh chính:</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="col-6">

                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Thêm vào phần <head> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2-category').select2({
                placeholder: "Chọn danh mục...",
                allowClear: true,
                width: '100%',
                theme: 'default'
            });

            $('.select2-brand').select2({
                placeholder: "Chọn thương hiệu...",
                allowClear: false,
                width: '100%',
                theme: 'default'
            });
        });
    </script>
@endsection
