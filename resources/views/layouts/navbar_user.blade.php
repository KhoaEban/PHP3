<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/d70c32c211.js" crossorigin="anonymous"></script>

    <!-- Favicons -->
    <link href="/img/favicon.png" rel="icon">
    <link href="/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Bootstrap CSS File -->
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/lib/animate/animate.min.css" rel="stylesheet">
    <link href="/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid px-5">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav">
                    <!-- Logo -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img width="150px" src="{{ asset('images/logo.webp') }}" alt="Slide 1">
                    </a>
                </ul>

                <!-- Center Side Of Navbar -->
                <ul class="navbar-nav mx-auto">
                    {{-- Tìm kiếm --}}
                    <li class="nav-item me-5">
                        <form method="GET" action="{{ route('user.products.search') }}" class="search-form">
                            <input class="form-control" name="search" type="search" placeholder="Tìm kiếm sản phẩm"
                                aria-label="Search">
                            <button class="search-icon" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>
                    {{-- Trang chủ --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Trang chủ</a>
                    </li>
                    {{-- Sản phẩm --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.products') }}">Sản phẩm</a>
                    </li>
                    {{-- About --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">Về chúng tôi</a>
                    </li>

                    {{-- Tìm kiếm --}}
                    {{-- <li class="nav-item">
                        <form method="GET" action="" class="search-form">
                            <input class="form-control" name="search" type="search"
                                placeholder="Tìm kiếm sản phẩm" aria-label="Search">
                            <button class="search-icon" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li> --}}
                </ul>


                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    {{-- Giỏ hàng --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.show') }}" style="position: relative;">
                            <div class="discount-icon text-center text-white d-flex justify-content-center align-items-center"
                                style="position: absolute; top: 0px; right: 0px; background-color: red; width: 20px; height: 20px; border-radius: 50%;">
                                <span class="discount-number">
                                    {{ $totalItems }}
                                </span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                style="width: 30px; height: 30px;">
                                <path fill-rule="evenodd"
                                    d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>

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
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                {{-- Nếu là admin truy cập thì sẽ có menu quản trị --}}
                                @if (Auth::user()->role == 'admin')
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Quản trị</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('user.profile') }}">Hồ sơ</a>

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
        </div>
    </nav>

    <main class="">
        @yield('content')
    </main>

    <!-- Chat Button -->
    <div id="chat-container">
        <div id="chat-bubble" class="d-none">
            <iframe src="{{ route('chat.index') }}" frameborder="0"
                style="width: 100%; height: 100%; border-radius: 15px;"></iframe>
        </div>
        <button id="chat-toggle" class="btn btn-primary chat-toggle-btn">
            <i class="fas fa-comments"></i> Chat
        </button>
    </div>
    <script>
        document.getElementById('chat-toggle').addEventListener('click', function() {
            const chatBubble = document.getElementById('chat-bubble');
            chatBubble.classList.toggle('d-none');
        });
    </script>
    <style>
        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
        }

        .dropdown-item {
            white-space: normal;
        }

        .bg_finished {
            background-color: #00ff002c !important;
        }

        .bg_unfinished {
            background-color: #ff00002c !important;
        }

        /* Chat Container */
        #chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        /* Chat Toggle Button */
        .chat-toggle-btn {
            font-size: 1.2em;
            /* Increase font size for larger text/icon */
            padding: 15px 25px;
            /* Larger padding for a bigger button */
            background: #4a4a4a !important;
            /* Dark gray background to match Grok theme */
            color: #ffffff !important;
            /* White text */
            border: none !important;
            /* Remove default border */
            border-radius: 30px !important;
            /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            /* Subtle shadow for depth */
            transition: background 0.2s ease, transform 0.1s ease;
            /* Smooth transitions */
        }

        .chat-toggle-btn:hover {
            background: #5a5a5a !important;
            /* Lighter gray on hover */
            transform: scale(1.05);
            /* Slight scale-up effect on hover */
        }

        .chat-toggle-btn i {
            margin-right: 8px;
            /* Space between icon and text */
        }

        /* Chat Bubble */
        #chat-bubble {
            width: 400px;
            /* Fixed width for the chat window */
            height: 600px;
            /* Fixed height for the chat window */
            background: #2a2a2a;
            /* Dark background to match Grok theme */
            border-radius: 15px;
            /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            /* Floating effect */
            position: absolute;
            bottom: 80px;
            /* Position above the button */
            right: 0;
            overflow: hidden;
            /* Ensure iframe fits within rounded corners */
        }

        /* Hide chat bubble when d-none is applied */
        #chat-bubble.d-none {
            display: none !important;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            #chat-bubble {
                width: 90vw;
                /* Full width on smaller screens */
                height: 80vh;
                /* Taller on mobile */
                bottom: 70px;
                /* Adjust position */
            }

            .chat-toggle-btn {
                font-size: 1em;
                /* Slightly smaller font on mobile */
                padding: 12px 20px;
                /* Adjust padding */
            }
        }
    </style>
</body>

</html>
<style>
    * {
        font-family: "Oswald", sans-serif;
        font-optical-sizing: auto;
        font-weight: <weight>;
        font-style: normal;
    }

    /* --- Navbar --- */
    .navbar {
        background-color: #ffffff !important;
        /* Xanh dương đậm */
        padding: 12px 0;
    }

    .navbar-brand {
        font-size: 20px;
        font-weight: 600;
        color: rgb(0, 0, 0) !important;
    }

    .navbar-nav {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* Màu chữ và hiệu ứng hover */
    .nav-link {
        color: rgb(0, 0, 0) !important;
        font-weight: 500;
        transition: color 0.3s ease-in-out;
        padding: 0 !important;
    }

    .nav-link:hover {
        color: #636260 !important;
    }

    /* Icon menu trên mobile */
    .navbar-toggler {
        border: none;
        outline: none;
    }

    .navbar-toggler-icon {
        background-color: white;
        border-radius: 5px;
    }

    /* --- Dropdown Menu --- */
    .dropdown-menu {
        border: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 0;
    }

    .dropdown-item:hover {
        background-color: #000000 !important;
        color: white !important;
    }

    /* --- Tìm kiếm --- */
    .navbar .search-form {
        display: flex;
        align-items: center;
        border-radius: 25px;
        overflow: hidden;
        background: white;
        border: 1px solid #000000;
        transition: all 0.3s ease-in-out;
        margin: 0;
    }

    .navbar .search-form input {
        border: none;
        outline: none;
        width: 500px;
    }

    .navbar .search-form:focus-within {
        border-color: #000000;
        box-shadow: 0px 0px 5px rgb(0, 0, 0);
    }

    /* Icon tìm kiếm */
    .navbar .search-icon {
        background: #ffffff;
        padding: 8px 12px;
        color: rgb(0, 0, 0);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .navbar .search-icon:hover {
        background: #ffffff;
    }

    /* --- Nút tìm kiếm --- */
    .navbar .btn-search {
        background-color: #ffffff !important;
        border: none;
        font-weight: 500;
        color: white;
        border-radius: 20px;
        transition: all 0.3s ease-in-out;
    }

    .navbar .btn-search:hover {
        background-color: #ffffff !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar .search-form input {
            width: 180px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
