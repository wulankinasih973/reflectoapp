<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil jurnal user yang login
        $journals = JournalEntry::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        $totalJournals = $journals->count();
        $avgMood = round($journals->avg(fn($e) => floatval($e->skor_mood)) ?? 0, 2);
        $avgAnxiety = round($journals->avg(fn($e) => floatval($e->skor_kecemasan)) ?? 0, 2);
        $avgStress = round($journals->avg(fn($e) => floatval($e->skor_stres)) ?? 0, 2);

        return view('dashboard', compact(
            'totalJournals', 'avgMood', 'avgAnxiety', 'avgStress', 'journals'
        ));
    }
}
