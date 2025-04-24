<!DOCTYPE HTML>
<html>

<head>
    <title>Classy Register Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet" type="text/css" media="all" />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
    <!--header start here-->
    <div class="header">
        <div class="header-main">
            <h1>Đăng ký</h1>
            <div class="header-bottom">
                <div class="header-right w3agile">
                    <div class="header-left-bottom agileinfo">
                        @if (session('success'))
                            <div class="notification success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="notification error">{{ session('error') }}</div>
                        @endif

                        <!-- Form đăng ký -->
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="input-container">
                                <img src="{{ asset('images/m.png') }}" alt="User Icon" class="input-icon">
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Họ và tên"
                                    autocomplete="off" >
                            </div>
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <div class="input-container">
                                <i class="fa fa-envelope input-icon-email"></i>
                                <input type="email" name="email" placeholder="Email" autocomplete="off" >
                            </div>                            
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <div class="input-container">
                                <img src="{{ asset('images/l.png') }}" alt="Password Icon" class="input-icon">
                                <input type="password" name="password" placeholder="Mật khẩu" autocomplete="off"
                                    >
                            </div>
                            @error('password')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <div class="input-container">
                                <img src="{{ asset('images/l.png') }}" alt="Confirm Password Icon" class="input-icon">
                                <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu"
                                    autocomplete="off" >
                            </div>

                            <input type="submit" value="Đăng ký">
                            <div class="sign-up-text">
                                <h6>Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></h6>
                            </div>
                        </form>

                        <div class="header-left-top">
                            <div class="sign-up">
                                <h2>Hoặc</h2>
                            </div>
                        </div>

                        <div class="header-social wthree">
                            <a href="#" class="face">
                                <h5>Facebook</h5>
                            </a>
                            <a href="{{ url('/auth/google') }}" class="goog">
                                <h5>Google</h5>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
