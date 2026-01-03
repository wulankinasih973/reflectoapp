@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-xl rounded-xl p-8 mt-6">

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}"
        class="inline-flex items-center gap-2
                text-green-700 text-sm font-medium
                hover:text-green-800 hover:-translate-x-[2px]
                transition-all duration-200
                focus:outline-none focus:ring-2 focus:ring-green-200 rounded">
            <span class="text-lg">←</span>
            <span>Kembali</span>
        </a>
    </div>

    <h2 class="text-2xl font-semibold text-center mb-6 text-gray-700">
        🧠 Psikotest Mini — Mood Check
    </h2>

    <form method="POST" action="{{ route('journal.store') }}" class="space-y-6">
        @csrf

        <!-- Kecemasan -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">Kecemasan</h3>

            <div class="mb-4">
                <label class="block font-medium text-gray-600 mb-1">
                    Saya merasa cemas tanpa alasan yang jelas.
                </label>
                <select name="q1" required class="w-full border rounded-lg p-3">
                    <option value="">-- Pilih Jawaban --</option>
                    @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}">{{ $v }} - {{ ["Sangat Tidak Setuju","Tidak Setuju","Netral","Setuju","Sangat Setuju"][$v-1] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-600 mb-1">
                    Saya sulit merasa tenang meskipun situasi biasa saja.
                </label>
                <select name="q2" required class="w-full border rounded-lg p-3">
                    <option value="">-- Pilih Jawaban --</option>
                    @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}">{{ $v }} - {{ ["Sangat Tidak Setuju","Tidak Setuju","Netral","Setuju","Sangat Setuju"][$v-1] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Stres -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">Stres</h3>

            <div class="mb-4">
                <label class="block font-medium text-gray-600 mb-1">
                    Saya merasa kewalahan dengan tugas sehari-hari.
                </label>
                <select name="q3" required class="w-full border rounded-lg p-3">
                    <option value="">-- Pilih Jawaban --</option>
                    @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}">{{ $v }} - {{ ["Sangat Tidak Setuju","Tidak Setuju","Netral","Setuju","Sangat Setuju"][$v-1] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-600 mb-1">
                    Saya mudah tersinggung atau gelisah.
                </label>
                <select name="q4" required class="w-full border rounded-lg p-3">
                    <option value="">-- Pilih Jawaban --</option>
                    @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}">{{ $v }} - {{ ["Sangat Tidak Setuju","Tidak Setuju","Netral","Setuju","Sangat Setuju"][$v-1] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Mood -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">Mood</h3>

            <div class="mb-4">
                <label class="block font-medium text-gray-600 mb-1">
                    Saya merasa bahagia dan bersemangat hari ini.
                </label>
                <select name="q5" required class="w-full border rounded-lg p-3">
                    <option value="">-- Pilih Jawaban --</option>
                    @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}">{{ $v }} - {{ ["Sangat Tidak Setuju","Tidak Setuju","Netral","Setuju","Sangat Setuju"][$v-1] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-600 mb-1">
                    Saya mampu menikmati aktivitas sehari-hari.
                </label>
                <select name="q6" required class="w-full border rounded-lg p-3 mb-4">
                    <option value="">-- Pilih Jawaban --</option>
                    @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}">{{ $v }} - {{ ["Sangat Tidak Setuju","Tidak Setuju","Netral","Setuju","Sangat Setuju"][$v-1] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Catatan Bebas -->
        <div>
            <label class="block font-medium text-gray-700 mb-2">Catatan Bebas</label>
            <textarea name="isi_jurnal" rows="5"
                    class="w-full border rounded-lg p-3"
                    placeholder="Tuliskan perasaan Anda hari ini..."
                    required>{{ old('isi_jurnal') }}</textarea>

            {{-- Hint minimal 20 karakter --}}
            <p class="text-xs text-gray-500 mt-1">Minimal 20 karakter</p>

            {{-- Error di bawah textarea --}}
            @error('isi_jurnal')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full py-3 mt-4
                bg-[#2a7fff] text-white font-semibold
                rounded-lg
                shadow-sm
                hover:bg-[#1f6fe0] hover:-translate-y-[1px]
                transition-all duration-200
                focus:outline-none focus:ring-2 focus:ring-blue-300">
            Simpan Jurnal
        </button>

        {{-- Pop-up error --}}
        @if($errors->any())
        <div id="error-popup"
            style="opacity:1; transition:opacity .5s;"
            class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded shadow-lg z-50 font-semibold">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const popup = document.getElementById('error-popup');
                if (popup) {
                    setTimeout(() => popup.style.opacity = 0, 3000);
                    setTimeout(() => popup.remove(), 3500);
                }
            });
        </script>
    @endif
@endsection
