<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountRequest;

class AccountRequestController extends Controller
{
    // ================================
    // FORM PENGAJUAN (USER)
    // ================================
    public function create()
    {
        return view('account-request.form');
    }

    // ================================
    // SIMPAN PERMINTAAN (USER)
    // ================================
   public function store(Request $request)
    {
    $request->validate([
        'message' => 'required|string|min:10',
    ]);

    AccountRequest::create([
        'user_id' => auth()->id(),
        'pesan'   => $request->message,
        'status'  => 'pending',
    ]);

    return redirect()
        ->route('dashboard')
        ->with('success', 'Permintaan berhasil dikirim ke admin.')
        ->with('request_sent', 'Permintaan berhasil dikirim ke admin.');
    }
}
