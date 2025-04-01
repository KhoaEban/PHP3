<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-purple-600 to-blue-500">
    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Quên mật khẩu</h2>

        @if (session('status'))
            <div class="p-3 mb-3 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Địa chỉ email</label>
                <input id="email" type="email" name="email" required 
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                Gửi liên kết đặt lại mật khẩu
            </button>
        </form>
    </div>
</body>

</html>
