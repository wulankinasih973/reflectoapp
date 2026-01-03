@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4 text-center">
        Pengajuan Penghapusan Akun
    </h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error validasi --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('account.request.store') }}">
        @csrf

        <textarea name="message"
                  rows="5"
                  required
                  class="w-full border rounded p-3"
                  placeholder="Tuliskan alasan penghapusan akun (min 10 karakter)">{{ old('message') }}</textarea>

        <button class="mt-4 w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
            Kirim Permintaan
        </button>
    </form>
</div>
@endsection
