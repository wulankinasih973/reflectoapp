@extends('layouts.auth')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f9fbfa 0%, #eef6f3 50%, #fef7f1 100%);
        font-family: 'Poppins', sans-serif;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(18px);
        border-radius: 20px;
        max-width: 330px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
        padding: 24px;
    }

    .auth-input {
        background: rgba(255,255,255,0.85);
        border-radius: 12px;
        border: 1px solid #d7e2df;
        transition: 0.25s ease-in-out;
    }

    .auth-input:focus {
        outline: none;
        border-color: #99d7c9;
        box-shadow: 0 0 0 3px rgba(153, 215, 201, 0.35);
    }

    .primary-btn {
        background-color: #59cab0;
        transition: 0.25s ease-in-out;
        height: 48px;
        font-size: 1rem;
    }

    .primary-btn:hover {
        background-color: #84c9bc;
    }

    .soft-link {
        color: #6ab9a5;
    }
    .soft-link:hover {
        color: #5aa897;
    }

    /* Password wrapper */
    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        color: #6ab9a5;
        display: flex;
        align-items: center;
    }

    .toggle-password:hover {
        color: #5aa897;
    }

    /* SUCCESS POPUP */
    .success-popup {
        position: fixed;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #59cab0;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        z-index: 9999;
        opacity: 1;
        transition: opacity 0.5s ease;
    }
</style>

{{-- POPUP SUCCESS (SETELAH GANTI PASSWORD / LOGOUT) --}}
@if(session('success'))
    <div id="success-popup" class="success-popup">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(() => {
            const popup = document.getElementById('success-popup');
            if (popup) {
                popup.style.opacity = '0';
                setTimeout(() => popup.remove(), 500);
            }
        }, 3000);
    </script>
@endif

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full auth-card mx-auto">

        <h1 class="text-center text-2xl font-semibold mb-6 text-gray-700 tracking-wide">
            Login ke Reflecto
        </h1>

        @if (session('status'))
            <div class="mb-3 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium text-sm">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 auth-input"
                       required autofocus>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium text-sm">Password</label>
                <div class="password-wrapper">
                    <input type="password"
                           name="password"
                           id="password"
                           class="w-full px-3 py-2 auth-input"
                           required>
                    <button type="button"
                            class="toggle-password"
                            data-target="password">
                        👁️
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full primary-btn text-white font-semibold rounded-xl shadow-md">
                Log In
            </button>
        </form>

        <div class="text-center mt-5 text-gray-700 text-xs">
            Belum punya akun?
            <a href="{{ route('register') }}" class="soft-link hover:underline font-semibold">
                Register
            </a>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            button.textContent = type === 'password' ? '👁️' : '🙈';
        });
    });
</script>
@endsection
