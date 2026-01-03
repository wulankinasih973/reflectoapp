@extends('layouts.app')

@section('content')
<div class="w-full flex justify-center">
    <div class="w-full max-w-4xl mx-auto px-4 py-6">

        <!-- Header + Slogan + Quote -->
        <div class="text-center mb-8">
            <h1 class="font-title text-5xl font-bold tracking-wide text-gray-800">
                Reflecto
            </h1>
            <p class="mt-2 font-body text-lg italic text-gray-600">
                "Menyelami perasaan, memahami diri"
            </p>

            <p class="text-xl font-semibold text-gray-700 mt-4">
                Selamat Datang, {{ auth()->user()->name }} 👋
            </p>

            <div class="mt-4 bg-gradient-to-r from-[#d5ebe5] to-[#f1f8f7] shadow-md rounded-xl p-4">
                <p class="text-gray-600 italic font-medium">
                    {!! $quote ?? 'Setiap emosi adalah pesan berharga yang ingin didengar.' !!}
                </p>
            </div>
        </div>

        <!-- Tombol User (Isi Jurnal + Logout) -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-10">
            <a href="{{ route('journal.create') }}"
               class="w-48 px-5 py-3 bg-[#2a504f] text-white rounded-lg shadow hover:bg-[#1f3d3c] transition text-sm font-medium text-center">
                + Isi Jurnal
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-48">
                @csrf
                <button type="submit"
                        class="w-full px-5 py-3 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition text-sm font-medium text-center">
                    Logout
                </button>
            </form>
        </div>

        <!-- Grafik Mood Trend -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h3 class="text-xl font-semibold mb-4 text-gray-700 text-center">Mood Trend</h3>
            <canvas id="moodChart" class="w-full h-56"></canvas>
        </div>

        <!-- Riwayat Jurnal -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-xl font-semibold mb-8 text-gray-700 text-center">Riwayat Jurnal</h3>

            @if($journals->isEmpty())
                <p class="text-gray-500 text-center">Belum ada entri jurnal.</p>
            @else
                <ul class="space-y-5">
                    @foreach($journals as $entry)
                        <li class="p-5 border rounded-xl shadow-sm bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-700">
                                        {{ $entry->created_at->format('d M Y') }}
                                    </p>

                                    <p class="text-gray-600 mt-2">
                                        {{ $entry->isi_jurnal }}
                                    </p>
                                </div>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('journal.destroy', $entry->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus jurnal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 text-sm bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('moodChart').getContext('2d');

    const moodChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($journals->pluck('created_at')->map->format('d M')) !!},
            datasets: [{
                label: 'Mood',
                data: {!! json_encode($journals->pluck('mood')) !!},
                borderColor: '#2a504f',
                backgroundColor: 'rgba(42, 80, 79, 0.2)',
                tension: 0.3,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#2a504f'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, suggestedMax: 10 }
            }
        }
    });
</script>
@endsection
