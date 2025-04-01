<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

    <!-- Main Stylesheet File -->
    <link href="/css/style.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">

</head>

<body>
    <div class="layoutss"></div>
    <div class="contaner">
        <div class="sidebar">
            <div class="logo">
                <div class="icon">
                    <img src="{{ $user->avatar ?? 'https://placehold.co/96x96' }}" alt="User profile picture">
                </div>
            </div>
            <h1>Cài đặt tài khoản</h1>
            <p>Quản lý cài đặt tài khoản của bạn như thông tin cá nhân, cài đặt bảo mật, quản lý thông báo, v.v.</p>
            <button class="primary">
                <span><i class="fas fa-user"></i> Thông tin cá nhân</span>
            </button>
            <button class="secondary">
                <span><i class="fas fa-shield-alt"></i> Mật khẩu và bảo mật</span>
            </button>
        </div>
    </div>

    @yield('content')

</body>

</html>

<style>
    * {
        font-family: "Oswald", sans-serif;
        font-optical-sizing: auto;
        font-weight: <weight>;
        font-style: normal;
    }

    body {
        display: flex;
        justify-content: center;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .layoutss {
        position: fixed;
        z-index: -1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .layoutss::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -2;
        background: #fff;
    }

    .layoutss::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -1;
        opacity: .08;
        background-image: radial-gradient(#ffffff40, #fff0 40%), radial-gradient(hsl(44, 100%, 66%) 30%, hsl(338, 68%, 65%), hsla(338, 68%, 65%, .4) 41%, transparent 52%), radial-gradient(hsl(272, 100%, 60%) 37%, transparent 46%), linear-gradient(155deg, transparent 65%, hsl(142, 70%, 49%) 95%), linear-gradient(45deg, #0065e0, #0f8bff);
        background-size: 200% 200%, 285% 500%, 285% 500%, cover, cover;
        background-position: bottom left, 109% 68%, 109% 68%, center, center;
    }

    .container {
        background: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        display: flex;
        width: 100%;
        max-width: 1200px;
    }

    .sidebar {
        width: 450px;
        padding: 32px;
        border-right: 1px solid #e0e0e0;
        height: 100vh;
    }

    .sidebar .logo {
        display: flex;
        align-items: center;
        margin-bottom: 32px;
    }

    .sidebar .logo .icon {
        color: white;
        border-radius: 50%;
        padding: 8px;
        font-size: 24px;
        font-weight: bold;
    }

    .sidebar .logo .icon img {
        border-radius: 50%;
    }


    .sidebar h1 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .sidebar p {
        color: #757575;
        margin-bottom: 15px;
    }

    .sidebar button {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        margin-bottom: 16px;
    }

    .sidebar button.primary {
        background: #424242;
        color: white;
    }

    .sidebar button.secondary {
        background: white;
        color: #424242;
        border: 1px solid #e0e0e0;
    }

    .content {
        width: 66.67%;
        /* background: #e0f7fa; */
        padding: 32px;
        border-radius: 0 8px 8px 0;
    }

    .content h2 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .content p {
        color: #757575;
        margin-bottom: 24px;
    }

    .content h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .content .info {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 16px;
    }

    .content .info .item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .content .info .item:last-child {
        border-bottom: none;
    }

    .content .info .item p {
        margin: 0;
    }

    .content .info .item .avatar {
        width: 500px;
        height: auto;
    }

    .content .info .item .avatar img {
        border-radius: 50%;
        margin-top: 8px;
    }
</style>
