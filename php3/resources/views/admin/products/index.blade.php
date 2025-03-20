@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Danh sách sản phẩm</h1>

        <div class="d-flex justify-content-between my-3">
            <a href="{{ route('products.create') }}" class="btn border rounded-pill"><i class="fas fa-plus"></i> Thêm sản
                phẩm</a>

            {{-- Thanh tìm kiếm sản phẩm --}}
            <form method="GET" action="{{ route('products.index') }}"
                class="search-form d-flex align-items-center m-0 border rounded-pill px-3">
                <input class="p-2 border-0" style="outline: none;" name="search" type="search"
                    placeholder="Tìm kiếm sản phẩm" aria-label="Search" value="{{ request('search') }}">
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
                        <th>Danh mục</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $key => $product)
                        <tr>
                            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                    width="100">
                            </td>
                            <td>{{ $product->category->name ?? 'Không có danh mục' }}</td> <!-- Hiển thị danh mục -->
                            <td class="fw-bold">{{ $product->title }}</td>
                            <td>{{ $product->description }}</td>
                            <td class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->status ? '✅ Hiển thị' : '❌ Ẩn' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Sửa
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
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
                            <td colspan="8" class="text-center text-danger fw-bold">Không tìm thấy sản phẩm nào!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center">
            {{ $products->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
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
