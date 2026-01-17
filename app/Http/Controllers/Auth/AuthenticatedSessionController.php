<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi
        $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        // Ambil user yang sedang login
        $user = auth()->user();

        // Cek apakah user aktif, untuk role user
        if ($user->role->name !== 'admin' && isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun ini sudah dinonaktifkan oleh admin.',
            ]);
        }

        // Redirect otomatis sesuai role
        if ($user->role->name === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Hapus semua sesi setelah logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
