@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Danh sách danh mục</h1>

        <div class="d-flex justify-content-between my-3">
            <a href="{{ route('category.create') }}" class="btn border rounded-pill"><i class="fas fa-plus"></i> Thêm danh
                mục</a>

            {{-- Thanh tìm kiếm danh mục --}}
            <form method="GET" action="{{ route('category.index') }}"
                class="search-form d-flex align-items-center m-0 border rounded-pill px-3">
                <input class="p-2 border-0" style="outline: none;" name="search" type="search"
                    placeholder="Tìm kiếm danh mục" aria-label="Search" value="{{ request('search') }}">
                <button class="search-icon m-0 p-2 border-0 bg-transparent" style="outline: none;" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Hình ảnh</th>
                        <th>Tên danh mục</th>
                        <th>Danh mục cha</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        width="50">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $category->name }}</td>
                            <td>
                                {{ $category->parent ? $category->parent->name : '---' }}
                            </td>                            
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-edit"></i>
                                    Sửa</a>
                                <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                    class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"><i
                                            class="fas fa-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-danger fw-bold">Không tìm thấy danh mục nào!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center">
            {{ $categories->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function() {
                let form = this.closest("form");

                Swal.fire({
                    title: "Bạn có chắc chắn?",
                    text: "Hành động này không thể hoàn tác!",
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
