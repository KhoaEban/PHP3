@extends('layouts.navbar_admin')

@section('content')
    <div class="card-title mt-3" style="background-color: #B0C4DE">
        <h1 class="h6 p-3">Quản lý danh mục</h1>
    </div>
    <div class="container-fluid mt-4">

        <div class="row mt-4">
            {{-- Cột trái: Form thêm danh mục --}}
            <div class="col-md-4">
                <div class="border p-3">
                    <h5 class="mb-3">Thêm danh mục</h5>
                    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Danh mục cha:</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- Chọn danh mục cha (nếu có) --</option>
                                @foreach ($categories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Thêm danh mục</button>
                    </form>
                </div>
            </div>

            {{-- Cột phải: Danh sách danh mục --}}
            <div class="col-md-8">
                <div class="border p-3">
                    <h5 class="mb-3">Danh sách danh mục</h5>

                    {{-- Thanh tìm kiếm danh mục --}}
                    <form method="GET" action="{{ route('category.index') }}" class="d-flex mb-3">
                        <input class="form-control me-2" name="search" type="search" placeholder="Tìm kiếm danh mục"
                            value="{{ request('search') }}">
                        <button class="btn btn-dark py-2 px-3" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Hiển thị danh sách danh mục dưới dạng list --}}
                    <ul class="list-group">
                        @forelse ($categories as $category)
                            @if (!$category->parent_id)
                                {{-- Danh mục cha --}}
                                <div class="m-1">
                                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                        <div class="d-flex align-items-center">
                                            @if ($category->image)
                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                                    width="40" height="40" class="me-2 rounded">
                                            @endif
                                            {{ $category->name }}
                                        </div>
                                        <div>
                                            @if ($category->children->count() > 0)
                                                {{-- Nút xem danh mục con --}}
                                                <a href="{{ route('category.show', $category->id) }}" class="btn-sm">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            @endif
                                            {{-- Thêm danh mục con --}}
                                            <a href="{{ route('category.create.sub', $category->id) }}" class="btn-sm">
                                                <i class="fas fa-plus"></i> Thêm con
                                            </a>
                                            {{-- Sửa danh mục --}}
                                            <a href="{{ route('category.edit', $category->id) }}" class="btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            {{-- Xóa danh mục --}}
                                            <form action="{{ route('category.destroy', $category->id) }}" method="POST"
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

                                {{-- Danh mục con (nếu có) --}}
                                @if ($category->children->count() > 0)
                                    <ul class="list-group ms-4 m-1">
                                        @foreach ($category->children as $sub)
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="border-radius: 0px">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">--</span>
                                                    @if ($sub->image)
                                                        <img src="{{ asset($sub->image) }}" alt="{{ $sub->name }}"
                                                            width="30" height="30" class="me-2 rounded">
                                                    @endif
                                                    {{ $sub->name }}
                                                </div>
                                                <div>
                                                    {{-- Sửa danh mục con --}}
                                                    <a href="{{ route('category.edit', $sub->id) }}" class="btn-sm">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                    {{-- Xóa danh mục con --}}
                                                    <form action="{{ route('category.remove_parent', $sub->id) }}"
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
                                Không tìm thấy danh mục nào!
                            </li>
                        @endforelse
                    </ul>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $categories->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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

{{-- Thông báo --}}
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
    /* Bỏ màu nền header */
    .table thead {
        background-color: #f8f9fa;
    }

    /* Giảm độ đậm của đường viền bảng */
    .table-bordered td,
    .table-bordered th {
        border: 1px solid #dee2e6 !important;
    }

    /* Bỏ shadow */
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
