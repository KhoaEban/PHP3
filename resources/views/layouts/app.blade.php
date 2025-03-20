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
</head>

<body>
    {{-- <div id="app">
        @php

        @endphp

        @auth
            @php

            @endphp

            @if (Auth::user()->role === 'admin')
                @include('layouts.navbar_admin')
            @else
                @include('layouts.navbar_user')
            @endif
        @else
            @include('layouts.navbar_user') <!-- Mặc định cho khách -->
        @endauth


        <div class="container">
            @yield('content')
        </div>

        <footer class="bg-dark text-white text-center p-3" style="position: fixed; bottom: 0; width: 100%;">
            <p>PHP3 - Laravel</p>
        </footer>
    </div> --}}
</body>
