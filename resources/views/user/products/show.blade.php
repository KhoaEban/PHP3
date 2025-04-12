@extends('layouts.navbar_user')

@section('content')
    <div class="container mt-5">
        <div class="back-button" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('user.products') }}" class="text-decoration-none p-2">← Quay lại trang sản phẩm</a>
        </div>

        <div class="product-details">
            <!-- Hình ảnh sản phẩm -->
            <div class="product-image">
                <img id="mainProductImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                    class="w-100" height="500">

                <!-- Hình ảnh biến thể -->
                <div class="product-thumbnails mt-3">
                    <div class="container py-0" style="width: 500px; overflow-x: scroll;">
                        <div id="variant-thumbnails" class="d-flex" style="width: max-content;">
                            @foreach ($variantImages as $images)
                                @foreach ($images as $image)
                                    <div class="p-1">
                                        <img class="variant-thumbnail" src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="Product Image" height="60px">
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-info">
                <h2>{{ $product->title }}</h2>
                @php
                    // Kiểm tra nếu sản phẩm có biến thể
                    $hasVariants = $product->variants->isNotEmpty();
                    $minPrice = $hasVariants ? $product->variants->min('price') : $product->price;
                    $maxPrice = $hasVariants ? $product->variants->max('price') : $product->price;
                    $totalStock = $hasVariants ? $product->variants->sum('stock') : $product->stock;
                @endphp

                <p class="text-danger price">
                    <span id="price">
                        {{ number_format($minPrice, 0, ',', '.') }}
                        @if ($hasVariants && $minPrice !== $maxPrice)
                            - {{ number_format($maxPrice, 0, ',', '.') }}
                        @endif
                    </span> VNĐ
                </p>
                <!-- Thương hiệu sản phẩm -->
                @php
                    $brandNames = $product->brands->pluck('name')->implode(', ');
                @endphp
                <p><strong>Thương hiệu:</strong> {{ $brandNames }}</p>
                <p>{{ $product->description }}</p>
                @if ($hasVariants)
                    <label>Chọn biến thể:</label>
                    <select id="variant-select">
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-price="{{ $variant->price }}"
                                data-stock="{{ $variant->stock }}"
                                data-images="{{ json_encode($variantImages[$variant->id]->pluck('image_path')) }}">
                                {{ $variant->variant_type }} - {{ $variant->variant_value }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" id="variant_id" name="variant_id">

                    <label for="quantity">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1"
                        max="{{ $totalStock }}" class="form-control">

                    <button type="submit" class="btn btn-dark mt-3">🛒 Thêm vào giỏ hàng</button>
                </form>

                <p class="mt-3"><strong>Số lượng trong kho:</strong> <span id="stock">{{ $totalStock }}</span></p>
                <!-- Script cập nhật hình ảnh -->
                <script>
                    document.getElementById('variant-select').addEventListener('change', function() {
                        document.getElementById('variant_id').value = this.value;
                        let selectedOption = this.options[this.selectedIndex];

                        let formattedPrice = new Intl.NumberFormat('vi-VN').format(selectedOption.getAttribute('data-price'));

                        document.getElementById('price').innerText = formattedPrice;
                        document.getElementById('stock').innerText = selectedOption.getAttribute('data-stock');

                        // Update main image
                        let imagePaths = JSON.parse(selectedOption.getAttribute('data-images'));
                        if (imagePaths.length > 0) {
                            document.getElementById('mainProductImage').src = "/storage/" + imagePaths[0];
                        }

                        // Update thumbnails
                        let thumbnailsContainer = document.getElementById('variant-thumbnails');
                        thumbnailsContainer.innerHTML = "";

                        // If a variant is selected, show only related images
                        if (imagePaths.length > 0) {
                            imagePaths.forEach(path => {
                                let thumbnailDiv = document.createElement('div');
                                thumbnailDiv.classList.add('p-1');

                                let thumbnailImg = document.createElement('img');
                                thumbnailImg.src = "/storage/" + path;
                                thumbnailImg.alt = "Variant Image";
                                thumbnailImg.height = 60;

                                thumbnailDiv.appendChild(thumbnailImg);
                                thumbnailsContainer.appendChild(thumbnailDiv);
                            });
                        } else {
                            // If no variant is selected, show all images again
                            variantImages.forEach(images => {
                                images.forEach(image => {
                                    let thumbnailDiv = document.createElement('div');
                                    thumbnailDiv.classList.add('p-1');

                                    let thumbnailImg = document.createElement('img');
                                    thumbnailImg.src = "/storage/" + image.image_path;
                                    thumbnailImg.alt = "Product Image";
                                    thumbnailImg.height = 60;

                                    thumbnailDiv.appendChild(thumbnailImg);
                                    thumbnailsContainer.appendChild(thumbnailDiv);
                                });
                            });
                        }
                    });
                    window.addEventListener('DOMContentLoaded', function() {
                        let variantSelect = document.getElementById('variant-select');
                        if (!variantSelect || variantSelect.options.length === 0) {
                            let formattedPrice = new Intl.NumberFormat('vi-VN').format(document.getElementById('price')
                                .innerText);
                            document.getElementById('price').innerText = formattedPrice + " VNĐ";

                            // Đảm bảo không có lỗi khi sản phẩm không có biến thể
                            let thumbnailsContainer = document.getElementById('variant-thumbnails');
                            thumbnailsContainer.innerHTML = "";
                            variantImages.forEach(images => {
                                images.forEach(image => {
                                    let thumbnailDiv = document.createElement('div');
                                    thumbnailDiv.classList.add('p-1');

                                    let thumbnailImg = document.createElement('img');
                                    thumbnailImg.src = "/storage/" + image.image_path;
                                    thumbnailImg.alt = "Product Image";
                                    thumbnailImg.height = 60;

                                    thumbnailDiv.appendChild(thumbnailImg);
                                    thumbnailsContainer.appendChild(thumbnailDiv);
                                });
                            });
                        }
                    });
                </script>
            </div>
        </div>


        <ul class="tabs wc-tabs nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-title-description" data-bs-toggle="tab" href="#tab-description"
                    role="tab">Mô tả</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-title-additional_information" data-bs-toggle="tab"
                    href="#tab-additional_information" role="tab">Thông tin bổ sung</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-title-reviews" data-bs-toggle="tab" href="#tab-reviews" role="tab">Đánh
                    giá</a>
            </li>
        </ul>


        <!-- Nội dung của từng tab -->
        <div class="tab-content">
            <div id="tab-description" class="tab-pane fade show active" role="tabpanel">
                <p>{{ $product->description }}</p>
            </div>
            <div id="tab-additional_information" class="tab-pane fade" role="tabpanel">
                <p>{{ $product->additional_information }}</p>
            </div>
            <div id="tab-reviews" class="tab-pane fade" role="tabpanel">
                <h3 class="mb-3">Đánh Giá Của Người Mua</h3>
                <!-- Hiển thị danh sách đánh giá từ người đã mua -->
                @if ($product->reviews->isEmpty())
                    <p>Hiện chưa có đánh giá nào từ người mua.</p>
                @else
                    @foreach ($product->reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <span class="user-name">{{ $review->user->name }}</span>
                                <span class="review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="review-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="review-comment">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Sản phẩm liên quan -->
        @if ($relatedProducts->isNotEmpty())
            <div class="related-products">
                <h4>Sản phẩm liên quan</h4>
                <div class="row">
                    @foreach ($relatedProducts as $related)
                        <div class="col-md-3">
                            <div class="card shadow-sm" style="height: 100%;">
                                <a href="{{ route('user.products.show', $related->slug) }}"
                                    class="text-decoration-none text-dark">
                                    <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top"
                                        alt="{{ $related->title }}" height="200px">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $related->title }}</h6>
                                        <p class="text-danger fw-bold">{{ number_format($related->price, 0, ',', '.') }}
                                            VNĐ</p>
                                        <span class="card-text">Tác giả:
                                            {{ $related->brands->pluck('name')->implode(', ') }}</span>
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
    document.getElementById('variant-select').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];

        document.getElementById('price').innerText = selectedOption.getAttribute('data-price') + " VNĐ";
        document.getElementById('stock').innerText = selectedOption.getAttribute('data-stock');

        let imagePath = selectedOption.getAttribute('data-image');
        document.getElementById('mainProductImage').src = imagePath;
    });

    function changeImage(imageUrl) {
        document.getElementById('mainProductImage').src = imageUrl;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Lấy tất cả các tab và nội dung tab
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
                const targetTab = this.getAttribute("href").replace("#", "");

                // Ẩn tất cả nội dung tab trước khi hiển thị tab mới
                tabContents.forEach(content => content.classList.remove("show", "active"));

                // Hiển thị nội dung tab được chọn
                const targetContent = document.getElementById(targetTab);
                if (targetContent) {
                    targetContent.classList.add("show", "active");
                }
            });
        });
    });
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
