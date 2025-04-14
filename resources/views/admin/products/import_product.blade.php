@extends('layouts.navbar_admin')

@section('content')
    <div class="container mt-5" style="max-width: 600px">
        <!-- Form tải file Excel -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-file-excel me-1"></i>
                Import Products from Excel
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Upload Excel File</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls"
                            required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="overwrite" name="overwrite">
                        <label class="form-check-label" for="overwrite">Overwrite existing products</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="{{ asset('templates/products_import_template.xlsx') }}" class="btn btn-secondary">Download
                        Template</a>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="notification success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="notification error">{{ session('error') }}</div>
    @endif
@endsection
