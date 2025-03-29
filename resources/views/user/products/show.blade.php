@extends('layouts.navbar_user')

@section('content')
    <div class="container mt-5">
        <!-- Nút quay lại -->
        <div class="mb-3"
            style="background-color: #4E4E4E; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('user.products') }}" class="text-decoration-none text-white p-2">← Quay lại trang sản phẩm</a>
        </div>

        <div class="product-details">
            <!-- Hình ảnh sản phẩm -->
            <div class="product-image">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="product-info">
                <h2>{{ $product->title }}</h2>
                <p class="price">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>

                <!-- Hiển thị danh mục sản phẩm -->
                <p><strong>Danh mục:</strong> {{ $categoryName }}</p>

                <p>{{ $product->description }}</p>

                <!-- Hiển thị cảnh báo nếu số lượng thấp hơn 10 -->
                @if ($product->stock < 10)
                    <p class="text-warning fw-bold">⚠ Chỉ còn {{ $product->stock }} sản phẩm trong kho!</p>
                @endif

                <div class="d-flex align-items-end">
                    <!-- Thêm vào giỏ hàng -->
                    <form action="#" method="POST" id="addToCartForm">
                        @csrf
                        <div class="quantity-input mb-3 mt-5">
                            <label for="quantity" class="fw-bold">Số lượng:</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                max="{{ $product->stock }}" class="form-control">
                        </div>

                        <button type="submit" class="bg-dark text-white py-2 px-3 mt-5 border-1">Thêm vào giỏ hàng</button>
                        <p id="quantityError" class="text-danger mt-2" style="display: none;">Số lượng không hợp lệ!</p>
                    </form>
                    <!-- Mua ngay -->
                    <a href="#" style="border: 1px solid #4E4E4E" class="text-dark py-2 px-3 mt-5 mb-3 mx-3">Mua ngay</a>
                </div>
                <!-- Hiển thị số lượng sản phẩm có sẵn -->
                <p><strong>Số lượng có sẵn:</strong> {{ $product->stock }} sản phẩm</p>
            </div>
        </div>

        <!-- Sản phẩm liên quan -->
        @if ($relatedProducts->isNotEmpty())
            <div class="related-products">
                <h4>Sản phẩm liên quan</h4>
                <div class="row">
                    @foreach ($relatedProducts as $related)
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <a href="{{ route('user.products.show', $related->slug) }}"
                                    class="text-decoration-none text-dark">
                                    <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top"
                                        alt="{{ $related->title }}">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $related->title }}</h6>
                                        <p class="text-danger fw-bold">{{ number_format($related->price, 0, ',', '.') }}
                                            VNĐ</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Bình luận -->
        <div class="comments">
            <h4>Bình luận</h4>
            <p><strong>Nguyễn Văn A:</strong> Sản phẩm rất tốt, mình đã mua và rất hài lòng!</p>
            <p><strong>Trần Thị B:</strong> Giao hàng nhanh, chất lượng sản phẩm đúng như mô tả.</p>
            <p><strong>Lê Văn C:</strong> Giá cả hợp lý, chất lượng ổn.</p>
        </div>
    </div>
@endsection

<style>
    .product-details {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }

    .product-image {
        flex: 1;
        max-width: 500px;
    }

    .product-image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .product-info {
        flex: 1;
    }

    .product-info h2 {
        font-size: 28px;
        font-weight: bold;
    }

    .product-info .price {
        font-size: 24px;
        font-weight: bold;
        color: #e60000;
    }

    .quantity-input {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-input input {
        width: 60px;
        text-align: center;
        font-size: 18px;
    }

    .btn-dark {
        background-color: #333;
        color: #fff;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    .btn-dark:hover {
        background-color: #555;
    }

    .related-products {
        margin-top: 50px;
    }

    .related-products h4 {
        font-size: 22px;
        margin-bottom: 20px;
    }

    .related-products .card {
        transition: 0.3s;
        border-radius: 8px;
    }

    .related-products .card img {
        border-radius: 8px 8px 0 0;
    }

    .related-products .card:hover {
        transform: translateY(-5px);
    }

    .comments {
        margin-top: 40px;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
    }

    .comments p {
        margin-bottom: 8px;
    }

    .comments strong {
        color: #333;
    }
</style>
