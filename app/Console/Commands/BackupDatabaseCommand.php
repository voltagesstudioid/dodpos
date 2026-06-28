<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'db:backup 
                            {--path= : Custom backup path}
                            {--compress : Compress the backup file}
                            {--keep=7 : Number of days to keep backups}
                            {--no-verify : Skip mysqldump availability check}';

    protected $description = 'Backup the database using mysqldump';

    public function handle(): int
    {
        $this->info('Starting database backup...');

        if (! $this->option('no-verify')) {
            $available = $this->checkMysqldump();
            if (! $available) {
                $this->error('mysqldump tidak tersedia di server ini.');
                $this->line('');
                $this->line('Solusi:');
                $this->line('  1. Install mysql-client (paket: mysql-client / mariadb-client)');
                $this->line('  2. Atau gunakan tool backup dari panel hosting (phpMyAdmin, cPanel, dll)');
                $this->line('  3. Atau install spatie/laravel-backup: composer require spatie/laravel-backup');
                return Command::FAILURE;
            }
        }

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        
        $backupPath = $this->option('path') ?: storage_path('app/backups');
        $fullPath = "{$backupPath}/{$filename}";

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $this->info('Mengekspor database...');

        $process = new Process([
            'mysqldump',
            '-h', $dbHost,
            '-u', $dbUser,
            ...($dbPass ? ['-p' . $dbPass] : []),
            $dbName,
        ]);

        try {
            $process->mustRun();
            file_put_contents($fullPath, $process->getOutput());

            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                $this->error('File backup kosong atau gagal dibuat.');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Backup gagal: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $size = $this->formatSize(filesize($fullPath));
        $this->info("Backup created: {$fullPath} ({$size})");

        if ($this->option('compress')) {
            $this->info('Compressing backup...');
            $zipPath = "{$fullPath}.gz";

            $gz = gzopen($zipPath, 'w9');
            if ($gz) {
                gzwrite($gz, file_get_contents($fullPath));
                gzclose($gz);
                unlink($fullPath);
                $fullPath = $zipPath;
                $size = $this->formatSize(filesize($fullPath));
                $this->info("Compressed: {$fullPath} ({$size})");
            } else {
                $this->warn('Gagal mengompres, backup disimpan tanpa kompresi.');
            }
        }

        $keepDays = (int) $this->option('keep');
        $this->cleanupOldBackups($backupPath, $keepDays);

        $this->info('Database backup completed!');
        $this->info("Location: {$fullPath}");

        return Command::SUCCESS;
    }

    private function checkMysqldump(): bool
    {
        if (!function_exists('exec')) {
            $this->warn('Fungsi exec() dinonaktifkan oleh server.');
            return false;
        }

        exec('which mysqldump 2>nul', $output, $code);
        if ($code === 0 && !empty($output)) {
            return true;
        }

        exec('where mysqldump 2>nul', $output, $code);
        return $code === 0 && !empty($output);
    }

    private function cleanupOldBackups(string $backupPath, int $keepDays): void
    {
        $files = glob("{$backupPath}/backup_*.sql*");
        $now = time();
        $deleted = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                $fileAge = $now - filemtime($file);
                if ($fileAge > ($keepDays * 24 * 60 * 60)) {
                    unlink($file);
                    $deleted++;
                }
            }
        }

        if ($deleted > 0) {
            $this->info("Deleted {$deleted} old backup(s)");
        }
    }

    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
