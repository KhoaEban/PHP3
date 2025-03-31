@extends('layouts.navbar_user')

@section('content')
    <div class="container-fluid mt-4">
        <div class="promo-code">
            <label for="promo-code">
                Nhập mã khuyến mại
            </label>
            <div style="display: flex;">
                <input id="promo-code" placeholder="" type="text" />
                <button>
                    Áp dụng
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
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
                            @if ($cart->items->isEmpty())
                                <tr>
                                    <td colspan="5">Giỏ hàng của bạn hiện đang trống.</td>
                                </tr>
                            @else
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
                                            <input
                                                style="width: 50px; text-align: center; padding: 4px; border: 1px solid #ccc;"
                                                type="number" value="{{ $item->quantity }}" min="1" />
                                        </td>
                                        <td>
                                            {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="remove">
                                            <a href="{{ route('cart.remove', $item->id) }}" style="color: red;">X</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-4">
                <div class="summary">
                    <div style="font-size: 1.25em; font-weight: bold;">
                        {{ $cart->items->count() }} sản phẩm
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <div>Tổng phụ</div>
                        <div>{{ number_format($total, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div>
                        <select>
                            <option>Chọn phương thức giao hàng</option>
                        </select>
                    </div>
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
        border: 1px solid #ccc;
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
</style>

<script></script>
