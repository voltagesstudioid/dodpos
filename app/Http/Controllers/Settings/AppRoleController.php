<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AppRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AppRoleController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = AppRole::query()->orderBy('key');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%");
            });
        }

        $roles = $query->paginate(20)->withQueryString();

        return view('pengaturan.roles.index', compact('roles', 'search'));
    }

    public function create()
    {
        return view('pengaturan.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', 'unique:app_roles,key'],
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        AppRole::create([
            'key' => strtolower($request->string('key')->value()),
            'label' => $request->string('label')->value(),
            'description' => $request->input('description'),
            'active' => (bool) $request->boolean('active'),
        ]);

        return redirect()->route('pengaturan.roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(AppRole $role)
    {
        return view('pengaturan.roles.edit', compact('role'));
    }

    public function update(Request $request, AppRole $role)
    {
        $request->validate([
            'key' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('app_roles', 'key')->ignore($role->id),
            ],
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        $role->update([
            'key' => strtolower($request->string('key')->value()),
            'label' => $request->string('label')->value(),
            'description' => $request->input('description'),
            'active' => (bool) $request->boolean('active'),
        ]);

        return redirect()->route('pengaturan.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(AppRole $role)
    {
        if ($role->key === 'supervisor') {
            return back()->with('error', 'Role supervisor tidak dapat dihapus.');
        }

        $used = User::where('role', $role->key)->exists();
        if ($used) {
            return back()->with('error', 'Role ini sedang digunakan oleh akun user.');
        }

        $role->delete();

        return redirect()->route('pengaturan.roles.index')->with('success', 'Role berhasil dihapus.');
    }

    public function migrate()
    {
        $roles = AppRole::query()->orderBy('label')->get(['id', 'key', 'label', 'active']);
        $roleKeys = $roles->pluck('key')->values()->all();

        $usedRoleKeys = User::query()
            ->select('role')
            ->distinct()
            ->whereNotNull('role')
            ->pluck('role')
            ->map(fn (string $r) => strtolower(trim($r)))
            ->unique()
            ->values()
            ->all();

        $usedRequestedKeys = User::query()
            ->select('requested_role')
            ->distinct()
            ->whereNotNull('requested_role')
            ->pluck('requested_role')
            ->map(fn (string $r) => strtolower(trim($r)))
            ->unique()
            ->values()
            ->all();

        $unknownInUsersRole = collect($usedRoleKeys)->diff($roleKeys)->values()->all();
        $unknownInRequestedRole = collect($usedRequestedKeys)->diff($roleKeys)->values()->all();

        return view('pengaturan.roles.migrate', [
            'roles' => $roles,
            'unknownInUsersRole' => $unknownInUsersRole,
            'unknownInRequestedRole' => $unknownInRequestedRole,
        ]);
    }

    public function migrateStore(Request $request)
    {
        $request->validate([
            'from' => ['required', 'string', 'max:50'],
            'to' => ['required', 'string', 'max:50', 'different:from'],
            'include_requested_role' => ['nullable', 'boolean'],
        ]);

        $from = strtolower($request->string('from')->value());
        $to = strtolower($request->string('to')->value());

        if ($from === 'supervisor') {
            return back()->with('error', 'Role supervisor tidak dapat dimigrasikan.');
        }
        if ($to === 'pending') {
            return back()->with('error', 'Role tujuan tidak boleh pending.');
        }

        $toExists = AppRole::query()->where('key', $to)->where('active', true)->exists();
        if (! $toExists) {
            return back()->with('error', 'Role tujuan tidak valid atau nonaktif.');
        }

        $includeRequestedRole = (bool) $request->boolean('include_requested_role');

        $updatedRole = 0;
        $updatedRequested = 0;

        DB::transaction(function () use ($from, $to, $includeRequestedRole, &$updatedRole, &$updatedRequested) {
            $updatedRole = User::query()->where('role', $from)->update(['role' => $to]);
            if ($includeRequestedRole) {
                $updatedRequested = User::query()->where('requested_role', $from)->update(['requested_role' => $to]);
            }
        });

        $msg = 'Migrasi role selesai. User updated: '.$updatedRole;
        if ($includeRequestedRole) {
            $msg .= ', requested_role updated: '.$updatedRequested;
        }

        return redirect()->route('pengaturan.roles.migrate')->with('success', $msg);
    }
}
