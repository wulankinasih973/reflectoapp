<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Reflecto') }} - Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-title { font-family: 'Playfair Display', serif; }
        .font-body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="font-body antialiased min-h-screen bg-gradient-to-br from-white via-[#E9FFF8] to-[#C9FFE9]">

    <!-- Konten utama -->
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-body font-bold text-center text-[#1F2937] mb-4">Reset Password</h1>
            <p class="text-sm text-gray-600 text-center mb-6">Masukkan email kamu untuk menerima link reset password.</p>

            @if (session('status'))
                <div class="mb-4 text-green-600 text-center">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input id="email" type="email" name="email" placeholder="Email" required autofocus
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C6B4D] text-sm mb-4">
                <button type="submit" class="w-full bg-[#0C6B4D] text-white py-2 rounded-lg hover:bg-[#0A553B] transition font-semibold">
                    Kirim Link Reset Password
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                <a href="/login" class="hover:text-[#0C6B4D] transition">Kembali ke Login</a>
            </div>
        </div>
    </div>

</body>
</html>
