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

class AdminController extends Controller
{
    // ================================
    // DASHBOARD ADMIN
    // ================================
    public function index(Request $request)
    {
        $journals = JournalEntry::all();
        $totalUsers = User::where('role_id', '!=', 1)->count();
        $totalJournals = $journals->count();

        $avgMood = round(
            JournalEntry::pluck('skor_mood')
                ->map(fn ($v) => is_numeric($v) ? (int) $v : null)
                ->filter()
                ->avg(),
            2
        );

        $search = $request->input('search');
        $usersWithJournal = User::whereHas('journalEntries')
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                }
            })
            ->withCount('journalEntries')
            ->get();

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
            'path'    => request()->path(),
        ]);

        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        Log::info('ADMIN UPDATE PROFILE HIT', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $user->role->name,
            'method'  => request()->method(),
            'path'    => request()->path(),
            'url'     => request()->fullUrl(),
        ]);

        $request->validate([
            'name'     => 'required|string|max:255',
            'bio'      => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name'     => $request->name,
            'bio'      => $request->bio,
            'location' => $request->location,
        ]);

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
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        $user->update([
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
        $totalUsers = User::where('role_id', '!=', 1)->count();
        $totalJournals = $journals->count();
        $avgMood = round(floatval($journals->avg('skor_mood') ?? 0), 2);
        $avgAnxiety = round(floatval($journals->avg('skor_kecemasan') ?? 0), 2);
        $avgStress = round(floatval($journals->avg('skor_stres') ?? 0), 2);

        $path = storage_path('app/laporan.csv');
        $handle = fopen($path, 'w+');

        fputcsv($handle, ['Statistik', 'Nilai']);
        fputcsv($handle, ['Total Pengguna', $totalUsers]);
        fputcsv($handle, ['Total Jurnal', $totalJournals]);
        fputcsv($handle, ['Rata-Rata Mood', $avgMood]);
        fputcsv($handle, ['Rata-Rata Kecemasan', $avgAnxiety]);
        fputcsv($handle, ['Rata-Rata Stres', $avgStress]);

        fclose($handle);

        return response()->download($path, 'laporan.csv')
                         ->deleteFileAfterSend(true);
    }

    // ================================
    // EXPORT PDF
    // ================================
    public function exportPdf()
    {
        $journals = JournalEntry::all();
        $totalUsers = User::where('role_id', '!=', 1)->count();
        $totalJournals = $journals->count();
        $avgMood = round(floatval($journals->avg('skor_mood') ?? 0), 2);
        $avgAnxiety = round(floatval($journals->avg('skor_kecemasan') ?? 0), 2);
        $avgStress = round(floatval($journals->avg('skor_stres') ?? 0), 2);

        $html = "
            <h2>Laporan Statistik Jurnal Reflecto</h2>
            <p>Tanggal Cetak: " . now()->format('d M Y H:i') . "</p>
            <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
                <tr><th>Statistik</th><th>Nilai</th></tr>
                <tr><td>Total Pengguna</td><td>{$totalUsers}</td></tr>
                <tr><td>Total Jurnal</td><td>{$totalJournals}</td></tr>
                <tr><td>Rata-Rata Mood</td><td>{$avgMood}</td></tr>
                <tr><td>Rata-Rata Kecemasan</td><td>{$avgAnxiety}</td></tr>
                <tr><td>Rata-Rata Stres</td><td>{$avgStress}</td></tr>
            </table>
        ";

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('laporan.pdf');
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
        $request = AccountRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        return back()->with('success', 'Permintaan berhasil disetujui.');
    }

    public function rejectAccountRequest($id)
    {
        $request = AccountRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }
}
