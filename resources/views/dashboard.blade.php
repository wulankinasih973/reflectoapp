@extends('layouts.app')

@section('content')
<div class="w-full flex justify-center">
    <div class="w-full max-w-5xl px-4 pt-6 pb-8">

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div id="flash-message" class="fixed inset-0 flex items-start justify-center z-50 mt-10">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg animate-slide-in max-w-lg text-center">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div id="flash-message" class="fixed inset-0 flex items-start justify-center z-50 mt-10">
                <div class="bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg animate-slide-in max-w-lg text-center">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- SLOGAN REFLECTO --}}
        <div class="mb-6 text-center mt-2">
            <h1 class="text-4xl md:text-5xl font-title font-bold text-[#1F2937] tracking-wide">
                Reflecto
            </h1>
            <p class="text-gray-600 text-sm md:text-base mt-1">
                Find balance inside your mind
            </p>
        </div>

        {{-- JUDUL --}}
        <div class="mb-6 text-center">
            <p class="mt-4 text-3xl font-semibold text-gray-700">
                Selamat Datang, 
                {{ auth()->user()->name }}
                @if(auth()->user()->role->name === 'admin')
                    <span class="text-sm text-gray-500"></span>
                @endif
                👋
            </p>
        </div>

        {{-- USER DASHBOARD --}}
        @if(auth()->check() && auth()->user()->role->name === 'user')

            {{-- Tombol Tambah Jurnal --}}
            <div class="flex items-center justify-end mb-6 space-x-4 z-10">
                <a href="{{ route('journal.create') }}"
                    class="px-5 py-3 bg-[#2a504f] text-white rounded-lg shadow
                            hover:bg-[#1b62a8] hover:-translate-y-[1px]
                            transition-all duration-200
                            focus:outline-none focus:ring-2 focus:ring-[#2a504f]/40
                            text-sm font-semibold">
                        + Isi Jurnal
                    </a>
            </div>

            {{-- Quote --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <p id="quoteBox" class="text-center text-gray-700 text-lg italic opacity-0 transition-all duration-700">
                    "{{ $quote ?? ($allQuotes[0] ?? 'Selamat datang di Reflecto!') }}"
                </p>
            </div>

            {{-- Mood Chart --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-12">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 text-center">Mood Trend</h3>
                @if(!empty($chartLabels) && !empty($chartData))
                    <canvas id="moodChart" class="w-full h-56"></canvas>
                @else
                    <p class="text-center text-gray-500">Belum ada data mood untuk ditampilkan.</p>
                @endif
            </div>

            {{-- Riwayat Jurnal --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-6 text-gray-700 text-center">Riwayat Jurnal</h3>
                @if($journals->isEmpty())
                    <p class="text-gray-500 text-center">Belum ada entri jurnal.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($journals as $entry)
                            <li class="p-4 border rounded-lg shadow-sm bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-700">{{ $entry->created_at->format('d M Y') }}</p>
                                        <p class="text-gray-600 mt-1">{{ $entry->isi_jurnal }}</p>
                                    </div>

                                    {{-- Hapus Jurnal --}}
                                    <form action="{{ route('journal.destroy', $entry->id) }}" 
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus jurnal ini?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 underline underline-offset-2 text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- HUBUNGI ADMIN (SOFT CARD) --}}
            <div class="mt-10 flex justify-center">
                <div class="bg-white/70 backdrop-blur
                            border border-gray-200
                            rounded-xl px-6 py-4
                            max-w-xl w-full
                            shadow-sm
                            transition
                            hover:shadow-md">

                    <p class="text-sm text-gray-600 text-center leading-relaxed">
                        Jika kamu mengalami kendala atau merasa perlu bantuan,
                        jangan ragu untuk menghubungi admin.
                    </p>

                    <div class="mt-3 text-center">
                        <span id="contactAdminText"
                            class="inline-flex items-center gap-2
                                    text-blue-600 text-sm font-medium
                                    cursor-pointer
                                    hover:text-blue-700
                                    hover:underline
                                    transition">
                            💬 Hubungi Admin
                        </span>
                    </div>
                </div>
            </div>

            {{-- Modal Hubungi Admin --}}
            <div id="contactAdminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Hubungi Admin</h2>
                    <p class="mb-4 text-sm text-gray-600">
                        Masukkan pesan untuk admin.
                    </p>
                    <form method="POST" action="{{ route('account.request.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="block text-sm font-medium text-gray-700">Pesan</label>
                            <textarea name="message" id="message" rows="4" required
                                      class="w-full border rounded px-3 py-2"
                                      placeholder="Tuliskan pesan Anda..."></textarea>
                        </div>
                        <div class="flex justify-end gap-3 mt-4">
                            <span id="cancelContactAdmin" 
                                  class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 cursor-pointer">
                                Batal
                            </span>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ADMIN DASHBOARD --}}
        @if(auth()->check() && auth()->user()->role->name === 'admin')
            {{-- Admin Stats, Export, List User (tidak diubah) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <x-admin-stat title="Total User" value="{{ $totalUsers }}" icon="fa-solid fa-users" />
                <x-admin-stat title="Total Jurnal" value="{{ $totalJournals }}" icon="fa-solid fa-book" />
                <x-admin-stat title="Rata-rata Mood" value="{{ $avgMood }}" icon="fa-solid fa-face-smile" />
            </div>

           <div class="bg-white rounded-lg shadow p-5 mb-8">
            <h2 class="text-xl font-semibold mb-4">Export Data</h2>
            <div class="flex gap-4">
                <a href="{{ route('admin.export.csv') }}"
                    class="px-4 py-2 bg-white text-green-700 border border-green-600 rounded
                            hover:bg-green-50 hover:-translate-y-[1px]
                            transition-all duration-200
                            focus:outline-none focus:ring-2 focus:ring-green-300">
                        Export CSV
                    </a>

                    <a href="{{ route('admin.export.pdf') }}"
                    class="px-4 py-2 bg-white text-blue-700 border border-blue-600 rounded
                            hover:bg-blue-50 hover:-translate-y-[1px]
                            transition-all duration-200
                            focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Export PDF
                    </a>
            </div>
        </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Daftar User Yang Telah Mengisi Jurnal</h2>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                    <div class="flex gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..." class="border rounded px-3 py-2 w-full">
                        <select name="filter" class="border rounded px-3 py-2">
                            <option value="">Filter...</option>
                            <option value="newest" {{ request('filter')=='newest'?'selected':'' }}>Terbaru</option>
                            <option value="oldest" {{ request('filter')=='oldest'?'selected':'' }}>Terlama</option>
                        </select>
                        <button class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">Cari</button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead>
                            <tr class="bg-gray-100 border-b text-left">
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Nama</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Jumlah Jurnal</th>
                                <th class="px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usersWithJournal as $index => $u)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2">{{ $u->name }}</td>
                                    <td class="px-3 py-2">{{ $u->email }}</td>
                                    <td class="px-3 py-2">{{ $u->journal_entries_count }}</td>
                                    <td class="px-3 py-2 text-sm">
                                        <span onclick="alert('Forbidden: Admin tidak boleh melihat jurnal user.')"
                                              class="inline-block mr-4 text-[#0C6B4D] hover:text-[#074d36] underline underline-offset-2 cursor-pointer">
                                            Lihat Detail
                                        </span>
                                        <span onclick="openDeleteModal({{ $u->id }})"
                                              class="inline-block text-red-600 hover:text-red-800 underline underline-offset-2 cursor-pointer">
                                            Hapus
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Tidak ada user yang mengisi jurnal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif {{-- <-- Tutup ADMIN DASHBOARD --}}

    </div>
</div>

{{-- MODAL DELETE USER --}}
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Konfirmasi Hapus User</h2>
        <p class="mb-4 text-sm text-gray-600">Masukkan email & password admin untuk menghapus user ini.</p>
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="mb-3">
                <label class="text-sm text-gray-700">Email Admin</label>
                <input type="email" name="confirm_email" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="text-sm text-gray-700">Password Admin</label>
                <input type="password" name="confirm_password" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(auth()->check() && auth()->user()->role->name === 'user')
// Chart.js
const ctx = document.getElementById('moodChart');
if (ctx) {
    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Mood',
                data: {!! json_encode($chartData ?? []) !!},
                borderColor: '#2a504f',
                backgroundColor: 'rgba(42, 80, 79, 0.2)',
                borderWidth: 3,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#2a504f',
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
}

// Quote Slideshow
const quoteBox = document.getElementById('quoteBox');
if (quoteBox) {
    const quotes = {!! json_encode($allQuotes ?? ['Selamat datang di Reflecto!']) !!};
    let index = quotes.indexOf(`{{ $quote ?? ($allQuotes[0] ?? 'Selamat datang di Reflecto!') }}`);
    if(index === -1) index = 0;
    function showQuote() {
        quoteBox.style.opacity = 0;
        setTimeout(() => {
            index = (index + 1) % quotes.length;
            quoteBox.innerHTML = `"${quotes[index]}"`;
            quoteBox.style.opacity = 1;
        }, 700);
    }
    setTimeout(() => quoteBox.style.opacity = 1, 300);
    setInterval(showQuote, 4000);
}
@endif

// Flash message auto-hide
setTimeout(() => { 
    const f = document.getElementById('flash-message'); 
    if (f) f.remove(); 
}, 5000);

// Modal DELETE USER
function openDeleteModal(id) {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('deleteForm').action = `/admin/users/${id}`;
}
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Modal Hubungi Admin
const contactText = document.getElementById('contactAdminText');
const contactModal = document.getElementById('contactAdminModal');
const cancelBtn = document.getElementById('cancelContactAdmin');

if(contactText && contactModal && cancelBtn){
    contactText.addEventListener('click', () => {
        contactModal.classList.remove('hidden');
        contactModal.classList.add('flex');
    });

    cancelBtn.addEventListener('click', () => {
        contactModal.classList.add('hidden');
        contactModal.classList.remove('flex');
    });

    contactModal.addEventListener('click', (e) => {
        if(e.target === contactModal){
            contactModal.classList.add('hidden');
            contactModal.classList.remove('flex');
        }
    });
}

// Slide-in animation
const flash = document.getElementById('flash-message');
if(flash) flash.classList.add('animate-slide-in');
</script>
@endsection

<style>
@keyframes slide-in {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.5s ease-out; }
</style>
