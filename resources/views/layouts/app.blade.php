<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Reflecto') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-title {
            font-family: 'Playfair Display', serif;
        }
        .font-body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="font-body antialiased min-h-screen bg-gradient-to-br from-white via-[#E9FFF8] to-[#C9FFE9]">

    <!-- 🌿 NAVBAR -->
    <nav class="fixed top-0 left-0 w-full backdrop-blur shadow-sm z-50
                bg-gradient-to-b from-[#c9ffe9] to-[#aef5de]">
        <div class="max-w-6xl mx-auto px-6 py-3 flex justify-between items-center">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 text-2xl font-title text-[#1F2937] font-bold">
                <span class="text-xl">🌿</span>
                Reflecto
            </a>

            <!-- Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Dashboard -->
                    <a href="{{ auth()->user()->role->name === 'admin' ? '/admin/dashboard' : '/dashboard' }}"
                       class="text-sm text-gray-700 hover:text-[#1F2937] font-semibold">
                       Dashboard
                    </a>

                    <!-- Dropdown Profil -->
                    <div class="relative">
                        <button id="profileDropdownBtn"
                                class="text-sm text-gray-700 hover:text-[#1F2937] font-semibold flex items-center gap-1">
                            {{ auth()->user()->name }}
                            <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdownMenu"
                             class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg py-2 hidden z-50">

                            <!-- Profil -->
                            <a href="{{ auth()->user()->role->name === 'admin' ? '/admin/profile' : '/profile' }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil
                            </a>

                            <!-- Permintaan (Admin) -->
                            @if(auth()->user()->role->name === 'admin')
                                <a href="{{ route('admin.account-requests') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Permintaan
                                </a>
                            @endif

                            <div class="border-t my-1"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    @if(!request()->is('register'))
                        <a href="/login" class="text-sm text-gray-700 hover:text-[#0C6B4D] transition">
                            Login
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Wrapper konten -->
    <div class="pt-20 w-full">
        @yield('content')
    </div>

    @yield('scripts')

    <!-- Dropdown Script -->
    <script>
        const btn = document.getElementById('profileDropdownBtn');
        const menu = document.getElementById('profileDropdownMenu');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    </script>

    <!-- 🌿 FOOTER -->
    <footer class="mt-16 border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-6 py-6 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} <span class="font-medium text-gray-600">Reflecto</span>
            </p>
        </div>
    </footer>

</body>
</html>
