<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Petugas;
use App\Models\Warga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $request->user()->loadMissing(['warga', 'petugas']);

        $view = $request->user()->role === 'petugas'
            ? 'profile.petugas'
            : 'profile.warga';

        return view($view, [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $validated = $request->validated();

            $user->fill([
                'email' => $validated['email'],
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            if ($user->role === 'petugas') {
                Petugas::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama' => $validated['nama'],
                        'nomor_hp' => $validated['nomor_hp'],
                    ]
                );
            } else {
                Warga::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama' => $validated['nama'],
                        'nomor_hp' => $validated['nomor_hp'],
                    ]
                );
            }
        });

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
