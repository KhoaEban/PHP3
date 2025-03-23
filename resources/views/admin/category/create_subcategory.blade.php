@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <h1 class="text-center fw-bold">Thêm danh mục con</h1>
        <p class="text-center text-muted">Danh mục cha: <strong>{{ $parent->name }}</strong></p>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <div class="border p-4">
                    <h5 class="mb-3 text-center">Chọn danh mục con</h5>

                    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $parent->id }}">

                        {{-- Chọn danh mục có sẵn --}}
                        <div class="mb-3">
                            <label class="fw-bold">Danh mục có sẵn:</label>
                            <select name="existing_category" class="form-control">
                                <option value="">-- Chọn danh mục có sẵn --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-4">

                        {{-- Hoặc tạo danh mục mới --}}
                        <h5 class="text-center">Hoặc nhập danh mục mới</h5>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên danh mục</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('category.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-plus"></i> Thêm danh mục con
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Hiển thị thông báo --}}
@section('toast')
    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
            }).showToast();
        </script>
    @endif

    @if (session('error'))
        <script>
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #ff6b6b, #556270)",
                },
            }).showToast();
        </script>
    @endif
@endsection

<style>
    .border {
        box-shadow: none !important;
    }

    .btn-dark {
        width: 48%;
    }

    .btn-secondary {
        width: 48%;
    }
</style>
