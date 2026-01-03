@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Riwayat Jurnal Saya</h2>

    @if($entries->isEmpty())
        <p>Belum ada catatan jurnal.</p>
        <a href="{{ route('journal.create') }}" class="btn btn-primary">Buat Jurnal Baru</a>
    @else
        <ul style="list-style:none; padding:0;">
            @foreach($entries as $entry)
                <li style="margin-bottom:1rem; border-bottom:1px solid #eee; padding-bottom:1rem;">
                    <strong>{{ $entry->created_at->format('d M Y') }}</strong><br>
                    Mood: {{ $entry->skor_mood }} | Kecemasan: {{ $entry->skor_kecemasan }} | Stres: {{ $entry->skor_stres }}<br>
                    <em>"{{ $entry->isi_jurnal }}"</em>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('journal.create') }}" class="btn btn-primary">Tambah Jurnal Baru</a>
    @endif
</div>
@endsection
