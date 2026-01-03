@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">

    {{-- SUCCESS POPUP --}}
    @if(session('success'))
        <div id="success-popup"
             style="opacity:1; transition:opacity .5s;"
             class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @php
        $user->refresh();
    @endphp

    <div class="bg-white shadow rounded-lg p-6">

        {{-- Tabs --}}
        <div class="flex border-b mb-6">
            <button id="tab-profile" class="px-4 py-2 border-b-2 font-semibold">Informasi Anda</button>
            <button id="tab-password" class="px-4 py-2 border-b-2 font-semibold">Ubah Password</button>
            <button id="tab-preview" class="px-4 py-2 border-b-2 font-semibold">Profil Anda</button>
        </div>

        {{-- PROFILE TAB --}}
        <div id="profile-tab-content">
            <form method="POST"
                  action="{{ auth()->user()->role->name === 'admin'
                        ? route('admin.profile.update')
                        : route('profile.update') }}"
                  class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name"
                           value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bio</label>
                    <input type="text" name="bio"
                           value="{{ old('bio', $user->bio) }}"
                           class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="location"
                           value="{{ old('location', $user->location) }}"
                           class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mt-6">
                    Simpan
                </button>
            </form>
        </div>

        {{-- PASSWORD TAB --}}
        <div id="password-tab-content" class="hidden">
            <form method="POST"
                  action="{{ auth()->user()->role->name === 'admin'
                        ? route('admin.profile.updatePassword')
                        : route('profile.updatePassword') }}"
                  class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                    <input type="password" name="current_password"
                           class="mt-1 block w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" name="password"
                           class="mt-1 block w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Masukkan Lagi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="mt-1 block w-full border rounded px-3 py-2" required>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mt-6">
                    Simpan Password
                </button>
            </form>
        </div>

        {{-- PREVIEW TAB --}}
        <div id="preview-tab-content" class="hidden">
            <div class="flex flex-col items-center">
                <div class="mb-4"></div>

                <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                <p class="mt-2 text-gray-700">{{ $user->bio ?: '—' }}</p>
                <p class="mt-1 text-gray-500">{{ $user->location ?: '—' }}</p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabs = {
        profile: document.getElementById('profile-tab-content'),
        password: document.getElementById('password-tab-content'),
        preview: document.getElementById('preview-tab-content')
    };

    const buttons = {
        profile: document.getElementById('tab-profile'),
        password: document.getElementById('tab-password'),
        preview: document.getElementById('tab-preview')
    };

    function showTab(tabName) {
        Object.keys(tabs).forEach(tab => {
            tabs[tab].classList.add('hidden');
            buttons[tab].classList.remove('border-green-600');
        });

        tabs[tabName].classList.remove('hidden');
        buttons[tabName].classList.add('border-green-600');
    }

    showTab("{{ session('activeTab', 'profile') }}");

    buttons.profile.onclick = () => showTab('profile');
    buttons.password.onclick = () => showTab('password');
    buttons.preview.onclick = () => showTab('preview');

    const popup = document.getElementById('success-popup');
    if (popup) {
        setTimeout(() => popup.style.opacity = 0, 3000);
        setTimeout(() => popup.remove(), 3500);
    }
});
</script>
@endsection
