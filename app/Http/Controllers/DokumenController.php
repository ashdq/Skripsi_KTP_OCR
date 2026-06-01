<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Ocr;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use App\Services\OcrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View as ViewResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DokumenController extends Controller
{
    public function pengurusan(): ViewResponse
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        $warga = $user->warga;
        $existingDocuments = [
            'ktp' => null,
            'kk' => null,
        ];
        $ocrData = null;

        if ($warga) {
            $existingDocuments = $this->getExistingDocuments($warga);
            $ocrData = Ocr::where('warga_id', $warga->id)->first();
        }

        return view('warga.pengurusan', [
            'existingKtp' => $existingDocuments['ktp'],
            'existingKk' => $existingDocuments['kk'],
            'warga' => $warga,
            'ocrData' => $ocrData,
        ]);
    }

    public function previewDocument(string $type): BinaryFileResponse
    {
        $file = $this->resolveDocumentFile($type);

        if (! $file) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $file['path']));
    }

    public function downloadDocument(string $type): BinaryFileResponse
    {
        $file = $this->resolveDocumentFile($type);

        if (! $file) {
            abort(404);
        }

        return response()->download(
            storage_path('app/public/' . $file['path']),
            $file['name']
        );
    }

    public function upload(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'jenis_surat' => ['required', 'string'],
            'ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'kk' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        $warga = $user->warga;

        if (! $warga) {
            $warga = Warga::create([
                'nama_warga' => filled($user->name) ? $user->name : ($user->email ?? 'warga'),
                'nomor_hp' => '-',
                'user_id' => $user->id,
            ]);
        }

        $folderKey = 'dokumen/warga-' . $warga->id;
        $hasNewKtp = $request->hasFile('ktp');
        $hasNewKk = $request->hasFile('kk');

        if ($hasNewKtp) {
            $this->replaceStoredFile($folderKey . '/ktp', 'ktp', $request->file('ktp')->extension(), $request->file('ktp'));
        }

        if ($hasNewKk) {
            $this->replaceStoredFile($folderKey . '/kk', 'kk', $request->file('kk')->extension(), $request->file('kk'));
        }

        $finalDocuments = $this->getExistingDocuments($warga);

        if (! $hasNewKtp && ! $finalDocuments['ktp']) {
            return back()->withErrors(['ktp' => 'File KTP wajib diupload minimal satu kali.']);
        }

        if (! $hasNewKk && ! $finalDocuments['kk']) {
            return back()->withErrors(['kk' => 'File KK wajib diupload minimal satu kali.']);
        }

        $dokumen = Dokumen::updateOrCreate(
            ['warga_id' => $warga->id],
            [
                'warga_id' => $warga->id,
            ]
        );

        // --- OCR Processing ---
        $ocrFields = [];
        try {
            $ktpAbsPath = null;
            $kkAbsPath = null;

            if ($finalDocuments['ktp']) {
                $ktpAbsPath = storage_path('app/public/' . $finalDocuments['ktp']['path']);
            }
            if ($finalDocuments['kk']) {
                $kkAbsPath = storage_path('app/public/' . $finalDocuments['kk']['path']);
            }

            if ($ktpAbsPath && $kkAbsPath && file_exists($ktpAbsPath) && file_exists($kkAbsPath)) {
                // --- OCR Processing ---
                // Do not save to DB here, just return the fields for frontend to autofill
                $ocrService = new OcrService();
                $ocrResult = $ocrService->processKtpAndKk($ktpAbsPath, $kkAbsPath);
                $ocrFields = $ocrResult['fields'] ?? [];
            } else {
                Log::warning('[OCR] File KTP/KK tidak ditemukan untuk OCR processing');
            }
        } catch (\Exception $e) {
            Log::error('[OCR] Error saat OCR processing: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Dokumen KTP dan KK berhasil diunggah.',
                'ktp' => $finalDocuments['ktp']['url'] ?? null,
                'kk' => $finalDocuments['kk']['url'] ?? null,
                'ocr_fields' => $ocrFields,
            ]);
        }

        return back()->with('success', 'Dokumen KTP dan KK berhasil diunggah.');
    }

    private function getExistingDocuments(Warga $warga): array
    {
        $ktpFile = $this->resolveDocumentFile('ktp', $warga);
        $kkFile = $this->resolveDocumentFile('kk', $warga);

        return [
            'ktp' => $ktpFile ? $this->decorateExistingFile($ktpFile, 'ktp', $warga) : null,
            'kk' => $kkFile ? $this->decorateExistingFile($kkFile, 'kk', $warga) : null,
        ];
    }

    private function decorateExistingFile(array $file, string $type, Warga $warga): array
    {
        $lastModified = Storage::disk('public')->lastModified($file['path']);
        return [
            'path' => $file['path'],
            'name' => $file['name'],
            'preview_url' => route('warga.dokumen.preview', ['type' => $type]) . '?t=' . $lastModified,
            'download_url' => route('warga.dokumen.download', ['type' => $type]) . '?t=' . $lastModified,
            'type' => $file['type'],
        ];
    }

    private function resolveDocumentFile(string $type, ?Warga $warga = null): ?array
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $warga) {
            abort_unless($user instanceof User, 403);
            $warga = $user->warga;
        }

        if (! $warga) {
            return null;
        }

        $folderKey = 'dokumen/warga-' . $warga->id . '/' . $type;
        $disk = Storage::disk('public');
        $files = $disk->files($folderKey);

        if (empty($files)) {
            return null;
        }

        $filePath = $files[0];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fileType = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) ? 'image' : 'pdf';

        return [
            'path' => $filePath,
            'name' => basename($filePath),
            'type' => $fileType,
        ];
    }

    private function replaceStoredFile(string $directory, string $baseName, string $extension, $file): void
    {
        $disk = Storage::disk('public');

        foreach ($disk->files($directory) as $existingFile) {
            $disk->delete($existingFile);
        }

        $disk->putFileAs($directory, $file, $baseName . '.' . $extension);
    }

    public function saveIdentitas(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['nullable', 'string'],
            'nik' => ['nullable', 'string'],
            'nomor_kk' => ['nullable', 'string'],
            'tempat_lahir' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'string'],
            'agama' => ['nullable', 'string'],
            'status_perkawinan' => ['nullable', 'string'],
            'pekerjaan' => ['nullable', 'string'],
            'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string'],
            'kelurahan' => ['nullable', 'string'],
            'kecamatan' => ['nullable', 'string'],
            'kota' => ['nullable', 'string'],
            'provinsi' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        $warga = $user->warga;
        if (!$warga) {
            return response()->json(['message' => 'Data warga tidak ditemukan'], 404);
        }

        $dokumen = Dokumen::where('warga_id', $warga->id)->first();
        if (!$dokumen) {
            return response()->json(['message' => 'Silakan upload dokumen terlebih dahulu'], 400);
        }

        Ocr::updateOrCreate(
            ['dokumen_id' => $dokumen->id],
            [
                'warga_id' => $warga->id,
                'dokumen_id' => $dokumen->id,
                'nik' => $validated['nik'] ?? null,
                'nama' => $validated['nama'] ?? null,
                'nomor_kk' => $validated['nomor_kk'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'rt_rw' => $validated['rt'] ?? null,
                'kelurahan' => $validated['kelurahan'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kota_kabupaten' => $validated['kota'] ?? null,
                'provinsi' => $validated['provinsi'] ?? null,
                'agama' => $validated['agama'] ?? null,
                'status_perkawinan' => $validated['status_perkawinan'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
            ]
        );

        if (!empty($validated['no_telepon'])) {
            $warga->update(['nomor_hp' => $validated['no_telepon']]);
        }

        return response()->json([
            'message' => 'Data identitas berhasil disimpan.',
        ]);
    }

    public function kirimPengajuan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jenis_surat' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        $warga = $user->warga;
        if (!$warga) {
            return response()->json(['message' => 'Data warga tidak ditemukan'], 404);
        }

        $ocrData = Ocr::where('warga_id', $warga->id)->latest()->first();
        if (!$ocrData) {
            return response()->json(['message' => 'Data identitas (OCR) belum diisi. Silakan simpan data identitas terlebih dahulu.'], 400);
        }

        Surat::create([
            'jenis_surat' => $validated['jenis_surat'],
            'status' => 'menunggu',
            'warga_id' => $warga->id,
            'ocr_id' => $ocrData->id,
        ]);

        return response()->json([
            'message' => 'Pengajuan surat berhasil dikirim.',
            'redirect_url' => route('dashboard')
        ]);
    }
}