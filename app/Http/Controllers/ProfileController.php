<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountRequest;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = auth()->user();
        $user->refresh();

        return view('profile.edit', [
            'user' => $user,
            'activeTab' => session('activeTab', 'profile')
        ]);
    }

    public function update(Request $request)
    {
	$user = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'bio'      => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name'     => $request->name,
            'bio'      => $request->bio,
            'location' => $request->location,
        ]);

        return redirect()
            ->route('profile.edit', ['tab' => 'profile'])
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('activeTab', 'profile');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak cocok.'])
                ->with('activeTab', 'password');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')
        ->with('success', 'Password berhasil diperbarui. Silakan login kembali.');
    }

    public function accountRequestForm()
    {
        return view('profile.account-request');
    }

    public function submitAccountRequest(Request $request)
    {
        $request->validate([
            'pesan' => 'required|min:10',
        ]);

        AccountRequest::create([
            'user_id' => auth()->id(),
            'pesan' => $request->pesan,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Permintaan berhasil dikirim ke admin.');
    }
}
