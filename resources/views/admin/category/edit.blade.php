@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <h1 class="text-center fw-bold text-dark">Chỉnh sửa danh mục</h1>

        <div class="row mt-4">
            {{-- Cột giữa: Form chỉnh sửa danh mục --}}
            <div class="col-md-6 offset-md-3">
                <div class="border p-4 rounded shadow-sm bg-white">
                    <h5 class="mb-3 text-center">Cập nhật thông tin danh mục</h5>

                    <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Tên danh mục --}}
                        <div class="mb-3">
                            <label class="fw-bold">Tên danh mục:</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}"
                                required>
                        </div>

                        {{-- Danh mục cha --}}
                        <div class="mb-3">
                            <label class="fw-bold">Danh mục cha:</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- Chọn danh mục cha (nếu có) --</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Mô tả danh mục --}}
                        <div class="mb-3">
                            <label class="fw-bold">Mô tả:</label>
                            <textarea class="form-control" name="description" rows="3">{{ $category->description }}</textarea>
                        </div>

                        {{-- Hình ảnh danh mục --}}
                        <div class="mb-3">
                            <label class="fw-bold">Hình ảnh danh mục:</label>
                            <div class="">
                                {{-- Ảnh cũ --}}
                                <div class="me-3 mb-3">
                                    <p class="mb-1 text-muted">Hình ảnh hiện tại:</p>
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="80"
                                        class="rounded shadow-sm border">
                                </div>
                                {{-- Ảnh mới --}}
                                <div>
                                    <p class="mb-1 text-muted d-none" id="previewLabel">Hình ảnh mới:</p>
                                    <img id="previewImage" class="d-none rounded shadow-sm border" width="80">
                                </div>
                            </div>
                        </div>

                        {{-- Chọn ảnh mới --}}
                        <div class="mb-3">
                            <input type="file" name="image" id="image" class="form-control"
                                onchange="previewFile()">
                        </div>

                        {{-- Nút hành động --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('category.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script xem trước hình ảnh --}}
    <script>
        function previewFile() {
            var preview = document.getElementById('previewImage');
            var imageLabel = document.getElementById('previewLabel');
            var file = document.getElementById('image').files[0];
            var reader = new FileReader();

            reader.onload = function(event) {
                preview.src = event.target.result; // Gán ảnh mới vào thẻ <img>
                preview.classList.remove('d-none'); // Hiển thị ảnh mới
                imageLabel.classList.remove('d-none'); // Hiển thị label "Hình ảnh mới"
            };

            if (file) {
                reader.readAsDataURL(file); // Đọc file ảnh mới
            } else {
                preview.src = "";
                preview.classList.add('d-none'); // Ẩn ảnh nếu không chọn file
                imageLabel.classList.add('d-none'); // Ẩn label nếu không có ảnh mới
            }
        }
    </script>
@endsection
