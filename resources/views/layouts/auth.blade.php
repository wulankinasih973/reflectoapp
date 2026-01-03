<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Reflecto') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-title { font-family: 'Playfair Display', serif; }
        .font-body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="font-body antialiased min-h-screen bg-gradient-to-br from-white via-[#E9FFF8] to-[#C9FFE9]">

    {{-- ❗️ TANPA HEADER --}}
    <div class="pt-10 w-full">
        @yield('content')
    </div>

    @yield('scripts')

</body>
</html>
