@extends('layouts.navbar_user')
@section('content')
    {{-- Error 404 --}}
    <div class="container">
        <h1>ERROR 404</h1>
        <p>Page not found</p>
        <a href="/">Return to Home</a>
    </div>
    <style>
        body {
            text-align: center;
            background-color: #000000;
            color: #ffffff;
        }

        .container {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 auto;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
@endsection
