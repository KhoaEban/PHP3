@extends('layouts.navbar_admin')

@section('content')
    <div class="card-title mt-3" style="background-color: #B0C4DE">
        <h1 class="h6 p-3">Quản lý thương hiệu</h1>
    </div>

    <div class="container-fluid mt-4">
        <div class="row mt-4">
            {{-- Cột trái: Form thêm mã giảm giá --}}
            <div class="col-md-4">
                <div class="border p-3">
                    <h5 class="mb-3">Thêm mã giảm giá</h5>
                    <form action="{{ route('discounts.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Mã giảm giá</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Loại giảm giá:</label>
                            <select name="type" class="form-control">
                                <option value="percentage">Phần trăm (%)</option>
                                <option value="fixed">Số tiền cố định</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Giá trị giảm giá</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Ngày hết hạn</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Thêm mã giảm giá</button>
                    </form>
                </div>
            </div>

            {{-- Cột phải: Danh sách mã giảm giá --}}
            <div class="col-md-8">
                <div class="border p-3">
                    <h5 class="mb-3">Danh sách mã giảm giá</h5>

                    {{-- Thanh tìm kiếm --}}
                    <form method="GET" action="{{ route('discounts.index') }}" class="d-flex mb-3">
                        <input class="form-control me-2" name="search" type="search" placeholder="Tìm kiếm mã giảm giá"
                            value="{{ request('search') }}">
                        <button class="btn btn-dark py-2 px-3" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Hiển thị danh sách mã giảm giá --}}
                    <table class="table table-bordered">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>#</th>
                                <th>Mã giảm giá</th>
                                <th>Loại</th>
                                <th>Giá trị</th>
                                <th>Hết hạn</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discounts as $discount)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $discount->code }}</td>
                                    <td>{{ $discount->type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</td>
                                    <td>{{ $discount->amount }}</td>
                                    <td>{{ $discount->expires_at ? $discount->expires_at->format('d/m/Y') : 'Không có' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('discounts.edit', $discount->id) }}"
                                            class="btn-sm text-decoration-none">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- Xóa mã giảm giá --}}
                                        <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-sm btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $discounts->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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
                        form.submit(); // ✨ Submit form thủ công!
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
