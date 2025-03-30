@extends('layouts.navbar_user')
@section('content')
    {{-- Error 404 --}}
    <div class="container">
        <h1>ERROR 404</h1>
        <p>Page not found</p>
        <a class="return" href="/">Return to Home</a>
    </div>
    <style>
        body {
            text-align: center;
        }

        .container {
            margin-top: 100px;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .return {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border: 1px solid #000;
        }

        .return:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
@endsection
