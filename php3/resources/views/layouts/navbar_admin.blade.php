<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d70c32c211.js" crossorigin="anonymous"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Cố định sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            /* Màu nền tối */
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #007bff;
        }

        .content {
            margin-left: 250px;
            /* Để tránh bị đè lên */
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white mb-3 mt-3"><img src="{{ asset('images/logo.webp') }}" alt="Logo"
                width="150"></h4>
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('products.index') }}"><i class="fas fa-box"></i> Sản phẩm</a>
        <a href="{{ route('category.index') }}"><i class="fas fa-list"></i> Danh mục</a>
        <a href="#"><i class="fas fa-users"></i> Người dùng</a>
        <a href="#"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
        <hr class="bg-light">
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>


    <!-- Nội dung chính -->
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-3">
            <div class="collapse navbar-collapse px-5" id="navbarNav">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    {{-- Tìm kiếm --}}
                    <form method="GET" action=""
                        class="d-flex align-items-center justify-content-between m-0 border rounded-pill px-3"
                        style="width: 500px;">
                        <input class="p-2 border-0 bg-transparent" style="outline: none;" name="search" type="search"
                            placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                        <button class="search-icon m-0 p-2 border-0 bg-transparent" style="outline: none;"
                            type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown d-flex align-items-center gap-2">
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->role }}"
                                class="rounded-circle">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                {{-- Nếu là admin truy cập thì sẽ có menu quản trị --}}
                                <a class="dropdown-item" href="#">Hồ sơ</a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Đăng xuất') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
        <div class="py-4">
            @yield('content')
        </div>
    </div>

</body>

</html>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif
