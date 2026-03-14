<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('dodpos:purge-data {--force} {--dry-run}', function () {
    $keepTables = [
        'migrations',
        'users',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'failed_jobs',
        'job_batches',
        'roles',
        'permissions',
        'model_has_roles',
        'model_has_permissions',
        'role_has_permissions',
    ];

    $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();

    $tables = match ($driver) {
        'mysql' => array_map(function ($row) {
            $arr = (array) $row;

            return (string) array_values($arr)[0];
        }, \Illuminate\Support\Facades\DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"')),
        'sqlite' => array_map(fn ($row) => (string) $row->name, \Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")),
        default => array_map(fn ($row) => (string) $row->table_name, \Illuminate\Support\Facades\DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()')),
    };

    $tables = array_values(array_filter($tables, fn ($t) => $t !== ''));
    $keepLookup = array_fill_keys($keepTables, true);
    $toPurge = array_values(array_filter($tables, fn ($t) => ! isset($keepLookup[$t])));
    sort($toPurge);

    if ($this->option('dry-run') || ! $this->option('force')) {
        $this->line('Tabel yang akan dikosongkan:');
        foreach ($toPurge as $t) {
            $this->line('- '.$t);
        }
        if (! $this->option('force')) {
            $this->line('Jalankan lagi dengan --force untuk mengeksekusi.');
        }

        return;
    }

    if (count($toPurge) === 0) {
        $this->info('Tidak ada tabel yang perlu dikosongkan.');

        return;
    }

    if ($driver === 'mysql') {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');
    } elseif ($driver === 'sqlite') {
        \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys = OFF');
    }

    foreach ($toPurge as $table) {
        if ($driver === 'mysql') {
            $safe = str_replace('`', '``', $table);
            \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE `'.$safe.'`');
        } elseif ($driver === 'sqlite') {
            $safe = str_replace('"', '""', $table);
            \Illuminate\Support\Facades\DB::statement('DELETE FROM "'.$safe.'"');
            \Illuminate\Support\Facades\DB::statement('DELETE FROM sqlite_sequence WHERE name = ?', [$table]);
        } else {
            $safe = str_replace('"', '""', $table);
            \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE "'.$safe.'"');
        }
    }

    if ($driver === 'mysql') {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
    } elseif ($driver === 'sqlite') {
        \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys = ON');
    }

    $this->info('Selesai. Semua data (kecuali data user/role) sudah dikosongkan.');
})->purpose('Kosongkan semua data aplikasi kecuali data user/role');

Artisan::command('user:reset-supervisor-password {--email=admin@dodpos.com} {--password=password}', function () {
    $email = (string) $this->option('email');
    $newPassword = (string) $this->option('password');

    $user = \App\Models\User::query()
        ->where('role', 'supervisor')
        ->where('email', $email)
        ->first();

    if (! $user) {
        $user = \App\Models\User::query()->where('role', 'supervisor')->orderBy('id')->first();
    }

    if (! $user) {
        $this->error('Tidak ada user dengan role supervisor.');

        return 1;
    }

    $user->forceFill([
        'password' => \Illuminate\Support\Facades\Hash::make($newPassword),
    ])->save();

    $this->info('Password supervisor berhasil direset.');
    $this->line('Email: '.$user->email);
    $this->line('Password: '.$newPassword);

    return 0;
})->purpose('Reset password akun supervisor');
