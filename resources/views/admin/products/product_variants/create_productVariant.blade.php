@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center text-primary">Thêm Biến Thể</h1>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('product_variants.store', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="variant_type">Loại Biến Thể</label>
                        <input type="text" name="variant_type" class="form-control" id="variant_type"
                            value="{{ old('variant_type') }}" >
                    </div>

                    <div class="form-group mb-3">
                        <label for="variant_value">Giá trị Biến Thể</label>
                        <input type="text" name="variant_value" class="form-control" id="variant_value"
                            value="{{ old('variant_value') }}" >
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Giá</label>
                        <input type="number" name="price" class="form-control" id="price" step="0.01"
                            value="{{ old('price') }}">
                        <small class="text-muted">Giá tối đa: 10,000,000 VNĐ</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="stock">Số Lượng</label>
                        <input type="number" name="stock" class="form-control" id="stock" min="0"
                            value="{{ old('stock') }}" >
                    </div>

                    <div class="form-group mb-3">
                        <label for="images">Hình ảnh biến thể</label>
                        <input type="file" name="images[]" multiple class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm Biến Thể</button>
                    <a href="{{ route('product_variants.index', $product->id) }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Kiểm tra giá sản phẩm
            $('input[name="price"]').on('input', function() {
                var price = parseFloat($(this).val());
                var maxPrice = 10000000;
                var errorContainer = $('.alert-danger');
                var priceError = 'Giá không được vượt quá 10,000,000 VNĐ.';

                if (price > maxPrice) {
                    if (!errorContainer.length) {
                        $('<div class="alert alert-danger"><ul class="mb-0"><li>' + priceError +
                                '</li></ul></div>')
                            .insertAfter('h1');
                    } else {
                        if (!errorContainer.find('li:contains("' + priceError + '")').length) {
                            errorContainer.find('ul').append('<li>' + priceError + '</li>');
                        }
                    }
                    $(this).addClass('is-invalid');
                } else {
                    errorContainer.find('li:contains("' + priceError + '")').remove();
                    if (!errorContainer.find('li').length) {
                        errorContainer.remove();
                    }
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
