@extends('layouts.navbar_user')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-4">
                <div class="profile">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : ($user->google_id ? 'https://www.google.com/s2/photos/profile/' . $user->google_id : 'https://placehold.co/96x96') }}"
                        alt="User profile picture">
                    <p class="name h4 text-center mt-3">{{ $user->name }}</p>
                    <button><i class="fas fa-edit"></i> <a href="{{ route('user.profile.edit') }}">Chỉnh sửa hồ
                            sơ</a></button>
                    <p class="description mt-3 p-3 text-center"><i class="fas fa-info-circle"></i>
                        {{ $user->description ? $user->description : 'Chưa cập nhật' }}</p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="email mt-3"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                            <p class="role"><i class="fas fa-user"></i> Vai trò:
                                {{ $user->role == 'admin' ? 'Quản trị viên' : 'Người dùng' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="joined mt-3"><i class="fas fa-calendar-alt"></i>
                                Tham gia từ:
                                {{ $user->created_at->format('d/m/Y') }}</p>
                            {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-12 mb-5">
                        <!-- Sản phẩm đã xem -->
                        <div class="courses">
                            <div class="header mb-3">
                                <p>Sản phẩm đã xem ({{ $viewedProducts->count() }})</p>
                            </div>
                            <div class="swiper viewed-products-swiper">
                                <div class="swiper-wrapper">
                                    @forelse($viewedProducts as $product)
                                        <div class="swiper-slide">
                                            <div class="course-card">
                                                <img class="card-img-top lazyload"
                                                    src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/300x200' }}"
                                                    alt="{{ $product->title }}"
                                                    onError="this.src='https://placehold.co/300x200'">
                                                <div class="content">
                                                    <p class="title">{{ $product->title }}</p>
                                                    <p class="price">{{ number_format($product->getDiscountedPrice()) }}
                                                        VNĐ</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>Chưa có sản phẩm nào đã xem.</p>
                                    @endforelse
                                </div>
                                <!-- Nút điều hướng -->
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                                <!-- Thanh cuộn (scrollbar) - tùy chọn -->
                                <div class="swiper-scrollbar"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm đã mua -->
                    <div class="col-12 mt-5">
                        <div class="courses">
                            <div class="header mb-3 d-flex justify-content-between align-items-center">
                                <p>Sản phẩm đã mua ({{ $orders->flatMap->items->count() }})</p>
                                <a style="text-decoration: none; margin-bottom: 16px" href="{{ route('user.order.history') }}">Xem lịch sử đơn hàng</a>
                            </div>
                            <div class="swiper purchased-products-swiper">
                                <div class="swiper-wrapper">
                                    @forelse($orders as $order)
                                        @foreach ($order->items as $item)
                                            <div class="swiper-slide">
                                                <div class="course-card">
                                                    @if ($item->variant)
                                                        <a href="{{ route('user.order.details', $order->id) }}">
                                                            <!-- Hiển thị thông tin biến thể -->
                                                            <img src="{{ $item->variant->images->isNotEmpty() ? asset('storage/' . $item->variant->images->first()->image_path) : 'https://placehold.co/300x200' }}"
                                                                alt="{{ $item->product->title }} - {{ $item->variant->variant_value }}"
                                                                onError="this.src='https://placehold.co/300x200'">
                                                            <div class="content">
                                                                <p class="title">
                                                                    {{ $item->product->title }}
                                                                    ({{ $item->variant->variant_type }}:
                                                                    {{ $item->variant->variant_value }})
                                                                </p>
                                                                <p class="price">
                                                                    {{ number_format($item->variant->getDiscountedPrice()) }}
                                                                    VNĐ
                                                                    @if ($item->variant->getDiscountedPrice() < $item->variant->price)
                                                                        <del class="text-muted">{{ number_format($item->variant->price) }}
                                                                            VNĐ</del>
                                                                    @endif
                                                                </p>
                                                                <div class="info">
                                                                    <i class="fas fa-user"></i> Thương hiệu:
                                                                    {{ $item->product->brands->first()->name ?? 'Chưa xác định' }}
                                                                    <i class="fas fa-folder"></i> Danh mục:
                                                                    {{ $item->product->categories->first()->name ?? 'Chưa xác định' }}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('user.order.details', $order->id) }}">
                                                            <!-- Hiển thị thông tin sản phẩm chính -->
                                                            <img src="{{ $product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/300x200' }}"
                                                                alt="{{ $item->product->title }}"
                                                                onError="this.src='https://placehold.co/300x200'">
                                                            <div class="content">
                                                                <p class="title">{{ $item->product->title }}</p>
                                                                <p class="price">
                                                                    {{ number_format($item->product->getDiscountedPrice()) }}
                                                                    VNĐ
                                                                    @if ($item->product->getDiscountedPrice() < $item->product->price)
                                                                        <del class="text-muted">{{ number_format($item->product->price) }}
                                                                            VNĐ</del>
                                                                    @endif
                                                                </p>
                                                                <div class="info">
                                                                    <i class="fas fa-user"></i> Thương hiệu:
                                                                    {{ $item->product->brands->first()->name ?? 'Chưa xác định' }}
                                                                    <i class="fas fa-folder"></i> Danh mục:
                                                                    {{ $item->product->categories->first()->name ?? 'Chưa xác định' }}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @empty
                                        <p>Chưa có sản phẩm nào được mua.</p>
                                    @endforelse
                                </div>
                                <!-- Nút điều hướng -->
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                                <!-- Thanh cuộn (scrollbar) - tùy chọn -->
                                <div class="swiper-scrollbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Khởi tạo Swiper cho Sản phẩm đã xem
        new Swiper('.viewed-products-swiper', {
            slidesPerView: 4, // Mặc định hiển thị 4 sản phẩm
            spaceBetween: 20, // Khoảng cách giữa các slide
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
            },
            breakpoints: {
                // Mobile nhỏ (< 576px)
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                // Mobile lớn (576px - 768px)
                576: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                // Tablet (768px - 992px)
                768: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
                // Desktop (>= 992px)
                992: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
            },
        });

        // Khởi tạo Swiper cho Sản phẩm đã mua
        new Swiper('.purchased-products-swiper', {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
            },
        });
    </script>
