<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    public function upload(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'jenis_surat' => ['required', 'string'],
            'ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'kk' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        $namaWarga = filled($user->name) ? $user->name : ($user->email ?? 'warga');

        $warga = $user->warga;

        if (! $warga) {
            $warga = Warga::create([
                'nama' => $namaWarga,
                'nomor_hp' => '-',
                'user_id' => $user->id,
            ]);
        }

        $folderKey = 'dokumen/warga-' . $warga->id . '-' . Str::slug($namaWarga);

        $ktpPath = $request->file('ktp')->store($folderKey . '/ktp', 'public');
        $kkPath = $request->file('kk')->store($folderKey . '/kk', 'public');

        DB::transaction(function () use ($warga, $ktpPath, $kkPath) {
            Dokumen::updateOrCreate(
                ['warga_id' => $warga->id],
                [
                    'warga_id' => $warga->id,
                ]
            );
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Dokumen KTP dan KK berhasil diunggah.',
            ]);
        }

        return back()->with('success', 'Dokumen KTP dan KK berhasil diunggah.');
    }
}