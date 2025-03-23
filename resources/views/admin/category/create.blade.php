@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Thêm danh mục mới</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold">Tên danh mục:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Danh mục cha:</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- Chọn danh mục cha (nếu có) --</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Mô tả:</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Hình ảnh:</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Thêm danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
