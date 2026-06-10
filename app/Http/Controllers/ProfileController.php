<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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
        $user = $request->user();
        $validated = $request->validated();
        $changes = [];

        // Update basic fields
        foreach (['name', 'nik', 'email'] as $field) {
            if (isset($validated[$field]) && $validated[$field] !== $user->{$field}) {
                $changes[$field] = ['old' => $user->{$field}, 'new' => $validated[$field]];
                $user->{$field} = $validated[$field];
            }
        }

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update password only if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $changes['password'] = ['old' => '***', 'new' => '***'];
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'public');

            // Delete old photo
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile_photo_path = $path;
            $changes['photo'] = ['old' => '-', 'new' => basename($path)];
        }

        $user->save();

        // Audit trail
        if ($changes) {
            AuditService::log(
                'profile.update',
                'User',
                $user->id,
                $changes,
                'info'
            );
        }

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

        AuditService::log(
            'profile.delete',
            'User',
            $user->id,
            ['name' => $user->name, 'email' => $user->email],
            'warning'
        );

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
        if (!$user->profile_photo_path || !Storage::disk('public')->exists($user->profile_photo_path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($user->profile_photo_path));
    }
}
