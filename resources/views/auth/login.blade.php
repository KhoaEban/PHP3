<!DOCTYPE HTML>
<html>

<head>
    <title>Classy Login Form</title>
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
            <h1>Đăng nhập</h1>
            <div class="header-bottom">
                <div class="header-right w3agile">
                    <div class="header-left-bottom agileinfo">
                        @if (session('success'))
                            <div class="notification success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="notification error">{{ session('error') }}</div>
                        @endif

                        <!-- Form đăng nhập -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="input-container">
                                <i class="fa fa-envelope input-icon-email"></i>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                    autocomplete="off" required>
                            </div>
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <div class="input-container">
                                <img src="{{ asset('images/l.png') }}" alt="Password Icon" class="input-icon">
                                <input type="password" name="password" placeholder="Mật khẩu" autocomplete="off"
                                    required>
                            </div>
                            @error('password')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <div class="remember">
                                <span class="checkbox1">
                                    <label class="checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <i></i> Nhớ mật khẩu
                                    </label>
                                </span>
                                <div class="forgot">
                                    <h6><a href="{{ route('send-mail') }}">Quên mật khẩu?</a></h6>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <input type="submit" value="Đăng nhập">
                            <div class="sign-up-text">
                                <h6>Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></h6>
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
