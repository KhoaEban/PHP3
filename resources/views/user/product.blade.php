@extends('layouts.navbar_user')

@section('content')
    {{-- Bredcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <div class="container">
                {{ Breadcrumbs::render(request('category') ? 'category' : 'products', $categories->firstWhere('slug', request('category'))) }}
            </div>
        </ol>
    </nav>
    

    <div class="container mt-4">
        <div class="row">
            {{-- Sidebar Lọc Sản Phẩm --}}
            <div class="col-md-3">
                <h5 class="mb-3">DANH MỤC</h5>
                <ul class="list-group">
                    @foreach ($categories as $category)
                        <li class="list-group-item">
                            <a href="{{ route('user.products', ['category' => $category->slug]) }}"
                                class="text-decoration-none {{ request('category') == $category->slug ? 'fw-bold text-primary' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- Bộ lọc giá --}}
                <h5 class="mt-4">LỌC THEO GIÁ</h5>
                <form method="GET" action="{{ route('user.products') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">

                    <div class="mb-2">
                        <input type="number" name="min_price" class="form-control" placeholder="Giá từ"
                            value="{{ request('min_price') }}">
                    </div>
                    <div class="mb-2">
                        <input type="number" name="max_price" class="form-control" placeholder="Đến"
                            value="{{ request('max_price') }}">
                    </div>

                    {{-- Gợi ý mức giá --}}
                    <div class="mb-3">
                        <strong>Gợi ý:</strong>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm" onclick="setPrice(0, 100000)">Dưới
                                100K</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm"
                                onclick="setPrice(100000, 300000)">100K - 300K</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm"
                                onclick="setPrice(300000, 500000)">300K - 500K</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm"
                                onclick="setPrice(500000, '')">Trên 500K</button>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">Lọc</button>
                        <a href="{{ route('user.products') }}" class="btn btn-secondary">Xóa</a>
                    </div>
                </form>

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
                    @foreach ($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <a href="{{ route('user.products.show', $product->slug) }}"
                                    class="text-decoration-none text-dark">
                                    <div class="card-img">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                            alt="{{ $product->title }}">
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $product->title }}</h6>
                                        <p class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }}
                                            VNĐ</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                <div class="d-flex justify-content-center mt-4">
                    {!! $products->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
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
</style>
