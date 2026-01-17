<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JournalEntry;
use App\Models\User;
use App\Models\AccountRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ====================================================
        // USER DASHBOARD
        // ====================================================
        if ($user->role->name === 'user') {

            // Cek apakah user aktif
            if ($user->is_active === 0) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Akun telah dihapus oleh admin.');
            }

            $journals = JournalEntry::where('user_id', $user->id)
                ->orderBy('created_at', 'ASC')
                ->get();

            // Chart
            $chartLabels = $journals->pluck('created_at')
                ->map(fn ($date) => $date->format('d M'))
                ->toArray();

            $chartData = $journals->pluck('skor_mood')
                ->map(fn ($v) => (float) $v)
                ->toArray();
                
            // $chartData = array_fill(0, $journals->count(), (float) $avgMood);

            // Quotes
            $allQuotes = [
                "Setiap emosi adalah pesan berharga yang ingin didengar.",
                "Tidak apa-apa untuk beristirahat sejenak.",
                "Kamu sudah berusaha yang terbaik hari ini.",
                "Hidup adalah perjalanan, bukan perlombaan.",
                "Senyum kecil hari ini bisa jadi awal yang baik.",
            ];

            $quote = $allQuotes[array_rand($allQuotes)];

            return view('dashboard', compact(
                'journals',
                'chartLabels',
                'chartData',
                'quote',
                'allQuotes'
            ));
        }

        // ====================================================
        // ADMIN DASHBOARD
        // ====================================================
        $totalUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'user');
        })->count();

        $totalJournals = JournalEntry::count();

        $avgMood = round(JournalEntry::avg('skor_mood'), 2);

        $usersWithJournal = User::withCount('journalEntries')
            ->whereHas('journalEntries')
            ->get();

        $accountRequests = AccountRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'totalJournals',
            'avgMood',
            'usersWithJournal',
            'accountRequests'
        ));
    }
}
