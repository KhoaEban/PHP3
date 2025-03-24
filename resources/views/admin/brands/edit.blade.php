@extends('layouts.navbar_admin')

@section('content')
    <div class="card-title mt-3" style="background-color: #B0C4DE">
        <h1 class="h6 p-3">Chỉnh sửa thương hiệu</h1>
    </div>

    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ $brand->name }}" required>

        <label>Slug</label>
        <input type="text" name="slug" value="{{ $brand->slug }}" required>

        <label>Parent Brand</label>
        <select name="parent_id">
            <option value="">None</option>
            @foreach ($brands as $b)
                <option value="{{ $b->id }}" {{ $b->id == $brand->parent_id ? 'selected' : '' }}>{{ $b->name }}
                </option>
            @endforeach
        </select>

        <label>Description</label>
        <textarea name="description">{{ $brand->description }}</textarea>

        <label>Thumbnail</label>
        <input type="file" name="thumbnail">

        <button type="submit">Update Brand</button>
    </form>
@endsection
