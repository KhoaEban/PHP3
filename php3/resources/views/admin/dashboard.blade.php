{{-- Dashboard --}}
@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                {{-- Thống kê --}}
                <div class="card">
                    <div class="card-header">
                        <h2>Thống kê</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Sản phẩm</h5>
                                        <p class="card-text fs-3">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Khách hàng</h5>
                                        <p class="card-text fs-3">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Đơn hàng</h5>
                                        <p class="card-text fs-3">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-danger mb-3">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Doanh thu</h5>
                                        <p class="card-text fs-3">0 VNĐ</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Biểu đồ doanh thu --}}
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Doanh thu trong 7 ngày qua</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Thêm script Chart.js --}}
@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!}, // Mảng ngày
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenues) !!}, // Mảng doanh thu
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script> --}}
@endsection
