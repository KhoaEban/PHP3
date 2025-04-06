@extends('layouts.navbar_user')

@section('content')
    <div class="container mt-5">
        <div class="back-button" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('user.products') }}" class="text-decoration-none p-2">← Quay lại trang sản phẩm</a>
        </div>

        <div class="product-details">
            <!-- Hình ảnh sản phẩm -->
            <div class="product-image">
                <div class="row">
                    <div class="col-12">
                        <img id="mainProductImage" src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->title }}" class="w-100">
                    </div>
                    <div class="col-12">
                        <!-- Hình ảnh phụ -->
                        <div class="product-thumbnails mt-3 mx-0">
                            <div class="container py-0">
                                <div class="row">
                                    @foreach ($ProductVariants as $variant)
                                        @foreach ($variantImages[$variant->id] as $image)
                                            <div class="col-3">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                    alt="Image for {{ $variant->variant_value }}"
                                                    onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')">
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-info">
                <p>Sản phẩm / {{ $categoryName }} / {{ $product->title }}</p>
                <h2>{{ $product->title }}</h2>

                @if ($product->variants->isNotEmpty())
                    @php
                        $minPrice = $product->variants->min('price');
                        $maxPrice = $product->variants->max('price');
                    @endphp

                    <div class="d-flex align-items-center gap-1">
                        <p class="text-danger m-0 price">
                            {{ number_format($minPrice, 0, ',', '.') }} VNĐ
                        </p>
                        @if ($minPrice !== $maxPrice)
                            <span>-</span>
                            <p class="text-danger m-0 price">
                                {{ number_format($maxPrice, 0, ',', '.') }} VNĐ
                            </p>
                        @endif
                    </div>

                    {{-- Loại bìa --}}
                    <div class="mb-3 mt-5 d-flex align-items-center gap-2">
                        <span class="fw-bold">Bìa:</span>
                        @foreach ($ProductVariants->unique('variant_type') as $variant)
                            <button class="mx-1 variant-type-btn" type="button" data-type="{{ $variant->variant_type }}">
                                <p class="m-0">{{ $variant->variant_type }}</p>
                            </button>
                        @endforeach
                    </div>

                    {{-- Loại giấy --}}
                    <div class="mb-3">
                        <span class="fw-bold">Loại giấy:</span>
                        @foreach ($ProductVariants->unique('variant_value') as $variant)
                            <button class="mx-1 variant-value-btn" type="button"
                                data-value="{{ $variant->variant_value }}">
                                <p class="m-0">{{ $variant->variant_value }}</p>
                            </button>
                        @endforeach
                    </div>

                    {{-- Giá & tồn kho cho biến thể đầu tiên (ví dụ minh họa) --}}
                    @php $firstVariant = $ProductVariants->first(); @endphp

                    <div class="d-flex gap-1 align-items-center mb-3">
                        <span class="fw-bold">Giá:</span>
                        <p class="m-0 text-danger">{{ number_format($firstVariant->price, 0, ',', '.') }} VNĐ</p>
                    </div>

                    @if ($firstVariant->stock < 10)
                        <p class="text-warning fw-bold">⚠ Chỉ còn {{ $firstVariant->stock }} sản phẩm trong kho!</p>
                    @endif

                    {{-- Form thêm vào giỏ hàng --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="quantity-input mb-3 mt-2">
                            <label for="quantity" class="fw-bold">Số lượng:</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                max="{{ $firstVariant->stock }}" class="form-control">
                        </div>
                        <button type="submit" class="bg-dark text-white py-2 px-3 mt-3 border-1">Thêm vào giỏ hàng</button>
                        <p id="quantityError" class="text-danger mt-2" style="display: none;">Số lượng không hợp lệ!</p>
                    </form>
                    <p><strong>Số lượng có sẵn:</strong> {{ $firstVariant->stock }} sản phẩm</p>
                @else
                    {{-- KHÔNG CÓ BIẾN THỂ --}}
                    <p class="text-danger text-start price">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>

                    {{-- Tồn kho sản phẩm --}}
                    @if ($product->stock < 10)
                        <p class="text-warning fw-bold">⚠ Chỉ còn {{ $product->stock }} sản phẩm trong kho!</p>
                    @endif

                    {{-- Form thêm vào giỏ hàng --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="quantity-input mb-3 mt-2">
                            <label for="quantity" class="fw-bold">Số lượng:</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                max="{{ $product->stock }}" class="form-control">
                        </div>
                        <button type="submit" class="bg-dark text-white py-2 px-3 mt-3 border-1">Thêm vào giỏ hàng</button>
                        <p id="quantityError" class="text-danger mt-2" style="display: none;">Số lượng không hợp lệ!</p>
                    </form>
                    <p><strong>Số lượng có sẵn:</strong> {{ $product->stock }} sản phẩm</p>
                @endif

                {{-- Thương hiệu nếu có --}}
                @php
                    $brandNames = $product->brands
                        ->filter(fn($brand) => $brand->parent)
                        ->map(fn($brand) => $brand->name . ' (thuộc ' . $brand->parent->name . ')')
                        ->unique();
                @endphp
                @if ($brandNames->isNotEmpty())
                    <p style="font-size: 14px;"><strong>Tác giả:</strong> <span>{!! implode('<br>', $brandNames->toArray()) !!}</span></p>
                @endif
            </div>

        </div>

        <ul class="tabs wc-tabs product-tabs small-nav-collapse nav nav-uppercase nav-line nav-left" role="tablist">
            <li class="description_tab active" id="tab-title-description" role="presentation">
                <a href="#tab-description" role="tab" aria-selected="true" aria-controls="tab-description">Mô tả</a>
            </li>
            <li class="additional_information_tab" id="tab-title-additional_information" role="presentation">
                <a href="#tab-additional_information" role="tab" aria-selected="false"
                    aria-controls="tab-additional_information">Thông tin bổ sung</a>
            </li>
            <li class="reviews_tab" id="tab-title-reviews" role="presentation">
                <a href="#tab-reviews" role="tab" aria-selected="false" aria-controls="tab-reviews">Đánh giá (0)</a>
            </li>
        </ul>

        <!-- Nội dung của từng tab -->
        <div class="tab-content">
            <div id="tab-description" class="tab-pane active">
                <p>{{ $product->description }}</p>
            </div>
            <div id="tab-additional_information" class="tab-pane">
                <p>{{ $product->additional_information }}</p>
            </div>
            <div id="tab-reviews" class="tab-pane">
                <p>Hiện chưa có đánh giá nào.</p>
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
    </div>
@endsection
<script>
    function changeImage(imageUrl) {
        document.getElementById('mainProductImage').src = imageUrl;
    }
</script>
<style>
    /* Tổng thể */
    .container {
        max-width: 1200px;
        margin: auto;
        padding: 40px 20px;
    }

    .back-button {
        padding: 10px 20px;
        width: 25%;
        border-radius: 10px 10px 0px 0px;
        border-bottom: 2px dashed #ca2027;
        background: #fff;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Bố cục chính */
    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: #fff;
        padding: 30px;
        border-radius: 0px 10px 10px 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Hình ảnh sản phẩm */
    .product-image {
        position: relative;
        text-align: center;
    }

    .product-image img {
        width: 100%;
        max-width: 500px;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out;
    }

    .product-image img:hover {
        transform: scale(1.05);
    }

    /* Ảnh nhỏ */
    .product-thumbnails {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
    }

    .product-thumbnails .row {
        display: flex;
        justify-content: center;
    }

    .product-thumbnails img {
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }

    /* Thông tin sản phẩm */
    .product-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .product-info h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .product-info .price {
        font-size: 24px;
        font-weight: bold;
        color: #e60000;
        margin-bottom: 15px;
    }

    .product-info p {
        font-size: 16px;
        color: #555;
        margin-bottom: 10px;
    }

    .quantity-input {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 15px 0;
    }

    .quantity-input input {
        width: 60px;
        text-align: center;
        font-size: 18px;
        padding: 5px;
    }

    .button-group {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-primary {
        flex: 1;
        padding: 12px 20px;
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        background: #ca2027;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #ca2027;
    }

    .btn-outline {
        flex: 1;
        padding: 12px 20px;
        font-size: 18px;
        font-weight: bold;
        color: #ca2027;
        border: 2px solid #ca2027;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
        cursor: pointer;
    }

    .btn-outline:hover {
        background: #007bff;
        color: #fff;
    }

    /* Tabs sản phẩm */
    .tabs {
        display: flex;
        justify-content: center;
        border-bottom: 2px solid #ddd;
        padding: 0;
        list-style: none;
        margin: 40px 0 20px;
    }

    .tabs li {
        margin: 0 10px;
    }

    .tabs li a {
        display: block;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: bold;
        color: #555;
        border-bottom: 2px solid transparent;
        transition: all 0.3s;
    }

    .tabs li a:hover,
    .tabs li.active a {
        color: #007bff;
        border-bottom: 2px solid #007bff;
    }

    /* Nội dung tab */
    .tab-pane {
        display: none;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-top: 10px;
    }

    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Hiệu ứng mượt mà */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Sản phẩm liên quan */
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
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .related-products .card:hover {
        transform: translateY(-5px);
    }
</style>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        // Lấy tất cả các tab
        const tabs = document.querySelectorAll(".tabs.wc-tabs li a");
        const tabContents = document.querySelectorAll(".tab-pane");

        tabs.forEach(tab => {
            tab.addEventListener("click", function(e) {
                e.preventDefault(); // Ngăn chặn chuyển trang

                // Xóa class 'active' khỏi tất cả tab
                tabs.forEach(t => t.parentElement.classList.remove("active"));

                // Thêm class 'active' cho tab được click
                this.parentElement.classList.add("active");

                // Lấy ID nội dung tab tương ứng
                const targetTab = this.getAttribute("href").substring(1);

                // Ẩn tất cả nội dung tab
                tabContents.forEach(content => content.classList.remove("active"));

                // Hiển thị nội dung tab được chọn
                document.getElementById(targetTab).classList.add("active");
            });
        });
    });
</script>
