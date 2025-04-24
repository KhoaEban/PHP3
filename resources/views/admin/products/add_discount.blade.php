@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center fw-bold text-dark">Gán mã giảm giá cho sản phẩm</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <div class="border p-4 rounded shadow-sm bg-white">
                    <h5 class="mb-3 text-center">Chọn mã giảm giá cho <strong>{{ $product->title }}</strong></h5>

                    <form action="{{ route('products.applyDiscount', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold">Chọn mã giảm giá:</label>
                            <select name="discount_id" class="form-control">
                                @foreach ($discounts as $discount)
                                    <option value="{{ $discount->id }}"
                                        {{ $product->discount_id == $discount->id ? 'selected' : '' }}>
                                        {{ $discount->code }}
                                        ({{ $discount->type == 'percentage' ? $discount->amount . '%' : number_format($discount->amount, 0, ',', '.') . ' VNĐ' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save"></i> Lưu mã giảm giá
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
