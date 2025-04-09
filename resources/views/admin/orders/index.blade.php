@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid mt-4">
        <div class="header">
            <h1>Đơn hàng</h1>
        </div>
        <div class="filters">
            <div class="">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <select>
                        <option>
                            Thao tác
                        </option>
                    </select>
                    <button>
                        Áp dụng
                    </button>
                    <select name="status" class="">
                        <option value="">Lọc theo trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Hủy</option>
                    </select>

                    <select name="date" class="">
                        <option value="">Lọc theo thời gian</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                        <option value="this_week" {{ request('date') == 'this_week' ? 'selected' : '' }}>Tuần này</option>
                        <option value="this_month" {{ request('date') == 'this_month' ? 'selected' : '' }}>Tháng này
                        </option>
                    </select>

                    <button type="submit" class="btn btn-dark">Lọc</button>
                </form>
            </div>
            <div class="">
                {{-- Thanh tìm kiếm sản phẩm --}}
                <form method="GET" action="{{ route('products.index') }}" class="search-form d-flex border px-3"
                    style="width: 500px;">
                    <input class="p-2 border-0 w-100" style="outline: none;" name="search" type="search"
                        placeholder="Tìm kiếm sản phẩm" aria-label="Search" value="{{ request('search') }}">
                    <button class="btn bg-transparent text-muted border-0" style="outline: none;" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="responsive-table">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>SDT</th>
                        <th>Địa chỉ</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>PT Thanh toán</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                            </td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->address }}</td>
                            <td><span class="text-danger">{{ number_format($order->total) }} VNĐ</span></td>
                            <td>{{ $order->status }}</td>
                            <td>{{ strtoupper($order->payment_method) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}"
                                    class="text-decoration-none text-dark"><i class="fas fa-eye"
                                        title="Xem chi tiết đơn hàng"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $orders->links() }}
        </div>
        @foreach ($orders as $order)
            <!-- Modal -->
            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
                aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Chi tiết đơn hàng
                                #{{ $order->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Thông tin khách hàng -->
                                        <div class="mb-3">
                                            <p><strong>👤 Khách hàng:</strong> {{ $order->name }}</p>
                                            <p><strong>📍 Địa chỉ:</strong> {{ $order->address }}</p>
                                            <p><strong>📞 SĐT:</strong> {{ $order->phone }}</p>
                                            <p><strong>📦 Trạng thái:</strong> {{ $order->status }}</p>
                                        </div>
                                        <!-- Form cập nhật trạng thái -->
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                            class="mb-4 mt-4">
                                            @csrf
                                            <div class="input-group">
                                                <label class="py-2 px-2 bg-dark text-white" for="status">Trạng
                                                    thái:</label>
                                                <select name="status" class="form-select" id="status">
                                                    <option value="pending"
                                                        {{ $order->status == 'pending' ? 'selected' : '' }}>Đang xử
                                                        lý</option>
                                                    <option value="completed"
                                                        {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                        Hoàn tất</option>
                                                    <option value="failed"
                                                        {{ $order->status == 'failed' ? 'selected' : '' }}>
                                                        Thất bại
                                                    </option>
                                                    <option value="cancelled"
                                                        {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="bg-dark text-white d-block text-center py-2 border-0">Cập
                                                    nhật</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Danh sách sản phẩm -->
                                        <h5 class="mb-3 mt-4">🛍 Sản phẩm trong đơn hàng:</h5>
                                        <div class="row g-3">
                                            @foreach ($order->items as $item)
                                                <div class="col-md-12">
                                                    <div class="card shadow-sm">
                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                {{ $item->product->title ?? '[Sản phẩm đã xóa]' }}
                                                            </h6>
                                                            <p class="card-text">
                                                                <strong>Biến thể:</strong>
                                                                {{ $item->variant ? $item->variant->variant_type : 'Không có' }}
                                                                x
                                                                {{ $item->variant ? $item->variant->variant_value : 'Không có' }}<br>
                                                                <strong>Số lượng:</strong> {{ $item->quantity }}<br>
                                                                <strong>Giá:</strong> {{ number_format($item->price) }} VNĐ
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection


<style>
    .modal-backdrop.show {
        opacity: 0.1 !important;
        /* hoặc thấp hơn nếu cần */
    }

    .modal.fade {
        margin-top: 100px;
    }

    .responsive-table {
        overflow-x: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 14px;
    }

    input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .search-form {
        border-radius: 5px;
        background: white;
    }

    .search-form input {
        border: none;
    }

    .search-form button {
        cursor: pointer;
    }
</style>
