{{-- Product --}}
@extends('layouts.navbar_user')

@section('content')
    <div class="container">
        <h1>Product</h1>
        {{-- Render dữ liệu --}}
        @foreach ($products as $product)
            <div class="card">
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text">Price: {{ $product->price }}</p>
                        <p class="card-text">Stock: {{ $product->stock }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
