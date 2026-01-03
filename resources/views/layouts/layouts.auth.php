<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reflecto')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    @yield('content')
</body>
</html>
