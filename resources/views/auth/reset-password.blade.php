<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-white via-[#E9FFF8] to-[#C9FFE9]">
        
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-title font-bold text-center text-[#1F2937] mb-4">
                Reset Password
            </h1>
            <p class="text-sm text-gray-600 text-center mb-6">
                Masukkan password baru untuk akun kamu.
            </p>

            @if ($errors->any())
                <div class="mb-4 text-red-600 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <div class="mb-4">
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C6B4D] text-sm">
                </div>

                <div class="mb-4">
                    <label for="password" class="sr-only">Password Baru</label>
                    <input id="password" type="password" name="password" placeholder="Password Baru" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C6B4D] text-sm">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C6B4D] text-sm">
                </div>

                <button type="submit"
                        class="w-full bg-[#0C6B4D] text-white py-2 rounded-lg hover:bg-[#0A553B] transition font-semibold">
                    Reset Password
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                <a href="/login" class="hover:text-[#0C6B4D] transition">
                    Kembali ke Login
                </a>
            </div>
        </div>

    </div>
</x-guest-layout>
