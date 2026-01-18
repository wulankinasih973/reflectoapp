@extends('layouts.app')

@section('content')
<div class="w-full flex justify-center">
    <div class="w-full max-w-5xl px-4 pt-6 pb-8">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-semibold text-gray-700">
                Permintaan User
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Daftar permintaan/keluhan dari user.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($requests->isEmpty())
                <p class="text-gray-500 text-center">Belum ada permintaan dari user.</p>
            @else
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-100 border-b text-left">
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Nama User</th>
                            <th class="px-3 py-2">Email</th>
                            <th class="px-3 py-2">Pesan</th>
                            <th class="px-3 py-2 text-center">Tanggal</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $index => $r)
                            <tr class="border-b @if($r->status === 'rejected') bg-gray-200 @endif">
                                <td class="px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2">{{ $r->user->name ?? 'User dihapus' }}</td>
                                <td class="px-3 py-2">{{ $r->user->email ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $r->pesan }}</td>
                                <td class="px-3 py-2 text-center">{{ $r->created_at->format('d M Y H:i') }}</td>
                                <td class="px-3 py-2 text-center flex items-center justify-center space-x-2">
                                    @if($r->status === 'pending')
                                        <form method="POST" action="{{ route('admin.account-request.approve', $r->id) }}">
                                            @csrf
                                            <button type="submit" class="text-green-600 text-xl leading-none hover:text-green-800">✔</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.account-request.reject', $r->id) }}">
                                            @csrf
                                            <button type="submit" class="text-red-600 text-xl leading-none hover:text-red-800">✖</button>
                                        </form>
                                    @elseif($r->status === 'approved')
                                        <span class="text-green-600 text-xl">✔</span>
                                    @elseif($r->status === 'rejected')
                                        <span class="text-red-600 text-xl">✖</span>

                                        {{-- tombol hapus --}}
                                    {{--<form method="POST"
                                        action="{{ route('admin.account-request.destroy', $r->id) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus request ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-gray-600 text-xl hover:text-red-700">
                                            🗑
                                        </button>
                                    </form> --}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
@endsection
