<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountRequestController;

/*
|--------------------------------------------------------------------------
| Root '/'
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role->name === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Redirect setelah login
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    $user = auth()->user();
    return $user->role->name === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('dashboard');
})->middleware(['auth'])->name('home');

/*
|--------------------------------------------------------------------------
| USER Dashboard (role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'checkrole:user'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // CRUD Jurnal User
    Route::get('/journal/create', [JournalController::class, 'create'])
        ->name('journal.create');

    Route::post('/journal/store', [JournalController::class, 'store'])
        ->name('journal.store');

    Route::delete('/journal/{id}', [JournalController::class, 'destroy'])
        ->name('journal.destroy');

    // Profile User
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');

    /*
    |--------------------------------------------------------------------------
    | Hubungi Admin (Pengajuan Penghapusan Akun)
    |--------------------------------------------------------------------------
    */
    Route::get('/account/request', [AccountRequestController::class, 'create'])
        ->name('account.request.form');

    Route::post('/account/request', [AccountRequestController::class, 'store'])
        ->name('account.request.store');
});

/*
|--------------------------------------------------------------------------
| ADMIN Dashboard (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'checkrole:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // Click jurnal → forbidden
    Route::get('/admin/journal/{entry}', [AdminController::class, 'viewJournal'])
        ->name('admin.journal.view');

    // EXPORT
    Route::get('/admin/export/csv', [AdminController::class, 'exportCsv'])
        ->name('admin.export.csv');

    Route::get('/admin/export/pdf', [AdminController::class, 'exportPdf'])
        ->name('admin.export.pdf');

    /*
    |--------------------------------------------------------------------------
    | ADMIN Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/profile', [AdminController::class, 'profile'])
        ->name('admin.profile');

    Route::patch('/admin/profile', [AdminController::class, 'updateProfile'])
        ->name('admin.profile.update');

    Route::put('/admin/profile/password', [AdminController::class, 'updatePassword'])
        ->name('admin.profile.updatePassword');

    /*
    |--------------------------------------------------------------------------
    | Manajemen User
    |--------------------------------------------------------------------------
    */
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])
        ->name('admin.users.delete');

    Route::get('/admin/users/{id}/journal', [AdminController::class, 'viewJournal'])
        ->name('admin.users.journal');

    /*
    |--------------------------------------------------------------------------
    | Permintaan Penghapusan Akun (ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/account-requests', [AdminController::class, 'accountRequests'])
        ->name('admin.account-requests');

    Route::post('/admin/account-request/{id}/approve', [AdminController::class, 'approveAccountRequest'])
        ->name('admin.account-request.approve');

    Route::post('/admin/account-request/{id}/reject', [AdminController::class, 'rejectAccountRequest'])
        ->name('admin.account-request.reject');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
