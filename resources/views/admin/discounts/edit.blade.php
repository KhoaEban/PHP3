@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <h1 class="text-center fw-bold text-dark">Chỉnh sửa mã giảm giá</h1>

        <div class="row mt-4">
            {{-- Cột giữa: Form chỉnh sửa mã giảm giá --}}
            <div class="col-md-6 offset-md-3">
                <div class="border p-4 rounded shadow-sm bg-white">
                    <h5 class="mb-3 text-center">Cập nhật thông tin mã giảm giá</h5>

                    <form action="{{ route('discounts.update', $discount->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Mã giảm giá --}}
                        <div class="mb-3">
                            <label class="fw-bold">Mã giảm giá:</label>
                            <input type="text" name="code" class="form-control" value="{{ $discount->code }}"
                                required>
                        </div>

                        {{-- Loại giảm giá --}}
                        <div class="mb-3">
                            <label class="fw-bold">Loại giảm giá:</label>
                            <select name="type" class="form-control">
                                <option value="percentage" {{ $discount->type == 'percentage' ? 'selected' : '' }}>Phần trăm
                                    (%)</option>
                                <option value="fixed" {{ $discount->type == 'fixed' ? 'selected' : '' }}>Số tiền cố định
                                </option>
                            </select>
                        </div>

                        {{-- Giá trị giảm giá --}}
                        <div class="mb-3">
                            <label class="fw-bold">Giá trị giảm giá:</label>
                            <input type="number" name="amount" class="form-control" value="{{ $discount->amount }}"
                                required>
                        </div>

                        {{-- Ngày hết hạn --}}
                        <div class="mb-3">
                            <label class="fw-bold">Ngày hết hạn:</label>
                            <input type="date" name="expires_at" class="form-control"
                                value="{{ $discount->expires_at }}">
                        </div>

                        {{-- Nút hành động --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('discounts.index') }}" class="btn btn-secondary">
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
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

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

    $(document).ready(function() {
        $('.select2-category').select2({
            placeholder: "Chọn danh mục...",
            allowClear: true,
            width: '100%',
            theme: 'default'
        });

        $('.select2-brand').select2({
            placeholder: "Chọn thương hiệu...",
            allowClear: false,
            width: '100%',
            theme: 'default'
        });
    });
</script>
