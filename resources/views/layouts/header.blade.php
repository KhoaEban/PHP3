<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('title', 'Laravel') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d70c32c211.js" crossorigin="anonymous"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--  -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="{{ asset('css/styles_admin.css') }}" rel="stylesheet" />

    <style>
        /* Làm cho tất cả input, select, textarea vuông */
        input,
        select,
        textarea,
        button {
            border-radius: 0 !important;
            /* Bỏ bo góc */
        }

        /* Điều chỉnh nút bấm cũng vuông */
        .btn {
            border-radius: 0 !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
            /* Hiệu ứng focus */
        }
    </style>
</head>
