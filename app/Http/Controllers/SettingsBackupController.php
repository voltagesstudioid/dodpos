<?php

namespace App\Http\Controllers;

use App\Services\BackupValidationService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        // Generate signature for data integrity (optional, for future use)
        $signature = BackupValidationService::generateSignature(
            $payload,
            config('app.key')
        );
        $payload['signature'] = $signature;

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

        // Securely upload and validate the backup file
        try {
            $upload = FileUploadService::uploadDocument(
                $request->file('backup_file'),
                'temp/backups',
                'private',
                ['application/json' => 'json', 'text/plain' => 'txt']
            );
            $filePath = storage_path('app/private/' . $upload['path']);
            $raw = file_get_contents($filePath);

            // Clean up temp file immediately
            FileUploadService::delete($upload['path'], 'private');
        } catch (\Exception $e) {
            Log::error('Backup file upload failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengunggah file backup: ' . $e->getMessage());
        }

        $data = json_decode($raw, true);

        // Validate backup structure and content
        $validation = BackupValidationService::validate($data, strlen($raw));
        if (! $validation['valid']) {
            $errorMsg = 'Validasi backup gagal: ' . implode('; ', array_slice($validation['errors'], 0, 3));
            Log::warning('Backup restore validation failed', [
                'user_id' => $user->id,
                'errors' => $validation['errors'],
            ]);
            return back()->with('error', $errorMsg);
        }

        // Verify signature if present
        if (isset($data['signature'])) {
            $signature = $data['signature'];
            unset($data['signature']); // Remove before verification

            if (! BackupValidationService::verifySignature($data, $signature, config('app.key'))) {
                Log::warning('Backup signature verification failed', ['user_id' => $user->id]);
                return back()->with('error', 'Tanda tangan digital backup tidak valid. File mungkin telah dimodifikasi.');
            }
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

        Log::info('Backup restored successfully', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'backup_created_at' => $data['created_at'] ?? 'unknown',
        ]);

        return redirect()->route('pengaturan.backup')->with('success', 'Restore selesai. Data pengaturan/master sudah diperbarui.');
    }
}
