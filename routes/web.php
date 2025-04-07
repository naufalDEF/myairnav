<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Superadmin\UserManagementController;
use App\Http\Controllers\Superadmin\DocumentController as SuperadminDocumentController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect ke login jika user mengakses root "/"
Route::get('/', function () {
    return redirect('/login');
});

// ===================== RUTE LOGIN & LOGOUT ===================== //
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ===================== RUTE DASHBOARD BERDASARKAN ROLE ===================== //
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    })->name('dashboard');
});

// ===================== RUTE SUPERADMIN ===================== //
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    // Manajemen Users
    Route::resource('users', UserManagementController::class);

    // CRUD Dokumen (Superadmin)
    Route::resource('documents', SuperadminDocumentController::class);
    Route::post('/documents/bulk-download', [SuperadminDocumentController::class, 'bulkDownload'])->name('documents.bulkDownload');
    Route::post('/documents/bulk-delete', [SuperadminDocumentController::class, 'bulkDelete'])->name('documents.bulkDelete');

    // Submenu Dokumen (Teknik, Operasi, K3)
    Route::get('/documents/{category}', [SuperadminDocumentController::class, 'showCategory'])
        ->whereIn('category', ['teknik', 'operasi', 'k3'])
        ->name('documents.category');
});

// ===================== RUTE ADMIN ===================== //
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rute khusus untuk kategori dokumen sebelum Route Resource agar tidak tertimpa oleh metode `show`
    Route::get('/documents/{category}', [AdminDocumentController::class, 'showCategory'])
        ->whereIn('category', ['teknik', 'operasi', 'k3'])
        ->name('documents.category');

    // Manajemen Dokumen (Admin CRUD)
    Route::resource('documents', AdminDocumentController::class);
    Route::post('/documents/bulk-download', [AdminDocumentController::class, 'bulkDownload'])->name('documents.bulkDownload');
    Route::post('/documents/bulk-delete', [AdminDocumentController::class, 'bulkDelete'])->name('documents.bulkDelete');

});

// ===================== RUTE USER ===================== //
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    // Daftar Dokumen (Hanya Bisa Lihat dan Download)
    Route::get('/documents', [SuperadminDocumentController::class, 'userIndex'])->name('documents.index');

    // Submenu Dokumen (Hanya Bisa Lihat Berdasarkan Kategori)
    Route::get('/documents/{category}', [SuperadminDocumentController::class, 'showCategory'])
        ->whereIn('category', ['teknik', 'operasi', 'k3'])
        ->name('documents.category');
});

require __DIR__ . '/auth.php';
