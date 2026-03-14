<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (! Schema::hasColumn('users', 'nik')) {
            return back()->with('error', 'Fitur NIK belum aktif. Jalankan migrasi database terlebih dahulu.');
        }
        if (! Schema::hasColumn('users', 'profile_photo_path')) {
            return back()->with('error', 'Fitur foto profil belum aktif. Jalankan migrasi database terlebih dahulu.');
        }

        $request->user()->fill(Arr::only($validated, ['name', 'nik', 'email', 'password']));

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'public');
            if ($request->user()->profile_photo_path) {
                Storage::disk('public')->delete($request->user()->profile_photo_path);
            }
            $request->user()->profile_photo_path = $path;
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Serve profile photo from storage for the given user.
     */
    public function photo(\App\Models\User $user)
    {
        if (! $user->profile_photo_path) {
            abort(404);
        }
        if (! \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo_path)) {
            abort(404);
        }

        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($user->profile_photo_path));
    }
}
