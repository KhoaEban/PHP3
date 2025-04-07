@extends('layouts.navbar_user')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center fw-bold">Giỏ hàng</h1>
        <div class="promo-code">
            <label for="promo-code">
                Nhập mã khuyến mại
            </label>
            <div style="display: flex;">
                <input id="promo-code" placeholder="Nhận mã khuyến mãi..." type="text" />
                <button>
                    Áp dụng
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div style="overflow-x: auto;">
                    <table>
                        <thead class="text-center">
                            <tr>
                                <th>
                                    Mục
                                </th>
                                <th>
                                    Giá
                                </th>
                                <th>
                                    Số lượng
                                </th>
                                <th>
                                    Tổng phụ
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (is_object($cart) && $cart->items->isNotEmpty())
                                @foreach ($cart->items as $item)
                                    <tr>
                                        <td class="item-details">
                                            <img alt="{{ $item->product->title }}" height="50"
                                                src="{{ asset('storage/' . $item->product->image) }}" width="50" />
                                            <div class="mx-2">
                                                <div style="font-weight: bold;">
                                                    {{ $item->quantity }} x {{ $item->product->title }}
                                                </div>
                                                <div style="color: #666;">
                                                    Mã sản phẩm: {{ $item->product->id }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ number_format($item->product->price, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td>
                                            <input class="update-quantity" type="number" value="{{ $item->quantity }}"
                                                min="1" data-item-id="{{ $item->id }}"
                                                style="width: 50px; text-align: center; padding: 4px; border: 1px solid #ccc;">
                                        </td>
                                        <td>
                                            {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="remove">
                                            <a href="{{ route('cart.remove', $item->id) }}" style="color: red;">X</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-danger fw-bold">Giỏ hàng của bạn hiện đang
                                        trống.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-4">
                <div class="summary">
                    @if (isset($cart) && is_object($cart) && $cart->items->isNotEmpty())
                        <div style="font-size: 1.25em; font-weight: bold;">
                            {{ $cart->items->count() }} sản phẩm
                        </div>
                    @else
                        <div style="font-size: 1.25em; font-weight: bold;" class="text-danger">
                            Giỏ hàng trống
                        </div>
                    @endif
                    <div class="divider-top"></div>
                    <div style="display: flex; justify-content: space-between;">
                        <div>Tổng phụ</div>
                        <div>{{ number_format($total, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div>
                        <select>
                            <option>Chọn phương thức giao hàng</option>
                        </select>
                    </div>
                    <div class="divider-bottom"></div>
                    <br>
                    <div style="display: flex; justify-content: space-between; font-weight: bold;">
                        <div>TỔNG</div>
                        <div>{{ number_format($total, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <button>THANH TOÁN</button>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".update-quantity").forEach(input => {
            input.addEventListener("change", function() {
                let itemId = this.dataset.itemId;
                let quantity = this.value;

                if (quantity < 1) {
                    this.value = 1; // Ngăn nhập số âm
                    quantity = 1;
                }

                fetch("{{ route('cart.update') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            item_id: itemId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Tải lại trang để cập nhật giá tiền
                        } else {
                            alert("Lỗi: " + data.message);
                        }
                    })
                    .catch(error => console.error("Lỗi:", error));
            });
        });
    });
</script>


<style>
    .promo-code {
        margin-bottom: 16px;
    }

    .promo-code label {
        display: block;
        margin-bottom: 8px;
    }

    .promo-code input {
        padding: 8px;
        border: 1px solid #ccc;
        flex-grow: 1;
    }

    .promo-code button {
        background-color: #333;
        color: white;
        padding: 8px 16px;
        border: none;
        margin-left: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    table,
    th,
    td {
        border: 1px solid #ccc !important;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    .item img {
        width: 50px;
        height: 50px;
        margin-right: 16px;
    }

    .item-details {
        display: flex;
        align-items: center;
    }

    .item-details div {
        margin-right: 16px;
    }

    .remove {
        color: blue;
        cursor: pointer;
    }

    .summary {
        background-color: #f7f7f7;
        padding: 16px;
        border: 1px solid #ccc;
        width: 100%;
    }

    .summary div {
        margin-bottom: 16px;
    }

    .summary select,
    .summary button {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
    }

    .summary button {
        background-color: #333;
        color: white;
        border: none;
    }

    .divider-bottom {
        border: 1px dashed #ccc;
        margin: 40px 0;
    }

    .divider-top {
        border: 1px dashed #ccc;
        margin: 16px 0;
    }
</style>

<script></script>
