<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

  public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah user aktif
            if ($user->is_active === 0) {
                Auth::logout();
                return back()->with('error', 'Akun telah dihapus oleh admin.');
            }

            $request->session()->regenerate();

            if ($user->role && $user->role->name === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil.');
            }

            return redirect()->route('user.dashboard')->with('success', 'Login berhasil.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
