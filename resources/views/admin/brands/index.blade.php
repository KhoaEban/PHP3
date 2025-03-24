@extends('layouts.navbar_admin')

@section('content')
    <div class="container-fluid">
        <h2 class="my-4">Brands</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="">
                    <p class="fw-bold">Thêm thương hiệu</p>
                </div>
                <!-- Form thêm thương hiệu -->
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label for="parent_id">Parent Brand</label><br>
                        <select name="parent_id">
                            <option value="">None</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="form-group mb-3 d-flex align-items-center gap-2">
                        <div class="">
                            <label>Thumbnail</label>
                            <div class="thumbnail-preview">
                                <img width="50" src="" alt="Thumbnail" class="img-thumbnail"
                                    id="thumbnailPreview">
                            </div>
                        </div>
                        <input type="file" name="thumbnail">
                    </div>
                    <button class="btn" style="background-color: #CD5C5C; color: white;" type="submit">Thêm mới</button>
                </form>
            </div>
            <div class="col-md-8">

                <!-- Bảng danh sách thương hiệu -->
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    @if ($brand->thumbnail)
                                        <img width="50" src="{{ asset('uploads/brands/' . $brand->thumbnail) }}">
                                    @endif
                                </td>
                                <!-- nếu brand đó có brand cha thì thêm -- đăng sau -->
                                @if ($brand->parent)
                                    <td>
                                        <div class="d-flex">
                                            <span class="me-1">--</span>{{ $brand->name }}
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $brand->name }}</td>
                                @endif
                                {{-- <td>{{ $brand->name }}</td> --}}
                                <td>{{ $brand->description }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td>
                                    <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary">Sửa</a>
                                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger delete-btn" type="submit">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<style>
    .thumbnail-preview {
        width: 100px;
        height: 100px;
        overflow: hidden;
    }

    .thumbnail-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: none !important;
    }

    input:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
