@extends('layouts.navbar_user')

@section('content')
    {{-- Bredcrumb --}}
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
        <div class="image-breadcrumb">
            <span>{{ Breadcrumbs::render(request('category') ? 'category' : 'products', $categories->firstWhere('slug', request('category'))) }}</span>
        </div>
    </nav>


    <div class="container-fluid px-5 mt-4">
        <div class="row">
            {{-- Sidebar Lọc Sản Phẩm --}}
            <div class="col-md-3">
                <h5 class="mb-3">DANH MỤC</h5>
                <ul class="list-group">
                    @foreach ($categories as $category)
                        @if ($category->parent_id === null)
                            <li class="list-group-item fw-bold">
                                <a href="{{ route('user.products', ['category' => $category->slug]) }}"
                                    class="text-decoration-none {{ request('category') == $category->slug}}">
                                    {{ $category->name }}
                                </a>

                                <!-- Kiểm tra nếu danh mục cha có danh mục con -->
                                @php
                                    $subCategories = $categories->where('parent_id', $category->id);
                                @endphp

                                @if ($subCategories->isNotEmpty())
                                    <button class="btn btn-link p-0 ms-2" data-bs-toggle="collapse"
                                        data-bs-target="#category-{{ $category->id }}">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>

                                    <ul class="list-group collapse ps-4" id="category-{{ $category->id }}">
                                        @foreach ($subCategories as $subCategory)
                                            <li class="list-group-item">
                                                <a href="{{ route('user.products', ['category' => $subCategory->slug]) }}"
                                                    class="text-decoration-none {{ request('category') == $subCategory->slug}}">
                                                    -- {{ $subCategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>



                {{-- Bộ lọc giá --}}
                <h5 class="mt-4">LỌC THEO GIÁ</h5>
                <div class="list-group border px-4 py-3">
                    <form method="GET" action="{{ route('user.products') }}" id="filter-form">
                        <input type="hidden" name="category" value="{{ request('category') }}">

                        <!-- Hiển thị khoảng giá -->
                        <div class="mb-3">
                            <label for="price-slider" class="form-label fw-bold">Khoảng giá:</label>

                            <div class="d-flex justify-content-between">
                                <span id="min_price_display">{{ number_format(request('min_price', 0), 0, ',', '.') }}
                                    đ</span>
                                <span
                                    id="max_price_display">{{ number_format(request('max_price', 10000000), 0, ',', '.') }}
                                    đ</span>
                            </div>

                            <!-- Thanh trượt -->
                            <div id="price-slider" class="mt-3"></div>
                        </div>

                        <!-- Input ẩn để gửi giá trị -->
                        <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                        <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 10000000) }}">

                        {{-- Gợi ý mức giá --}}
                        <div class="mb-3">
                            <strong>Gợi ý:</strong>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setPrice(0, 100000)">Dưới 100K</button>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setPrice(100000, 300000)">100K - 300K</button>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setPrice(300000, 500000)">300K - 500K</button>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setPrice(500000, 10000000)">Trên 500K</button>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <!-- Nút Lọc giờ sẽ ẩn, không cần click -->
                            <button type="submit" class="btn-dark flex-grow-1 p-2" id="submit-filter">Lọc</button>
                            <a href="{{ route('user.products') }}" class="btn-secondary p-2" id="clear-filter"
                                style="display: none;">Xóa</a>
                        </div>
                    </form>
                </div>

                <script>
                    function setPrice(min, max) {
                        document.querySelector('input[name="min_price"]').value = min;
                        document.querySelector('input[name="max_price"]').value = max;
                    }
                </script>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div class="col-md-9">
                {{-- Thanh sắp xếp --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">
                        @php
                            $selectedCategory = $categories->firstWhere('slug', request('category'));
                        @endphp

                        {{ $selectedCategory ? $selectedCategory->name : 'Tất cả sản phẩm' }}
                    </h2>

                    <form method="GET" action="{{ route('user.products') }}">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="">Sắp xếp theo</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp → cao
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao →
                                thấp</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        </select>
                    </form>
                </div>

                {{-- Hiển thị sản phẩm --}}
                <div class="row">
                    @if ($products->isEmpty())
                        <div class="col-12">
                            <p class="text-danger text-center mt-3">Không có sản phẩm trong danh mục này.</p>
                        </div>
                    @else
                        @foreach ($products as $product)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <a href="{{ route('user.products.show', $product->slug) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="card-img">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                                alt="{{ $product->title }}">
                                        </div>
                                        <div class="card-body text-center">
                                            <h6 class="card-title">{{ $product->title }}</h6>
                                            <p class="text-danger fw-bold">
                                                {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Phân trang --}}
                <div class="d-flex justify-content-center mt-4">
                    {!! $products->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery & jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {
            var minPrice = {{ request('min_price', 0) }};
            var maxPrice = {{ request('max_price', 10000000) }};

            $("#price-slider").slider({
                range: true,
                min: 0,
                max: 10000000,
                step: 10000,
                values: [minPrice, maxPrice],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);

                    $("#min_price_display").text(formatCurrency(ui.values[0]));
                    $("#max_price_display").text(formatCurrency(ui.values[1]));
                }
            });

            // Cập nhật hiển thị giá ban đầu
            $("#min_price_display").text(formatCurrency(minPrice));
            $("#max_price_display").text(formatCurrency(maxPrice));
        });

        // Hàm định dạng số tiền
        function formatCurrency(value) {
            return new Intl.NumberFormat("vi-VN").format(value) + " đ";
        }

        // Hàm cập nhật giá trị cho min_price và max_price
        function setPrice(min, max) {
            document.getElementById('min_price').value = min;
            document.getElementById('max_price').value = max;
            document.getElementById('min_price_display').innerText = min.toLocaleString('vi-VN') + ' đ';
            document.getElementById('max_price_display').innerText = max.toLocaleString('vi-VN') + ' đ';
            checkIfFilterApplied(); // Kiểm tra nếu bộ lọc đã được áp dụng

            // Gửi form tự động sau khi chọn mức giá
            document.getElementById('filter-form').submit();
        }

        // Kiểm tra nếu có bộ lọc đã được áp dụng để hiển thị nút "Xóa"
        function checkIfFilterApplied() {
            const minPrice = document.getElementById('min_price').value;
            const maxPrice = document.getElementById('max_price').value;
            const clearFilterButton = document.getElementById('clear-filter');

            if (minPrice > 0 || maxPrice < 10000000) {
                clearFilterButton.style.display = 'inline-block'; // Hiển thị nút "Xóa"
            } else {
                clearFilterButton.style.display = 'none'; // Ẩn nút "Xóa" nếu không có thay đổi
            }
        }

        // Lắng nghe sự thay đổi trong thanh trượt (slider) nếu có
        document.getElementById('price-slider').addEventListener('change', function() {
            setPrice(this.value.split(',')[0], this.value.split(',')[1]); // Lấy giá trị min/max từ thanh trượt
        });

        // Kiểm tra trạng thái ban đầu khi trang tải
        window.onload = checkIfFilterApplied;
    </script>
