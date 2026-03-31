<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'db:backup 
                            {--path= : Custom backup path}
                            {--compress : Compress the backup file}
                            {--keep=7 : Number of days to keep backups}';

    protected $description = 'Backup the database';

    public function handle()
    {
        $this->info('💾 Starting database backup...');

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        
        $backupPath = $this->option('path') ?: storage_path('app/backups');
        $fullPath = "{$backupPath}/{$filename}";

        // Ensure backup directory exists
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Create backup using mysqldump
        $command = sprintf(
            'mysqldump -h %s -u %s %s %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            $dbPass ? '-p' . escapeshellarg($dbPass) : '',
            escapeshellarg($dbName),
            escapeshellarg($fullPath)
        );

        $this->info("Running: {$command}");
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error('❌ Backup failed!');
            return Command::FAILURE;
        }

        $this->info("✓ Backup created: {$fullPath}");

        // Compress if requested
        if ($this->option('compress')) {
            $this->info('Compressing backup...');
            $zipPath = "{$fullPath}.gz";
            exec("gzip -c " . escapeshellarg($fullPath) . " > " . escapeshellarg($zipPath));
            unlink($fullPath);
            $fullPath = $zipPath;
            $this->info("✓ Compressed backup: {$fullPath}");
        }

        // Clean up old backups
        $keepDays = (int) $this->option('keep');
        $this->cleanupOldBackups($backupPath, $keepDays);

        $this->info('✅ Database backup completed successfully!');
        $this->info("Backup location: {$fullPath}");

        return Command::SUCCESS;
    }

    private function cleanupOldBackups(string $backupPath, int $keepDays): void
    {
        $this->info("Cleaning up backups older than {$keepDays} days...");

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

        $this->info("✓ Deleted {$deleted} old backup(s)");
    }
}
