<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationPendingMail;
use App\Models\AppRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = AppRole::query()
            ->active()
            ->whereNotIn('key', ['supervisor', 'pending'])
            ->orderBy('label')
            ->get(['key', 'label'])
            ->map(fn (AppRole $r) => (object) ['key' => $r->key, 'label' => $r->label])
            ->values();

        if ($roles->count() === 0) {
            $roles = collect(User::ROLES)
                ->filter(fn (string $r) => ! in_array($r, ['supervisor', 'pending'], true))
                ->map(fn (string $r) => (object) ['key' => $r, 'label' => strtoupper(str_replace('_', ' ', $r))])
                ->values();
        }

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $requestedRole = (string) $request->input('requested_role', '');

        $email = (string) $request->input('email', '');

        $existingRejectedUser = null;
        if ($email !== '') {
            $existing = User::where('email', $email)->first();
            if ($existing && ! $existing->active && $existing->rejected_at && ! $existing->approved_at) {
                $existingRejectedUser = $existing;
            }
        }

        $emailRule = $existingRejectedUser
            ? ['required', 'string', 'lowercase', 'email', 'max:255']
            : ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRule,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'requested_role' => ['nullable', 'string'],
        ]);

        if ($requestedRole !== '' && ! User::isValidRole($requestedRole)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'requested_role' => 'Role yang dipilih tidak valid.',
            ]);
        }

        if (in_array(strtolower($requestedRole), ['supervisor', 'pending'], true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'requested_role' => 'Role ini tidak tersedia untuk pendaftaran.',
            ]);
        }

        if ($existingRejectedUser) {
            $existingRejectedUser->delete();
        }

        $user = User::create([
            'name' => (string) $validated['name'],
            'email' => (string) $validated['email'],
            'password' => Hash::make((string) $validated['password']),
            'role' => 'pending',
            'requested_role' => $requestedRole !== '' ? $requestedRole : null,
            'active' => false,
            'remember_token' => Str::random(10),
        ]);

        $to = collect(explode(',', (string) env('SUPERVISOR_APPROVAL_EMAILS', '')))
            ->map(fn (string $e) => trim($e))
            ->filter(fn (string $e) => filter_var($e, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()
            ->values()
            ->all();

        if (count($to) > 0) {
            foreach ($to as $addr) {
                Mail::to($addr)->send(new RegistrationPendingMail(
                    name: (string) $user->name,
                    email: (string) $user->email,
                    requestedRole: $user->requested_role
                ));
            }
        }

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran berhasil. Akun Anda menunggu ACC supervisor.');
    }
}