@endsection

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
    }

    .profile {
        display: flex;
        flex-direction: column;
        padding: 0 44px;
    }

    .profile img {
        width: 216px;
        height: 216px;
        border-radius: 50%;
        background-color: #e2e2e2;
        margin-bottom: 16px;
        margin: auto;
    }

    .profile p {
        margin: 4px 0;
    }

    .profile button {
        margin-top: 8px;
        padding: 8px 16px;
        background-color: #e2e2e2;
        border: none;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 500;
    }

    .description {
        margin-top: 8px;
        padding: 8px 16px;
        background-color: #e2e2e2;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
    }

    .activity {
        margin-top: 32px;
    }

    .activity p {
        font-size: 18px;
        font-weight: 600;
    }

    .activity .grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 4px;
        margin-top: 8px;
    }

    .activity .grid div {
        width: 16px;
        height: 16px;
        background-color: #e2e2e2;
    }

    .activity .options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
    }

    .activity .options select {
        margin-left: 8px;
        font-size: 14px;
        color: #4a4a4a;
    }

    .activity .legend {
        display: flex;
        align-items: center;
    }

    .activity .legend span {
        font-size: 12px;
        color: #4a4a4a;
    }

    .activity .legend div {
        width: 16px;
        height: 16px;
        margin-left: 4px;
    }

    .activity .legend .bg-gray {
        background-color: #e2e2e2;
    }

    .activity .legend .bg-green-200 {
        background-color: #c6f6d5;
    }

    .activity .legend .bg-green-400 {
        background-color: #68d391;
    }

    .activity .legend .bg-green-600 {
        background-color: #48bb78;
    }

    .activity .legend .bg-green-800 {
        background-color: #2f855a;
    }

    .courses {
        height: 350px;
        margin-top: 32px;
    }

    .courses .header {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #e2e2e2;
    }

    .courses .header p {
        font-size: 18px;
        font-weight: 600;
    }

    .courses .grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 16px;
        margin-top: 16px;
    }

    @media (min-width: 768px) {
        .courses .grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .courses .grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1280px) {
        .courses .grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .course-card {
        background-color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .course-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }

    .course-card .content {
        padding: 16px;
    }

    .course-card .content p {
        margin: 4px 0;
    }

    .course-card .content .title {
        font-size: 18px;
        font-weight: 600;
    }

    .course-card .content .price {
        color: #e53e3e;
        margin-top: 8px;
    }

    .course-card .content .info {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #4a4a4a;
        margin-top: 8px;
    }

    .course-card .content .info i {
        margin-right: 4px;
    }

    .course-card .content .info i+span {
        margin-left: 16px;
    }

    /* Container cho Swiper */
    .courses .swiper {
        position: relative;
        padding-bottom: 30px;
        /* Không gian cho scrollbar */
    }

    /* Đảm bảo course-card đầy đủ chiều rộng trong slide */
    .courses .swiper-slide .course-card {
        width: 100%;
        height: 350px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    /* Tùy chỉnh nút điều hướng */
    .swiper-button-prev,
    .swiper-button-next {
        color: #333;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .swiper-button-prev:hover,
    .swiper-button-next:hover {}

    /* Tùy chỉnh scrollbar */
    .swiper-scrollbar {
        background: #e0e0e0;
        height: 5px;
    }

    .swiper-scrollbar-drag {
        background: #007bff;
        cursor: grab;
    }

    /* Responsive cho course-card */
    @media (max-width: 576px) {
        .course-card {
            padding: 10px;
        }

        .course-card img {
            max-height: 150px;
            object-fit: cover;
        }
    }
</style>
