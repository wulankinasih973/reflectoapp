<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JournalEntry;

class JournalController extends Controller
{
    // Form input
    public function create()
    {
        return view('journal.journal_form');
    }

    // Simpan data
    public function store(Request $request)
    {
       $validated = $request->validate([
            'q1' => 'required|numeric|min:1|max:5',
            'q2' => 'required|numeric|min:1|max:5',
            'q3' => 'required|numeric|min:1|max:5',
            'q4' => 'required|numeric|min:1|max:5',
            'q5' => 'required|numeric|min:1|max:5',
            'q6' => 'required|numeric|min:1|max:5',
            'isi_jurnal' => 'required|string|min:20',
        ], [
            'isi_jurnal.min' => 'Catatan Bebas harus berisi minimal 20 karakter',
            'isi_jurnal.required' => 'Catatan Bebas wajib diisi',
        ]);

        // Hitung skor rata-rata per kategori
        $skor_kecemasan = ($validated['q1'] + $validated['q2']) / 2;
        $skor_stres = ($validated['q3'] + $validated['q4']) / 2;
        $skor_mood = ($validated['q5'] + $validated['q6']) / 2;

        // Simpan ke database
        JournalEntry::create([
            'user_id' => auth()->id(),
            'isi_jurnal' => $validated['isi_jurnal'],
            'skor_mood' => $skor_mood,
            'skor_kecemasan' => $skor_kecemasan,
            'skor_stres' => $skor_stres,
        ]);

        // Feedback berdasarkan skor
        $pesan = 'Data berhasil disimpan. ';
        if ($skor_kecemasan > 3.5 || $skor_stres > 3.5) {
            $pesan .= '⚠️ Anda tampak sedang mengalami tekanan. Cobalah istirahat, meditasi ringan, atau journaling tambahan.';
        } elseif ($skor_mood < 2.5) {
            $pesan .= '😔 Mood Anda sedang menurun. Coba lakukan aktivitas menyenangkan atau hubungi teman dekat.';
        } else {
            $pesan .= '😊 Kondisi Anda terlihat stabil — terus pertahankan kebiasaan baik!';
        }

        // Redirect ke dashboard user
        return redirect()->route('dashboard')->with('success', $pesan);
    }

    // Hapus jurnal
    public function destroy($id)
    {
    $journal = JournalEntry::findOrFail($id);

    // disini hanya pemilik jurnal yang bisa menghapus
    if ($journal->user_id !== auth()->id()) {
        abort(403);
    }

    $journal->delete();

    return redirect()->route('dashboard')->with('status', 'Jurnal berhasil dihapus.');
    }

    // List jurnal user
    public function index()
    {
        $entries = JournalEntry::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('journal.index', compact('entries'));
    }
}
