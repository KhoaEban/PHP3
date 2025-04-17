@extends('layouts.sidebar_profile')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Địa chỉ</h2>

        <!-- Thông báo -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Danh sách địa chỉ -->
        <div class="address-list mb-4">
            @forelse ($addresses as $address)
                <div class="address-item {{ $address->is_default ? 'default-address' : '' }}">
                    <p><strong>Tên người nhận:</strong> {{ $address->recipient_name }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $address->address }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $address->phone }}</p>
                    <div class="action-buttons mt-2">
                        @if ($address->is_default)
                            <span class="badge bg-success">Mặc định</span>
                        @else
                            <form action="{{ route('user.addresses.setDefault', $address->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-primary btn-sm">Đặt làm mặc định</button>
                            </form>
                        @endif
                        <form action="{{ route('user.addresses.delete', $address->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="description">Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới!</p>
            @endforelse
        </div>

        <!-- Địa chỉ mặc định -->
        <h3 class="mb-3">Địa chỉ mặc định</h3>
        <div class="address-item default-address mb-4">
            @if ($defaultAddress)
                <p><strong>Tên người nhận:</strong> {{ $defaultAddress->recipient_name }}</p>
                <p><strong>Địa chỉ:</strong> {{ $defaultAddress->address }}</p>
                <p><strong>Số điện thoại:</strong> {{ $defaultAddress->phone }}</p>
                <span class="badge bg-success">Mặc định</span>
            @else
                <p class="description">Chưa có địa chỉ mặc định. Hãy thêm địa chỉ mới!</p>
            @endif
        </div>

        <!-- Nút mở modal thêm địa chỉ -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            Thêm địa chỉ mới
        </button>

        <!-- Modal thêm địa chỉ -->
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <form action="{{ route('user.addresses.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient_name">Tên người nhận</label>
                                <input type="text" name="recipient_name" id="recipient_name" class="form-control"
                                    required>
                                @error('recipient_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address" class="form-control" required>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_default" value="1"> Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Thêm địa chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>

    h2,
    h3 {
        color: #343a40;
    }

    .address-list {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        background-color: #ffffff;
    }

    .address-item {
        border-bottom: 1px solid #dee2e6;
        padding: 10px 0;
    }

    .address-item:last-child {
        border-bottom: none;
    }

    .default-address {
        background-color: #e9f7ef;
        border-left: 5px solid #28a745;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        transition: background-color 0.3s, border-color 0.3s;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .text-danger {
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.875rem;
    }
</style>
