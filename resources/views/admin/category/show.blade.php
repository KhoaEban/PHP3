@extends('layouts.navbar_admin')

@section('content')
    <div class="card-title mt-3" style="background-color: #B0C4DE">
        <h1 class="h6 p-3">Chi tiết danh mục</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                {{-- Nút quay lại --}}
                <div class="btn-back mt-5">
                    <a href="{{ route('category.index') }}" class="text-decoration-none text-dark">
                        <i class="fa fa-arrow-left me-2"></i>Quay lại trang danh sách
                    </a>
                </div>

                {{-- Thông tin danh mục --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>Danh mục: {{ $category->name }}</h3>
                    </div>
                    <div class="card-body">
                        {{-- Hình ảnh danh mục --}}
                        <div class="mb-3">
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="300"
                                    class="img-thumbnail">
                            @else
                                <span class="text-muted">Không có hình ảnh</span>
                            @endif
                        </div>

                        {{-- Hiển thị danh mục con dưới dạng danh sách --}}
                        @if ($category->children->count() > 0)
                            <h4 class="mt-4">Danh mục con</h4>
                            <ul class="list-group">
                                @foreach ($category->children as $subcategory)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            {{-- Ảnh danh mục con --}}
                                            @if ($subcategory->image)
                                                <img src="{{ asset($subcategory->image) }}" alt="{{ $subcategory->name }}"
                                                    width="40" class="rounded-circle me-2">
                                            @endif
                                            <a href="{{ route('category.show', $subcategory->id) }}"
                                                class="text-decoration-none">{{ $subcategory->name }}</a>
                                        </div>

                                        {{-- Hành động --}}
                                        <div>
                                            <a href="{{ route('category.edit', $subcategory->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <form action="{{ route('category.remove_parent', $subcategory->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-unlink"></i> Bỏ cha
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mt-3">Không có danh mục con.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
