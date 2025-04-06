@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Thêm Biến Thể</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('product_variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="variant_type">Loại Biến Thể</label>
                        <input type="text" name="variant_type" class="form-control" id="variant_type" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="variant_value">Giá trị Biến Thể</label>
                        <input type="text" name="variant_value" class="form-control" id="variant_value" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Giá</label>
                        <input type="number" name="price" class="form-control" id="price" step="0.01">
                    </div>

                    <div class="form-group mb-3">
                        <label for="stock">Số Lượng</label>
                        <input type="number" name="stock" class="form-control" id="stock" min="0" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="images">Hình ảnh biến thể</label>
                        <input type="file" name="images[]" multiple class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm Biến Thể</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Thêm vào phần <head> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
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
@endsection
