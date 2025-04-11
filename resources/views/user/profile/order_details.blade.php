@extends('layouts.navbar_user')
<style>
    .price {
        color: #e60000;
        font-weight: bold;
    }

    .rating-summary {
        background-color: #fff7f0;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .rating-summary .rating {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .rating-summary .rating span {
        font-size: 32px;
        font-weight: bold;
        color: #e60000;
    }

    .rating-summary .rating .text {
        font-size: 18px;
        margin-left: 8px;
    }

    .rating-summary .stars {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .rating-summary .stars i {
        color: #e60000;
        margin-right: 4px;
    }

    .rating-summary .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .rating-summary .filters button {
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    .rating-summary .filters button.active {
        background-color: #e60000;
        color: #fff;
        border: none;
    }

    .review {
        background-color: #fff;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
    }

    .review .user {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .review .user img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-right: 16px;
    }

    .review .user .info {
        display: flex;
        flex-direction: column;
    }

    .review .user .info .name {
        font-weight: bold;
    }

    .review .user .info .date {
        color: #888;
        font-size: 14px;
    }

    .review .user .info .stars {
        display: flex;
        align-items: center;
        margin-top: 4px;
    }

    .review .user .info .stars i {
        color: #e60000;
        margin-right: 4px;
    }

    .review .content {
        margin-bottom: 16px;
    }

    .review .content .highlight {
        font-weight: bold;
    }

    .review .images {
        display: flex;
        gap: 8px;
    }

    .review .images img {
        width: 96px;
        height: 96px;
        object-fit: cover;
    }

    .review .likes {
        display: flex;
        align-items: center;
        margin-top: 16px;
    }

    .review .likes i {
        color: #888;
    }

    .review .likes span {
        margin-left: 8px;
        color: #888;
    }
</style>
@section('content')
    <div class="bg-info p-3">
        <div class="container">
            <a href="{{ route('user.profile') }}" class="mt-3 text-white"><i class="fas fa-arrow-left"></i> Quay lại hồ sơ</a>
        </div>
    </div>
    <div class="container mt-4">
        <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Sản phẩm trong đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($order->items as $item)
                                <div class="col-md-4 mb-3">
                                    <div class="course-card text-center">
                                        @if ($item->variant)
                                            <img class="card-img-top"
                                                src="{{ $item->variant->images->isNotEmpty() ? asset('storage/' . $item->variant->images->first()->image_path) : 'https://placehold.co/300x200' }}"
                                                alt="{{ $item->product->title }} - {{ $item->variant->variant_value }}"
                                                onError="this.src='https://placehold.co/300x200'">
                                            <div class="content">
                                                <p class="title">
                                                    {{ $item->product->title }}
                                                    ({{ $item->variant->variant_type }}:
                                                    {{ $item->variant->variant_value }})
                                                </p>
                                                <p class="price">
                                                    {{ number_format($item->variant->getDiscountedPrice()) }} VNĐ
                                                    @if ($item->variant->getDiscountedPrice() < $item->variant->price)
                                                        <del class="text-muted">{{ number_format($item->variant->price) }}
                                                            VNĐ</del>
                                                    @endif
                                                </p>
                                                <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
                                            </div>
                                        @else
                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/300x200' }}"
                                                alt="{{ $item->product->title }}"
                                                onError="this.src='https://placehold.co/300x200'">
                                            <div class="content">
                                                <p class="title">{{ $item->product->title }}</p>
                                                <p class="price">
                                                    {{ number_format($item->product->getDiscountedPrice()) }} VNĐ
                                                    @if ($item->product->getDiscountedPrice() < $item->product->price)
                                                        <del class="text-muted">{{ number_format($item->product->price) }}
                                                            VNĐ</del>
                                                    @endif
                                                </p>
                                                <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Thông tin đơn hàng</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Mã đơn hàng:</strong> {{ $order->id }}</p>
                                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Tổng tiền:</strong> {{ number_format($order->total) }} VNĐ</p>
                                        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
                                        <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đánh giá sản phẩm -->
    <div class="container mt-4">
        <h1>ĐÁNH GIÁ SẢN PHẨM</h1>

        <!-- Form đánh giá -->
        @foreach ($order->items as $item)
            @if (!$order->reviews->where('product_id', $item->product_id)->where('variant_id', $item->variant_id ?? null)->first())
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Đánh giá {{ $item->product->title }}
                            {{ $item->variant ? '(' . $item->variant->variant_type . ': ' . $item->variant->variant_value . ')' : '' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.order.review', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <input type="hidden" name="variant_id" value="{{ $item->variant_id ?? '' }}">
                            <div class="form-group">
                                <label for="rating-{{ $item->id }}">Điểm đánh giá (1-5 sao):</label>
                                <select name="rating" id="rating-{{ $item->id }}" class="form-control" required>
                                    <option value="5">5 Sao</option>
                                    <option value="4">4 Sao</option>
                                    <option value="3">3 Sao</option>
                                    <option value="2">2 Sao</option>
                                    <option value="1">1 Sao</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment-{{ $item->id }}">Bình luận:</label>
                                <textarea name="comment" id="comment-{{ $item->id }}" class="form-control" rows="3"
                                    placeholder="Nhập bình luận của bạn"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Hiển thị đánh giá -->
        @if ($order->reviews->isNotEmpty())
            <div class="rating-summary px-4 mt-4">
                <div class="d-flex gap-3 align-items-center justify-content-between">
                    <div class="total_rating">
                        <div class="rating">
                            <span class="avg-rating">{{ number_format($order->reviews->avg('rating'), 1) }}</span>
                            <span class="text">trên 5</span>
                        </div>
                        <div class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $order->reviews->avg('rating'))
                                    <i class="fas fa-star text-danger"></i>
                                @elseif ($i - 0.5 <= $order->reviews->avg('rating'))
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star text-danger"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="total_filter">
                        <div class="filters">
                            <button class="filter-btn active" data-rating="all">Tất cả
                                ({{ $order->reviews->count() }})</button>
                            @for ($i = 5; $i >= 1; $i--)
                                <button class="filter-btn" data-rating="{{ $i }}">{{ $i }} Sao
                                    ({{ $order->reviews->where('rating', $i)->count() }})</button>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div id="reviews-container">
                @foreach ($order->reviews as $review)
                    <div class="review mt-3">
                        <div class="user">
                            <img alt="User avatar"
                                src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://placehold.co/50x50' }}"
                                onError="this.src='https://placehold.co/50x50'" />
                            <div class="info">
                                <span class="name">{{ $review->user->name }}</span>
                                <span class="date">{{ $review->created_at->format('Y-m-d H:i') }} |
                                    {{ $review->variant ? 'Phân loại: ' . $review->variant->variant_value : '' }}</span>
                                <div class="stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star text-danger"></i>
                                        @else
                                            <i class="far fa-star text-danger"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <p>{{ $review->comment }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mt-3">Chưa có đánh giá nào cho đơn hàng này.</p>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                const rating = $(this).data('rating');
                const orderId = {{ $order->id }};
                const url = '/profile/orders/' + orderId + '/review/filter/' + rating;

                console.log('Sending AJAX to:', url);

                $.ajax({
                    url: url,
                    method: 'GET',
                    xhrFields: {
                        withCredentials: true
                    }, // Gửi cookie nếu có auth
                    success: function(response) {
                        console.log('Response:', response);
                        $('.avg-rating').text(response.avg_rating);
                        $('#reviews-container').empty();

                        if (response.reviews.length > 0) {
                            response.reviews.forEach(function(review) {
                                let stars = '';
                                for (let i = 1; i <= 5; i++) {
                                    if (i <= review.rating) {
                                        stars +=
                                            '<i class="fas fa-star text-danger"></i>';
                                    } else {
                                        stars +=
                                            '<i class="far fa-star text-danger"></i>';
                                    }
                                }

                                const reviewHtml = `
                                <div class="review mt-3">
                                    <div class="user">
                                        <img alt="User avatar" src="${review.avatar}" onError="this.src='https://placehold.co/50x50'" />
                                        <div class="info">
                                            <span class="name">${review.user_name}</span>
                                            <span class="date">${review.date} | ${review.variant}</span>
                                            <div class="stars">${stars}</div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <p>${review.comment}</p>
                                    </div>
                                </div>
                            `;
                                $('#reviews-container').append(reviewHtml);
                            });
                        } else {
                            $('#reviews-container').html(
                                '<p class="mt-3">Không có đánh giá nào cho mức sao này.</p>'
                                );
                        }
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.status, xhr.statusText, xhr.responseText);
                        alert('Có lỗi xảy ra khi lọc đánh giá: ' + xhr.status + ' - ' + xhr
                            .statusText + '\nChi tiết: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