@endsection
<style>
    ol {
        background-color: #f8f9fa00 !important;
    }

    .image-breadcrumb {
        position: relative;
        background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=2128&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-breadcrumb::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.644);
        /* Điều chỉnh độ tối (0.5 là 50%) */
        z-index: 1;
    }

    .image-breadcrumb * {
        position: relative;
        z-index: 2;
    }

    .image-breadcrumb span {
        font-size: 2rem;
        text-align: center;
    }

    .breadcrumb-item a {
        color: white !important;
    }

    .card-img {
        height: 200px;
        overflow: hidden;
    }

    .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    ol {
        margin: 0 !important;
        padding: 10px 0 !important;
    }

    /* Thêm hiệu ứng hover cho các mục danh mục */
    .list-group-item a {
        transition: color 0.3s, background-color 0.3s;
    }

    .list-group-item a:hover {
        color: #007bff;
        /* Thay đổi màu chữ khi hover */
        background-color: #f8f9fa;
        /* Thêm màu nền cho mục khi hover */
    }

    /* Thêm hiệu ứng hover cho nút gợi ý mức giá */
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    /* Thêm hiệu ứng hover cho các nút lọc */
    .btn-success:hover {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    button,
    input {
        border: none;
    }

    button,
    input:focus {
        outline: none;
    }
</style>
