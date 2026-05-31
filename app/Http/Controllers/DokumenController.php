<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        if ($warga) {
            $existingDocuments = $this->getExistingDocuments($warga);
        }

        return view('warga.pengurusan', [
            'existingKtp' => $existingDocuments['ktp'],
            'existingKk' => $existingDocuments['kk'],
            'warga' => $warga,
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

        Dokumen::updateOrCreate(
            ['warga_id' => $warga->id],
            [
                'warga_id' => $warga->id,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Dokumen KTP dan KK berhasil diunggah.',
                'ktp' => $finalDocuments['ktp']['url'] ?? null,
                'kk' => $finalDocuments['kk']['url'] ?? null,
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
        return [
            'path' => $file['path'],
            'name' => $file['name'],
            'preview_url' => route('warga.dokumen.preview', ['type' => $type]),
            'download_url' => route('warga.dokumen.download', ['type' => $type]),
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
}