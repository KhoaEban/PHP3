@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <div class="header">
            <h1>Biến thể của sản phẩm: <strong>{{ $product->title }}</strong></h1>
            <div class="buttons mx-2">
                <a href="{{ route('product_variants.create', $product->id) }}" class="text-white text-decoration-none d-flex align-items-center">
                    <button>
                        <i class="fas fa-plus me-1"></i>
                        Thêm biến thể mới
                    </button>
                </a>
            </div>
        </div>

        @if ($product->variants->isEmpty())
            <div class="alert alert-warning">
                Sản phẩm chưa có biến thể nào.
            </div>
        @else
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Loại bìa</th>
                        <th>Loại giấy</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>SKU</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->variants as $index => $variant)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $variant->variant_type }}</td>
                            <td>{{ $variant->variant_value }}</td>
                            <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $variant->stock }} cái</td>
                            <td>{{ $variant->sku ?? '---' }}</td>
                            <td>
                                <a href="{{ route('product_variants.edit', $variant->id) }}"
                                    class="btn btn-sm btn-edit">
                                    <i class="fas fa-edit" title="Sửa"></i>
                                </a>
                                <form action="{{ route('product_variants.destroy', $variant->id) }}" method="POST"
                                    class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-delete">
                                        <i class="fas fa-trash" title="Xóa"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('products.index') }}" class="btn mt-3">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
        </a>
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
