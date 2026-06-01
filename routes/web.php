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
        $user = Auth::user();
        $warga = $user->warga;
        $surats = $warga ? \App\Models\Surat::where('warga_id', $warga->id)->orderBy('tanggal_pengajuan', 'desc')->get() : collect();
        $latestSurat = $surats->first();
        return view('warga.home', compact('surats', 'latestSurat'));
    })->name('warga.home');

    Route::get('/warga/unduh', function () {
        return view('warga.unduh');
    })->name('warga.unduh');

    Route::get('/warga/pengurusan', [DokumenController::class, 'pengurusan'])->name('warga.pengurusan');

    Route::post('/warga/pengurusan/submit', [DokumenController::class, 'upload'])->name('warga.pengurusan.submit');
    Route::post('/warga/pengurusan/simpan-identitas', [DokumenController::class, 'saveIdentitas'])->name('warga.pengurusan.simpan-identitas');
    Route::post('/warga/pengurusan/kirim', [DokumenController::class, 'kirimPengajuan'])->name('warga.pengurusan.kirim-pengajuan');

    Route::get('/warga/pengurusan/dokumen/{type}/preview', [DokumenController::class, 'previewDocument'])
        ->whereIn('type', ['ktp', 'kk'])
        ->name('warga.dokumen.preview');

    Route::get('/warga/pengurusan/dokumen/{type}/download', [DokumenController::class, 'downloadDocument'])
        ->whereIn('type', ['ktp', 'kk'])
        ->name('warga.dokumen.download');

    Route::get('/petugas/home', function () {
        $surats = \App\Models\Surat::with('ocr')->latest()->take(5)->get();
        $totalBulanIni = \App\Models\Surat::whereMonth('tanggal_pengajuan', now()->month)
                                          ->whereYear('tanggal_pengajuan', now()->year)
                                          ->count();
        $menungguTtd = \App\Models\Surat::where('status', 'menunggu')->count();
        
        return view('petugas.home', compact('surats', 'totalBulanIni', 'menungguTtd'));
    })->name('petugas.home');

    Route::get('/petugas/daftar', function () {
        $surats = \App\Models\Surat::with('ocr')->latest()->get();
        $total = $surats->count();
        $proses = $surats->where('status', 'diproses')->count();
        $selesai = $surats->where('status', 'selesai')->count();
        
        return view('petugas.daftar-pengajuan', compact('surats', 'total', 'proses', 'selesai'));
    })->name('petugas.daftar');

    Route::patch('/petugas/pengajuan/{surat}/proses', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas) {
            $surat->update([
                'status' => 'diproses',
                'petugas_id' => $user->petugas->id
            ]);
            return back()->with('success', 'Status pengajuan berhasil diubah menjadi diproses.');
        }
        return back()->with('error', 'Akses ditolak.');
    })->name('petugas.pengajuan.proses');

    Route::get('/petugas/tanda-tangan', function () {
        return view('petugas.tanda-tangan');
    })->name('petugas.tanda-tangan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
