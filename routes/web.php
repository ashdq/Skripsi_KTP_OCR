<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DokumenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user?->role) {
        'petugas' => redirect()->route('petugas.home'),
        default => redirect()->route('warga.home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/warga/home', function () {
        return view('warga.home');
    })->name('warga.home');

    Route::get('/warga/unduh', function () {
        return view('warga.unduh');
    })->name('warga.unduh');

    Route::get('/warga/pengurusan', [DokumenController::class, 'pengurusan'])->name('warga.pengurusan');

    Route::post('/warga/pengurusan/submit', [DokumenController::class, 'upload'])->name('warga.pengurusan.submit');

    Route::get('/warga/pengurusan/dokumen/{type}/preview', [DokumenController::class, 'previewDocument'])
        ->whereIn('type', ['ktp', 'kk'])
        ->name('warga.dokumen.preview');

    Route::get('/warga/pengurusan/dokumen/{type}/download', [DokumenController::class, 'downloadDocument'])
        ->whereIn('type', ['ktp', 'kk'])
        ->name('warga.dokumen.download');

    Route::get('/petugas/home', function () {
        return view('petugas.home');
    })->name('petugas.home');

    Route::get('/petugas/daftar', function () {
        return view('petugas.daftar-pengajuan');
    })->name('petugas.daftar');

    Route::get('/petugas/tanda-tangan', function () {
        return view('petugas.tanda-tangan');
    })->name('petugas.tanda-tangan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
