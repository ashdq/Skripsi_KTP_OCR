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
        $totalUnduh = $warga
            ? \App\Models\Surat::where('warga_id', $warga->id)
                ->where('status', 'selesai')
                ->whereNotNull('file_surat')
                ->count()
            : 0;
        return view('warga.home', compact('surats', 'latestSurat', 'totalUnduh'));
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
        $surats = \App\Models\Surat::with('ocr')->where('status', '!=', 'selesai')->latest()->take(5)->get();
        $totalBulanIni = \App\Models\Surat::whereMonth('tanggal_pengajuan', now()->month)
                                          ->whereYear('tanggal_pengajuan', now()->year)
                                          ->count();
        $menungguTtd = \App\Models\Surat::where('status', 'menunggu')->count();
        
        // --- Demografi Analisis ---
        // Get unique OCR data based on NIK
        $penduduk = \App\Models\Ocr::select('tanggal_lahir', 'jenis_kelamin', 'agama')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('ocrs')->groupBy('nik');
            })->get();

        $totalPenduduk = $penduduk->count();

        $usiaData = [
            '0-5' => 0, '6-12' => 0, '13-18' => 0, '19-35' => 0, '36-50' => 0, '51-65' => 0, '>65' => 0
        ];
        $totalUsia = 0;
        $usiaTermuda = null;
        $usiaTertua = null;
        $countUsiaValid = 0;

        foreach ($penduduk as $p) {
            if ($p->tanggal_lahir) {
                try {
                    $tanggalLahir = \Carbon\Carbon::parse($p->tanggal_lahir);
                    $umur = $tanggalLahir->age;
                    $totalUsia += $umur;
                    $countUsiaValid++;

                    if ($usiaTermuda === null || $umur < $usiaTermuda) $usiaTermuda = $umur;
                    if ($usiaTertua === null || $umur > $usiaTertua) $usiaTertua = $umur;

                    if ($umur <= 5) $usiaData['0-5']++;
                    elseif ($umur <= 12) $usiaData['6-12']++;
                    elseif ($umur <= 18) $usiaData['13-18']++;
                    elseif ($umur <= 35) $usiaData['19-35']++;
                    elseif ($umur <= 50) $usiaData['36-50']++;
                    elseif ($umur <= 65) $usiaData['51-65']++;
                    else $usiaData['>65']++;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        $rataRataUsia = $countUsiaValid > 0 ? round($totalUsia / $countUsiaValid, 1) : 0;
        $usiaTermuda = $usiaTermuda ?? 0;
        $usiaTertua = $usiaTertua ?? 0;

        $genderData = [
            'Laki-Laki' => $penduduk->filter(function($item) { return strtolower(trim((string)$item->jenis_kelamin)) == 'laki-laki'; })->count(),
            'Perempuan' => $penduduk->filter(function($item) { return strtolower(trim((string)$item->jenis_kelamin)) == 'perempuan'; })->count(),
        ];

        $agamaData = [
            'Islam' => $penduduk->filter(function($item) { return strtolower(trim((string)$item->agama)) == 'islam'; })->count(),
            'Kristen' => $penduduk->filter(function($item) { return strtolower(trim((string)$item->agama)) == 'kristen'; })->count(),
            'Katolik' => $penduduk->filter(function($item) { return strtolower(trim((string)$item->agama)) == 'katolik'; })->count(),
            'Hindu' => $penduduk->filter(function($item) { return strtolower(trim((string)$item->agama)) == 'hindu'; })->count(),
            'Budha' => $penduduk->filter(function($item) { $val = strtolower(trim((string)$item->agama)); return $val == 'budha' || $val == 'buddha'; })->count(),
            'Kong Hu Cu' => $penduduk->filter(function($item) { $val = strtolower(trim((string)$item->agama)); return $val == 'konghucu' || $val == 'kong hu cu'; })->count(),
        ];

        $demografi = [
            'totalPenduduk' => number_format($totalPenduduk, 0, ',', '.'),
            'usiaData' => array_values($usiaData),
            'rataRataUsia' => $rataRataUsia,
            'usiaTermuda' => $usiaTermuda,
            'usiaTertua' => $usiaTertua,
            'genderData' => [$genderData['Laki-Laki'], $genderData['Perempuan']],
            'agamaData' => array_values($agamaData),
        ];
        
        return view('petugas.home', compact('surats', 'totalBulanIni', 'menungguTtd', 'demografi'));
    })->name('petugas.home');

    Route::get('/petugas/daftar', function () {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'lurah') {
            return redirect()->route('petugas.home')->with('error', 'Hanya admin yang dapat melakukan pembuatan surat!');
        }

        $surats = \App\Models\Surat::with('ocr')->latest()->get();
        $total   = $surats->count();
        $proses  = $surats->whereIn('status', ['menunggu', 'diproses'])->count();
        $selesai = $surats->where('status', 'selesai')->count();
        
        return view('petugas.daftar-pengajuan', compact('surats', 'total', 'proses', 'selesai'));
    })->name('petugas.daftar');

    Route::patch('/petugas/pengajuan/{surat}/proses', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'lurah') {
            return back()->with('error', 'Hanya admin yang dapat melakukan pembuatan surat!');
        }
        if ($user && $user->petugas) {
            $surat->update([
                'status' => 'diproses',
                'petugas_id' => $user->petugas->id
            ]);
            return back()->with('success', 'Status pengajuan berhasil diubah menjadi diproses.');
        }
        return back()->with('error', 'Akses ditolak.');
    })->name('petugas.pengajuan.proses');

    Route::patch('/petugas/pengajuan/{surat}/tolak', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'lurah') {
            return back()->with('error', 'Hanya admin yang dapat melakukan pembuatan surat!');
        }
        if ($user && $user->petugas) {
            $surat->update([
                'status' => 'ditolak',
                'petugas_id' => $user->petugas->id
            ]);
            return back()->with('success', 'Pengajuan surat berhasil ditolak.');
        }
        return back()->with('error', 'Akses ditolak.');
    })->name('petugas.pengajuan.tolak');

    Route::get('/petugas/pengajuan/{surat}/detail', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas) {
            $surat->load('ocr', 'warga');
            return view('petugas.detail-pengajuan', compact('surat'));
        }
        return redirect()->route('petugas.daftar')->with('error', 'Akses ditolak.');
    })->name('petugas.pengajuan.detail');

    // Halaman daftar surat siap tanda tangan (status diproses) & sudah selesai
    Route::get('/petugas/tanda-tangan', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'admin') {
            return redirect()->route('petugas.home')->with('error', 'Hanya lurah yang dapat melakukan tanda tangan.');
        }

        $filter = $request->query('filter', 'menunggu');

        $query = \App\Models\Surat::with('ocr')->whereIn('status', ['diproses', 'selesai'])->latest();
        
        $countMenunggu = \App\Models\Surat::where('status', 'diproses')->count();
        $countSelesai  = \App\Models\Surat::where('status', 'selesai')->count();

        if ($filter === 'menunggu') {
            $query->where('status', 'diproses');
        } elseif ($filter === 'selesai') {
            $query->where('status', 'selesai');
        }

        $surats = $query->get();
        return view('petugas.tanda-tangan', compact('surats', 'filter', 'countMenunggu', 'countSelesai'));
    })->name('petugas.tanda-tangan');

    // Halaman Preview & Edit Surat (dari daftar pengajuan, status menunggu atau diproses)
    Route::get('/petugas/pengajuan/{surat}/generate', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'lurah') {
            return redirect()->route('petugas.home')->with('error', 'Hanya admin yang dapat melakukan pembuatan surat!');
        }

        if ($user && $user->petugas && in_array($surat->status, ['menunggu', 'diproses'])) {
            $surat->load('ocr');
            return view('petugas.generate-surat', compact('surat'));
        }
        return redirect()->route('petugas.daftar')->with('error', 'Surat tidak valid.');
    })->name('petugas.pengajuan.generate');

    // Route untuk Simpan Surat (menyimpan HTML dan mengubah status ke diproses)
    Route::post('/petugas/pengajuan/{surat}/simpan', function (\Illuminate\Http\Request $request, \App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'lurah') {
            return back()->with('error', 'Hanya admin yang dapat melakukan pembuatan surat!');
        }
        if ($user && $user->petugas && in_array($surat->status, ['menunggu', 'diproses'])) {
            $htmlContent = $request->input('html_content');
            
            $updateData = [
                'html_content' => $htmlContent
            ];

            if ($surat->status === 'menunggu') {
                $updateData['status'] = 'diproses';
                $updateData['petugas_id'] = $user->petugas->id;
            }

            $surat->update($updateData);

            return redirect()->route('petugas.pengajuan.ttd.page', $surat->id)->with('success', 'Surat berhasil disimpan dan siap ditandatangani.');
        }
        return back()->with('error', 'Akses ditolak.');
    })->name('petugas.pengajuan.simpan');

    // Halaman tanda tangan detail per surat (dari halaman tanda-tangan)
    Route::get('/petugas/pengajuan/{surat}/tanda-tangan', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'admin') {
            return redirect()->route('petugas.home')->with('error', 'Hanya lurah yang dapat melakukan tanda tangan.');
        }

        if ($user && $user->petugas && $surat->status === 'diproses') {
            $surat->load('ocr', 'petugas', 'warga');
            return view('petugas.tanda-tangan-surat', compact('surat'));
        }
        return redirect()->route('petugas.tanda-tangan')->with('error', 'Surat tidak valid.');
    })->name('petugas.pengajuan.ttd.page');

    // Proses tanda tangan & generate PDF final
    Route::post('/petugas/pengajuan/{surat}/tandatangan', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $user->petugas->role === 'admin') {
            return back()->with('error', 'Hanya lurah yang dapat melakukan tanda tangan.');
        }
        if ($user && $user->petugas && $surat->status === 'diproses') {
            $surat->load('ocr');

            // Ambil data signature dari request
            $signatureData = request('signature_data'); // base64 PNG
            $lokasi        = request('lokasi', 'Talun');
            $nama_petugas  = request('nama_petugas', $surat->petugas->nama ?? 'Nama Kepala Lurah');
            $nip_petugas   = request('nip_petugas', '-');

            // Generate PDF dari template
            $viewName = $surat->html_content ? 'surat.custom-template' : 'surat.template';
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, [
                'surat'         => $surat,
                'signature_data'=> $signatureData,
                'lokasi_ttd'    => $lokasi,
                'nama_petugas'  => $nama_petugas,
                'nip_petugas'   => $nip_petugas,
            ])->setPaper('a4', 'portrait');

            $fileName = 'surat_' . $surat->id . '_' . time() . '.pdf';
            $dir      = storage_path('app/public/surat');
            $fullPath = $dir . '/' . $fileName;

            if (!is_dir($dir)) { mkdir($dir, 0755, true); }
            file_put_contents($fullPath, $pdf->output());

            $surat->update([
                'status'     => 'selesai',
                'file_surat' => $fileName,
            ]);

            return redirect()->route('petugas.tanda-tangan')
                ->with('success', 'Surat berhasil ditandatangani dan di-generate. Warga dapat mengunduhnya.');
        }
        return back()->with('error', 'Akses ditolak atau status surat tidak valid.');
    })->name('petugas.pengajuan.tandatangan');

    // Petugas lihat PDF surat yang sudah selesai
    Route::get('/petugas/pengajuan/{surat}/pdf', function (\App\Models\Surat $surat) {
        $user = Auth::user();
        if ($user && $user->petugas && $surat->status === 'selesai' && $surat->file_surat) {
            $filePath = storage_path('app/public/surat/' . $surat->file_surat);
            if (file_exists($filePath)) {
                return response()->file($filePath);
            }
        }
        return back()->with('error', 'File surat tidak ditemukan.');
    })->name('petugas.pengajuan.pdf');

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

        return response()->file($fullPath);
    })->name('warga.surat.lihat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
