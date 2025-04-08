@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <div class="header">
            <h1>Sản phẩm</h1>
            <div class="buttons mx-2">
                <a href="{{ route('products.create') }}" class="text-white text-decoration-none d-flex align-items-center">
                    <button>
                        <i class="fas fa-plus me-1"></i>
                        Thêm sản phẩm
                    </button>
                </a>
            </div>
        </div>
        <div class="filters">
            <div class="">
                <select>
                    <option>
                        Thao tác
                    </option>
                </select>
                <button>
                    Áp dụng
                </button>
                <select>
                    <option>
                        Chọn một danh mục
                    </option>
                </select>
                <select>
                    <option>
                        Lọc theo thương hiệu
                    </option>
                </select>
                <button>
                    Lọc
                </button>
            </div>
            <div class="">
                {{-- Thanh tìm kiếm sản phẩm --}}
                <form method="GET" action="{{ route('products.index') }}" class="search-form d-flex border px-3"
                    style="width: 500px;">
                    <input class="p-2 border-0 w-100" style="outline: none;" name="search" type="search"
                        placeholder="Tìm kiếm sản phẩm" aria-label="Search" value="{{ request('search') }}">
                    <button class="btn bg-transparent text-muted border-0" style="outline: none;" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="responsive-table">
            <table class="text-center">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" />
                        </th>
                        <th>
                            Tên
                            <i class="fas fa-sort">
                            </i>
                        </th>
                        <th>
                            Số lượng
                        </th>
                        <th>
                            Giá
                            <i class="fas fa-sort">
                            </i>
                        </th>
                        <th>
                            Danh mục
                        </th>
                        <th>
                            Thương hiệu / Tác giả
                        </th>
                        <th>
                            Trạng thái
                        </th>
                        <th>
                            Ngày tạo
                            <i class="fas fa-sort">
                            </i>
                        </th>
                        <th>
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $key => $product)
                        <tr>
                            <td>
                                <input type="checkbox" />
                            </td>
                            <td class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                    width="50">
                                <p class="text-dark mx-2">
                                    {{ $product->title }}
                                </p>
                            </td>
                            <td class="status-in-stock">
                                @if ($product->variants->isNotEmpty())
                                    Còn hàng ({{ $product->variants->sum('stock') }})
                                @else
                                    Còn hàng ({{ $product->stock }})
                                @endif
                            </td>

                            <td>
                                @if ($product->discount_id)
                                    @if ($product->variants->isNotEmpty())
                                        {{ number_format($product->variants->min->getDiscountedPrice(), 0, ',', '.') }} VNĐ
                                        - {{ number_format($product->variants->min('price'), 0, ',', '.') }} VNĐ
                                    @else
                                        {{ number_format($product->getDiscountedPrice(), 0, ',', '.') }} VNĐ -
                                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                    @endif
                                @else
                                    @if ($product->variants->isNotEmpty())
                                        {{ number_format($product->variants->min('price'), 0, ',', '.') }} VNĐ
                                    @else
                                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                    @endif
                                @endif
                            </td>

                            <td>
                                @if ($product->categories->isNotEmpty())
                                    <span
                                        class="badge text-muted text-wrap m-0 p-0">{{ $product->categories->pluck('name')->implode(', ') }}</span>
                                @else
                                    <span class="badge text-muted text-wrap m-0 p-0">Không có danh mục</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->brands->isNotEmpty())
                                    <span
                                        class="badge text-muted text-wrap m-0 p-0">{{ $product->brands->pluck('name')->implode(', ') }}</span>
                                @else
                                    <span class="badge text-muted text-wrap m-0 p-0">Không có thương hiệu</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge text-muted text-wrap m-0 p-0 mb-1">Đã xuất bản</span>
                                <br />
                                <span
                                    class="badge text-muted text-wrap m-0 p-0">{{ $product->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="action">
                                <a href="{{ route('products.addDiscount', $product->id) }}" class="text-decoration-none">
                                    <button type="button" class="btn btn-sm">
                                        <i class="fa-solid fa-ticket-simple" title="Thêm mã giảm giá"></i>
                                    </button>
                                </a>
                                <!-- Kiểm tra nếu sản phẩm chưa có biến thể -->
                                @if ($product->variants)
                                    <a href="{{ route('product_variants.create', $product->id) }}"
                                        class="text-decoration-none">
                                        <button type="button" class="btn btn-sm">
                                            <i class="fas fa-plus" title="Thêm biến thể"></i>
                                        </button>
                                    </a>
                                @endif
                                @if ($product->variants->isNotEmpty())
                                    <a href="{{ route('product_variants.index', $product->id) }}"
                                        class="text-decoration-none">
                                        <button type="button" class="btn btn-sm">
                                            <i class="fas fa-eye" title="Xem biến thể"></i>
                                        </button>
                                    </a>
                                @endif


                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="text-decoration-none text-dark">
                                    <button type="button" class="btn btn-sm">
                                        <i class="fas fa-edit" title="Sửa"></i>
                                    </button>
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-delete">
                                        <i class="fas fa-trash" title="Xóa"></i>
                                    </button>
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
        <br>
        {{-- Phân trang --}}
        <div class="d-flex justify-content-center gap-3">
            {{ $products->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
<style>
    input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .search-form {
        border-radius: 5px;
        background: white;
    }

    .search-form input {
        border: none;
    }

    .search-form button {
        cursor: pointer;
    }
</style>


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
