{{-- Dashboard --}}
@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Thống Kê</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Thống Kê Doanh Thu</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Tổng Doanh Thu: {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="/admin/orders">Xem Chi Tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Đơn Hàng Chờ Xử Lý: {{ $pendingOrders }}</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="/admin/orders?status=pending">Xem Chi Tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Sản Phẩm Bán Ra: {{ $totalItemsSold }}</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="/admin/order-items">Xem Chi Tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Sản Phẩm Sắp Hết Hàng: {{ $lowStockProducts }}</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="/admin/products?stock=low">Xem Chi Tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Số Đơn Hàng Theo Ngày
                    </div>
                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Doanh Thu Theo Ngày
                    </div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Danh Sách Đơn Hàng
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user ? $order->user->name : $order->name }}</td>
                                <td>{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Truyền dữ liệu cho JavaScript -->
    <script>
        window.chartData = {
            dates: @json(array_map(function ($date) {
                    return date('d/m', strtotime($date));
                }, $dates)),
            orderCounts: @json($orderCounts),
            revenueTotals: @json($revenueTotals)
        };
    </script>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
@endsection
