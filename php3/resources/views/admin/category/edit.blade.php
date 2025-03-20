@extends('layouts.navbar_admin')

@section('content')
    <div class="container">
        <h1 class="text-center text-primary">Sửa danh mục</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="fw-bold">Tên danh mục:</label>
                        <input class="form-control" name="name" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Mô tả:</label>
                        <textarea class="form-control" name="description" rows="3">{{ $category->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold mb-3">Hình ảnh hiện tại:</label>
                        <div>
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                width="100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Hình ảnh mới:</label>
                        <div class="mb-3">
                            <img id="previewImage" class="mt-2 d-none" width="100">
                        </div>
                        <input type="file" name="image" id="image" class="form-control" onchange="previewFile()">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Sửa danh mục</button>
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
