<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsBackupController extends Controller
{
    private const TABLES = [
        'store_settings',
        'categories',
        'units',
        'brands',
        'suppliers',
        'warehouses',
        'locations',
    ];

    public function index()
    {
        return view('pengaturan.backup');
    }

    public function export()
    {
        $tables = [];
        foreach (self::TABLES as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                $tables[$table] = DB::table($table)->get()->map(fn ($r) => (array) $r)->all();
            }
        }

        $payload = [
            'type' => 'dodpos_settings_backup',
            'version' => 1,
            'created_at' => now()->toIso8601String(),
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ],
            'tables' => $tables,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $filename = 'dodpos-backup-settings-'.now()->format('Ymd-His').'.json';

        return response($json, 200, [
            'Content-Type' => 'application/json; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json,txt|max:10240',
            'password' => 'required|string',
        ]);

        $user = Auth::user();
        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return back()->with('error', 'Password salah. Restore dibatalkan.');
        }

        $raw = $request->file('backup_file')->get();
        $data = json_decode($raw, true);
        if (! is_array($data) || ($data['type'] ?? null) !== 'dodpos_settings_backup' || ! isset($data['tables']) || ! is_array($data['tables'])) {
            return back()->with('error', 'File backup tidak valid.');
        }

        DB::transaction(function () use ($data) {
            foreach (self::TABLES as $table) {
                $rows = $data['tables'][$table] ?? null;
                if (! $rows || ! is_array($rows)) {
                    continue;
                }
                if (! DB::getSchemaBuilder()->hasTable($table)) {
                    continue;
                }

                foreach ($rows as $row) {
                    if (! is_array($row) || ! array_key_exists('id', $row)) {
                        continue;
                    }
                    $id = $row['id'];
                    $row = array_filter(
                        $row,
                        fn ($k) => $k !== 'id',
                        ARRAY_FILTER_USE_KEY
                    );

                    DB::table($table)->updateOrInsert(['id' => $id], $row);
                }
            }
        });

        return redirect()->route('pengaturan.backup')->with('success', 'Restore selesai. Data pengaturan/master sudah diperbarui.');
    }
}
