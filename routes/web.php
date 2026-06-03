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
        $user = Auth::user();
        $warga = $user->warga;
        $surats = $warga
            ? \App\Models\Surat::where('warga_id', $warga->id)
                ->where('status', 'selesai')
                ->whereNotNull('file_surat')
                ->orderBy('updated_at', 'desc')
                ->get()
            : collect();
        return view('warga.unduh', compact('surats'));
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

    Route::get('/petugas/pengajuan/{surat}/detail', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas) {
            $surat->load('ocr', 'warga');
            return view('petugas.detail-pengajuan', compact('surat'));
        }
        return redirect()->route('petugas.daftar')->with('error', 'Akses ditolak.');
    })->name('petugas.pengajuan.detail');

    Route::get('/petugas/tanda-tangan', function () {
        $surats = \App\Models\Surat::with('ocr')->where('status', 'diproses')->latest()->get();
        return view('petugas.tanda-tangan', compact('surats'));
    })->name('petugas.tanda-tangan');

    // Halaman Preview & Edit Surat per pengajuan
    Route::get('/petugas/pengajuan/{surat}/generate', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $surat->status === 'diproses') {
            $surat->load('ocr');
            return view('petugas.generate-surat', compact('surat'));
        }
        return redirect()->route('petugas.tanda-tangan')->with('error', 'Surat tidak valid atau bukan dalam status diproses.');
    })->name('petugas.pengajuan.generate');

    // Proses tanda tangan & generate PDF
    Route::post('/petugas/pengajuan/{surat}/tandatangan', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $surat->status === 'diproses') {
            $surat->load('ocr');

            // Generate PDF dari template
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat.template', ['surat' => $surat])
                ->setPaper('a4', 'portrait');

            $fileName    = 'surat_' . $surat->id . '_' . time() . '.pdf';
            $dir         = storage_path('app/public/surat');
            $fullPath    = $dir . '/' . $fileName;

            if (!is_dir($dir)) { mkdir($dir, 0755, true); }
            file_put_contents($fullPath, $pdf->output());

            $surat->update([
                'status'     => 'selesai',
                'file_surat' => $fileName,
            ]);

            return redirect()->route('petugas.tanda-tangan')
                ->with('success', 'Surat berhasil ditandatangani dan di-generate. Warga sekarang dapat mengunduh surat tersebut.');
        }
        return back()->with('error', 'Akses ditolak atau status surat tidak valid.');
    })->name('petugas.pengajuan.tandatangan');

    // Warga unduh surat yang sudah selesai
    Route::get('/warga/surat/{surat}/unduh', function (\App\Models\Surat $surat) {
        $user = Auth::user();

        // Validasi akses: harus login sebagai warga, milik sendiri, status selesai, ada file
        if (!$user || !$user->warga) {
            return redirect()->route('warga.unduh')->with('error', 'Akses ditolak.');
        }
        if ($surat->warga_id !== $user->warga->id) {
            return redirect()->route('warga.unduh')->with('error', 'Anda tidak memiliki akses ke surat ini.');
        }
        if ($surat->status !== 'selesai' || !$surat->file_surat) {
            return redirect()->route('warga.unduh')->with('error', 'Surat belum selesai diproses.');
        }

        $fullPath = storage_path('app/public/surat/' . $surat->file_surat);

        if (!file_exists($fullPath)) {
            // File hilang — coba regenerate otomatis
            try {
                $surat->load('ocr');
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat.template', ['surat' => $surat])
                    ->setPaper('a4', 'portrait');
                $dir = storage_path('app/public/surat');
                if (!is_dir($dir)) { mkdir($dir, 0755, true); }
                file_put_contents($fullPath, $pdf->output());
            } catch (\Exception $e) {
                return redirect()->route('warga.unduh')->with('error', 'File surat tidak ditemukan. Hubungi petugas.');
            }
        }

        $downloadName = 'Surat_' . str_replace(' ', '_', $surat->jenis_surat) . '.pdf';
        return response()->download($fullPath, $downloadName, ['Content-Type' => 'application/pdf']);
    })->name('warga.surat.unduh');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
