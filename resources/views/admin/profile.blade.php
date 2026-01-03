@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded shadow text-center mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-700 text-center">Profil Admin</h2>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
