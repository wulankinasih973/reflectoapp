<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AccountRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ================================
    // DASHBOARD ADMIN
    // ================================
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'latest');
        $search = $request->input('search');

        /**
         * SUBQUERY:
         * Ambil jurnal TERLAMA & TERBARU per user
         */
        $journalSub = JournalEntry::select(
                'user_id',
                DB::raw('MIN(created_at) as first_journal_at'),
                DB::raw('MAX(created_at) as last_journal_at')
            )
            ->groupBy('user_id');

        /**
         * QUERY UTAMA USER
         */
        $journalSub = JournalEntry::select(
        'user_id',
        DB::raw('COUNT(*) as journal_count'),
        DB::raw('MIN(created_at) as first_journal_at'),
        DB::raw('MAX(created_at) as last_journal_at')
            )
            ->groupBy('user_id');

        $usersWithJournal = User::joinSub($journalSub, 'journals', function ($join) {
                $join->on('journals.user_id', '=', 'users.id');
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%");
                });
            })
            ->when(
                $filter === 'oldest',
                fn ($q) => $q->orderBy('journals.first_journal_at', 'asc'),
                fn ($q) => $q->orderBy('journals.last_journal_at', 'desc')
            )
            ->select([
                'users.*',
                'journals.journal_count',
                'journals.first_journal_at',
                'journals.last_journal_at',
            ])
            ->get();

        // ================================
        // STATISTIK
        // ================================
        $journals = JournalEntry::all();

        $totalUsers    = User::where('role_id', '!=', 1)->count();
        $totalJournals = $journals->count();

        $avgMood = round(
            $journals->pluck('skor_mood')
                ->map(fn ($v) => is_numeric($v) ? (int) $v : null)
                ->filter()
                ->avg(),
            2
        );

        // ================================
        // ACCOUNT REQUEST
        // ================================
        $accountRequests = AccountRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('dashboard', compact(
            'journals',
            'totalUsers',
            'totalJournals',
            'avgMood',
            'usersWithJournal',
            'search',
            'accountRequests'
        ));
    }

    // ================================
    // PROFIL ADMIN
    // ================================
    public function profile()
    {
        $user = auth()->user();

        Log::info('ADMIN PROFILE PAGE OPENED', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $user->role->name,
        ]);

        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'bio'      => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update($request->only('name', 'bio', 'location'));

        return redirect()->route('admin.profile')->with([
            'success'   => 'Profil berhasil diperbarui.',
            'activeTab' => 'profile',
        ]);
    }

    // ================================
    // UPDATE PASSWORD ADMIN
    // ================================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.profile')->with([
            'success'   => 'Password berhasil diperbarui.',
            'activeTab' => 'password',
        ]);
    }

    // ================================
    // HAPUS USER
    // ================================
    public function deleteUser(Request $request, $id)
    {
        $request->validate([
            'confirm_email'    => 'required|email',
            'confirm_password' => 'required',
        ]);

        $admin = Auth::user();

        if (
            $request->confirm_email !== $admin->email ||
            !Hash::check($request->confirm_password, $admin->password)
        ) {
            return back()->with('error', 'Email atau password admin salah.');
        }

        $user = User::findOrFail($id);
        JournalEntry::where('user_id', $user->id)->delete();
        $user->delete();

        return back()->with('success', 'Akun user berhasil dihapus secara permanen.');
    }

    // ================================
    // EXPORT CSV
    // ================================
    public function exportCsv()
    {
        $journals = JournalEntry::all();

        $path = storage_path('app/laporan.csv');
        $handle = fopen($path, 'w+');

        fputcsv($handle, ['Statistik', 'Nilai']);
        fputcsv($handle, ['Total Pengguna', User::where('role_id', '!=', 1)->count()]);
        fputcsv($handle, ['Total Jurnal', $journals->count()]);
        fputcsv($handle, ['Rata-Rata Mood', round($journals->avg('skor_mood') ?? 0, 2)]);
        fputcsv($handle, ['Rata-Rata Kecemasan', round($journals->avg('skor_kecemasan') ?? 0, 2)]);
        fputcsv($handle, ['Rata-Rata Stres', round($journals->avg('skor_stres') ?? 0, 2)]);

        fclose($handle);

        return response()->download($path, 'laporan.csv')->deleteFileAfterSend(true);
    }

    // ================================
    // EXPORT PDF
    // ================================
    public function exportPdf()
    {
        $journals = JournalEntry::all();

        $html = "
            <h2>Laporan Statistik Jurnal Reflecto</h2>
            <p>Tanggal Cetak: " . now()->format('d M Y H:i') . "</p>
        ";

        return Pdf::loadHTML($html)->download('laporan.pdf');
    }

    // ================================
    // ACCOUNT REQUESTS
    // ================================
    public function accountRequests()
    {
        $requests = AccountRequest::with('user')->latest()->get();
        return view('admin.account-requests', compact('requests'));
    }

    public function approveAccountRequest($id)
    {
        AccountRequest::findOrFail($id)->update(['status' => 'approved']);
        return back()->with('success', 'Permintaan berhasil disetujui.');
    }

    public function rejectAccountRequest($id)
    {
        AccountRequest::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Permintaan berhasil ditolak.');
    }
}
