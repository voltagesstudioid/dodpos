<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationApprovedMail;
use App\Mail\RegistrationRejectedMail;
use App\Models\AppRole;
use App\Models\User;
use App\Support\SearchSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $role = $request->role;
        $status = $request->input('status');

        $query = User::query();

        if ($search) {
            $sanitizedSearch = SearchSanitizer::sanitize($search);
            $query->where(function ($q) use ($sanitizedSearch) {
                $q->where('name', 'like', "%{$sanitizedSearch}%")
                    ->orWhere('email', 'like', "%{$sanitizedSearch}%")
                    ->orWhere('nik', 'like', "%{$sanitizedSearch}%");
            });
        }
        if ($role) {
            $query->where('role', $role);
        }
        if ($status === 'pending') {
            $query->where('active', false)->whereNull('approved_at')->whereNull('rejected_at');
        } elseif ($status !== null && $status !== '') {
            $query->where('active', (bool) ((int) $status));
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        $allRoles = AppRole::query()
            ->active()
            ->where('key', '!=', 'pending')
            ->orderBy('label')
            ->get(['key', 'label'])
            ->map(fn (AppRole $r) => (object) ['name' => $r->key, 'label' => $r->label])
            ->values();

        if ($allRoles->count() === 0) {
            $allRoles = collect(User::ROLES)
                ->filter(fn (string $r) => $r !== 'pending')
                ->map(fn (string $r) => (object) ['name' => $r, 'label' => strtoupper($r)])
                ->values();
        }

        $roleLabels = AppRole::query()
            ->active()
            ->get(['key', 'label'])
            ->pluck('label', 'key')
            ->toArray();
        if (count($roleLabels) === 0) {
            $roleLabels = collect(User::ROLES)
                ->filter(fn (string $r) => $r !== 'pending')
                ->mapWithKeys(fn (string $r) => [$r => strtoupper($r)])
                ->all();
        }

        // Compute stats from the full dataset
        $totalUsers = User::count();
        $activeUsers = User::where('active', true)->count();
        $inactiveUsers = User::where('active', false)->where(function ($q) {
            $q->whereNotNull('approved_at')->orWhereNotNull('rejected_at');
        })->count();
        $pendingUsers = User::where('active', false)->whereNull('approved_at')->whereNull('rejected_at')->count();
        $stats = compact('totalUsers', 'activeUsers', 'inactiveUsers', 'pendingUsers');

        return view('pengaturan.pengguna.index', compact('users', 'search', 'role', 'status', 'allRoles', 'roleLabels', 'stats'));
    }

    public function create()
    {
        $roles = AppRole::query()
            ->active()
            ->where('key', '!=', 'pending')
            ->orderBy('label')
            ->get(['key', 'label'])
            ->map(fn (AppRole $r) => (object) ['name' => $r->key, 'label' => $r->label])
            ->values();

        if ($roles->count() === 0) {
            $roles = collect(User::ROLES)
                ->filter(fn (string $r) => $r !== 'pending')
                ->map(fn (string $r) => (object) ['name' => $r, 'label' => strtoupper($r)])
                ->values();
        }

        return view('pengaturan.pengguna.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $allowedRoles = AppRole::query()
            ->active()
            ->where('key', '!=', 'pending')
            ->pluck('key')
            ->all();
        if (count($allowedRoles) === 0) {
            $allowedRoles = collect(User::ROLES)->filter(fn (string $r) => $r !== 'pending')->values()->all();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:32|unique:users,nik',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in($allowedRoles)],
            'fingerprint_id' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'active' => $request->has('active'),
            'email_verified_at' => now(),
            'fingerprint_id' => $request->fingerprint_id,
        ]);

        $this->syncProfiles($user);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        $roles = AppRole::query()
            ->active()
            ->where('key', '!=', 'pending')
            ->orderBy('label')
            ->get(['key', 'label'])
            ->map(fn (AppRole $r) => (object) ['name' => $r->key, 'label' => $r->label])
            ->values();

        if ($roles->count() === 0) {
            $roles = collect(User::ROLES)
                ->filter(fn (string $r) => $r !== 'pending')
                ->map(fn (string $r) => (object) ['name' => $r, 'label' => strtoupper($r)])
                ->values();
        }

        return view('pengaturan.pengguna.edit', compact('pengguna', 'roles'));
    }

    public function update(Request $request, User $pengguna)
    {
        $allowedRoles = AppRole::query()
            ->active()
            ->where('key', '!=', 'pending')
            ->pluck('key')
            ->all();
        if (count($allowedRoles) === 0) {
            $allowedRoles = collect(User::ROLES)->filter(fn (string $r) => $r !== 'pending')->values()->all();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'max:32', Rule::unique('users', 'nik')->ignore($pengguna->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in($allowedRoles)],
            'fingerprint_id' => 'nullable|string|max:50',
        ]);

        $data = [
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
            'role' => $request->role,
            'active' => $request->has('active'),
            'fingerprint_id' => $request->fingerprint_id,
        ];
        if ((string) $pengguna->email !== (string) $request->email) {
            $data['email_verified_at'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        $this->syncProfiles($pengguna);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        if (Auth::id() === $pengguna->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function approve(User $pengguna)
    {
        if ($pengguna->active) {
            return back()->with('error', 'Akun ini sudah aktif.');
        }

        $requested = $pengguna->requested_role ?: null;
        $defaultRole = 'admin1';
        if (! User::isValidRole($defaultRole)) {
            $defaultRole = AppRole::query()->active()->orderBy('label')->value('key') ?: 'admin1';
        }

        $role = User::isValidRole($requested) ? $requested : $defaultRole;
        $role = User::isValidRole($role) ? $role : $defaultRole;

        $roleLabel = AppRole::where('key', $role)->value('label')
            ?? strtoupper(str_replace('_', ' ', (string) $role));

        $pengguna->update([
            'role' => $role,
            'active' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejected_at' => null,
            'rejected_by' => null,
        ]);

        $this->syncProfiles($pengguna);

        try {
            Mail::to($pengguna->email)->send(new RegistrationApprovedMail(
                name: (string) $pengguna->name,
                email: (string) $pengguna->email,
                role: $roleLabel,
            ));
        } catch (\Throwable) {
            Log::warning('Failed to send approval email to ' . $pengguna->email);
        }

        $msg = 'Akun berhasil di-ACC dan diaktifkan.';
        return back()->with('success', $msg);
    }

    public function reject(User $pengguna)
    {
        if ($pengguna->active) {
            return back()->with('error', 'Akun ini sudah aktif.');
        }

        if ($pengguna->rejected_at) {
            return back()->with('error', 'Akun ini sudah ditolak.');
        }

        $pengguna->update([
            'active' => false,
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        try {
            Mail::to($pengguna->email)->send(new RegistrationRejectedMail(
                name: (string) $pengguna->name,
                email: (string) $pengguna->email,
            ));
        } catch (\Throwable) {
            Log::warning('Failed to send rejection email to ' . $pengguna->email);
        }

        return back()->with('success', 'Akun berhasil ditolak.');
    }

    private function syncProfiles(User $user): void
    {
        if (!$user->active) {
            return;
        }

        // Sync SDM Employee Profile
        if (!\App\Models\SdmEmployee::where('user_id', $user->id)->exists()) {
            $roleLabel = \App\Models\AppRole::where('key', $user->role)->value('label');
            if (!$roleLabel) {
                $roleLabel = strtoupper(str_replace('_', ' ', (string) $user->role));
            }

            \App\Models\SdmEmployee::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'position' => $roleLabel,
                'join_date' => now(),
                'active' => true,
                'basic_salary' => 0,
                'daily_allowance' => 0,
            ]);
        }

        // Sync Sales Profile
        $role = strtolower(trim((string) $user->role));
        if (!str_starts_with($role, 'sales_') && $role !== 'sales') {
            return;
        }

        $division = $user->division;
        $data = [
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'status' => 'aktif',
        ];

        if ($division === 'minyak') {
            if (!\App\Models\MinyakSales::where('user_id', $user->id)->exists()) {
                $data['kode_sales'] = \App\Models\MinyakSales::generateKode();
                \App\Models\MinyakSales::create($data);
            }
        } elseif ($division === 'gula') {
            if (!\App\Models\GulaSales::where('user_id', $user->id)->exists()) {
                $data['kode_sales'] = \App\Models\GulaSales::generateKode();
                \App\Models\GulaSales::create($data);
            }
        } elseif ($division === 'mineral') {
            if (!\App\Models\MineralSales::where('user_id', $user->id)->exists()) {
                $data['kode_sales'] = \App\Models\MineralSales::generateKode();
                \App\Models\MineralSales::create($data);
            }
        } elseif ($division === 'pasgar') {
            if (!\App\Models\PasgarSales::where('user_id', $user->id)->exists()) {
                $data['kode_sales'] = \App\Models\PasgarSales::generateKode();
                \App\Models\PasgarSales::create($data);
            }
        }
    }
}
