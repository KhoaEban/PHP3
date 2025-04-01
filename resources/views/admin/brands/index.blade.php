@extends('layouts.navbar_admin')

@section('content')
    <div class="card-title mt-3" style="background-color: #B0C4DE">
        <h1 class="h6 p-3">Quản lý thương hiệu</h1>
    </div>

    <div class="container-fluid mt-4">
        <div class="row mt-4">
            {{-- Cột trái: Form thêm thương hiệu --}}
            <div class="col-md-4">
                <div class="border p-3">
                    <h5 class="mb-3">Thêm thương hiệu</h5>
                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên thương hiệu</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Tác giả cha:</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- Chọn thương hiệu cha (nếu có) --</option>
                                @foreach ($brands as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Thêm thương hiệu</button>
                    </form>
                </div>
            </div>

            {{-- Cột phải: Danh sách thương hiệu --}}
            <div class="col-md-8">
                <div class="border p-3">
                    <h5 class="mb-3">Danh sách thương hiệu</h5>

                    {{-- Thanh tìm kiếm --}}
                    <form method="GET" action="{{ route('brands.index') }}" class="d-flex mb-3">
                        <input class="form-control me-2" name="search" type="search" placeholder="Tìm kiếm thương hiệu"
                            value="{{ request('search') }}">
                        <button class="btn btn-dark py-2 px-3" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Hiển thị danh sách thương hiệu dưới dạng danh mục cha - con --}}
                    <ul class="list-group">
                        @forelse ($brands as $brand)
                            @if (!$brand->parent_id)
                                {{-- Thương hiệu cha --}}
                                <div class="m-1">
                                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                        <div class="d-flex align-items-center">
                                            @if ($brand->thumbnail)
                                                <img src="{{ asset('uploads/brands/' . $brand->thumbnail) }}"
                                                    alt="{{ $brand->name }}" width="40" height="40"
                                                    class="me-2 rounded">
                                            @endif
                                            {{ $brand->name }}
                                        </div>
                                        <div>
                                            {{-- Thêm thương hiệu con --}}
                                            <a href="{{ route('brands.create.subbrand', $brand->id) }}" class="btn-sm">
                                                <i class="fas fa-plus"></i> Thêm con
                                            </a>
                                            {{-- Sửa thương hiệu --}}
                                            <a href="{{ route('brands.edit', $brand->id) }}" class="btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            {{-- Xóa thương hiệu --}}
                                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                </div>

                                {{-- Thương hiệu con (nếu có) --}}
                                @if ($brand->children->count() > 0)
                                    <ul class="list-group ms-4 m-1">
                                        @foreach ($brand->children as $sub)
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="border-radius: 0px">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">--</span>
                                                    @if ($sub->thumbnail)
                                                        <img src="{{ asset('uploads/brands/' . $sub->thumbnail) }}"
                                                            alt="{{ $sub->name }}" width="30" height="30"
                                                            class="me-2 rounded">
                                                    @endif
                                                    {{ $sub->name }}
                                                </div>
                                                <div>
                                                    {{-- Sửa thương hiệu con --}}
                                                    <a href="{{ route('brands.edit', $sub->id) }}" class="btn-sm">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                    {{-- Xóa danh mục con --}}
                                                    <form action="{{ route('brands.removeParent', $sub->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-secondary btn-sm">
                                                            <i class="fas fa-unlink"></i> Bỏ cha
                                                        </button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        @empty
                            <li class="list-group-item text-center text-danger fw-bold">
                                Không tìm thấy thương hiệu nào!
                            </li>
                        @endforelse
                    </ul>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $brands->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Xác nhận xóa --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function() {
                let form = this.closest("form");

                Swal.fire({
                    title: "Bạn có chắc chắn muốn xóa?",
                    text: "Thao tác này không thể hoàn tác!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Xác nhận xóa danh mục
        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function() {
                let form = this.closest("form");

                Swal.fire({
                    title: "Bạn có chắc chắn muốn xóa?",
                    text: "Danh mục con sẽ được chuyển thành danh mục độc lập!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<style>
    .border {
        box-shadow: none !important;
    }

    .btn-sm {
        background: none !important;
        border: none !important;
        text-decoration: underline !important;
        color: #000000 !important;
    }

    .btn-sm:hover {
        color: #0A58CA !important;
    }

    .btn-delete {
        background: none !important;
        border: none !important;
        text-decoration: underline !important;
    }

    .btn-delete:hover {
        color: #0A58CA !important;
    }
</style>
