<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BackupValidationService
{
    /**
     * Backup version supported by this application.
     */
    private const SUPPORTED_VERSION = 1;

    /**
     * Required fields in backup file.
     */
    private const REQUIRED_FIELDS = ['type', 'version', 'created_at', 'tables'];

    /**
     * Valid table names that can be restored.
     */
    private const VALID_TABLES = [
        'store_settings',
        'categories',
        'units',
        'brands',
        'suppliers',
        'warehouses',
        'locations',
    ];

    /**
     * Maximum allowed rows per table.
     */
    private const MAX_ROWS_PER_TABLE = 10000;

    /**
     * Maximum backup file size in KB.
     */
    private const MAX_FILE_SIZE_KB = 10240; // 10MB

    /**
     * Validate backup file content.
     *
     * @param array $data Decoded JSON data
     * @param int $fileSize File size in bytes
     * @return array ['valid' => bool, 'errors' => string[]]
     */
    public static function validate(array $data, int $fileSize): array
    {
        $errors = [];

        // Check file size
        if ($fileSize > self::MAX_FILE_SIZE_KB * 1024) {
            $errors[] = 'Ukuran file backup terlalu besar (maksimal ' . self::MAX_FILE_SIZE_KB . ' KB).';
        }

        // Check required fields
        foreach (self::REQUIRED_FIELDS as $field) {
            if (! isset($data[$field])) {
                $errors[] = "Field wajib '{$field}' tidak ditemukan dalam file backup.";
            }
        }

        if (! empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate type
        if ($data['type'] !== 'dodpos_settings_backup') {
            $errors[] = 'Tipe backup tidak valid.';
        }

        // Validate version
        if (! is_int($data['version']) || $data['version'] > self::SUPPORTED_VERSION) {
            $errors[] = 'Versi backup tidak didukung. Versi yang didukung: ' . self::SUPPORTED_VERSION;
        }

        // Validate timestamp
        if (! self::isValidTimestamp($data['created_at'])) {
            $errors[] = 'Timestamp backup tidak valid.';
        }

        // Validate tables structure
        if (! is_array($data['tables'])) {
            $errors[] = 'Struktur tabel backup tidak valid.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Check for malicious/unknown tables
        foreach (array_keys($data['tables']) as $tableName) {
            if (! in_array($tableName, self::VALID_TABLES, true)) {
                $errors[] = "Tabel '{$tableName}' tidak diizinkan dalam backup.";
                Log::warning('Backup validation failed - unknown table', ['table' => $tableName]);
            }
        }

        // Validate row counts and structure
        foreach ($data['tables'] as $tableName => $rows) {
            if (! is_array($rows)) {
                $errors[] = "Data tabel '{$tableName}' tidak valid.";
                continue;
            }

            if (count($rows) > self::MAX_ROWS_PER_TABLE) {
                $errors[] = "Tabel '{$tableName}' memiliki terlalu banyak baris (maksimal " . self::MAX_ROWS_PER_TABLE . ").";
            }

            // Validate each row has required structure
            foreach ($rows as $index => $row) {
                if (! is_array($row)) {
                    $errors[] = "Baris {$index} di tabel '{$tableName}' tidak valid.";
                    continue;
                }

                if (! isset($row['id'])) {
                    $errors[] = "Baris {$index} di tabel '{$tableName}' tidak memiliki ID.";
                }

                // Check for suspicious keys (potential SQL injection or malicious data)
                foreach (array_keys($row) as $key) {
                    if (! self::isValidColumnName($key)) {
                        $errors[] = "Kolom '{$key}' di tabel '{$tableName}' tidak valid.";
                        Log::warning('Backup validation failed - invalid column', [
                            'table' => $tableName,
                            'column' => $key,
                        ]);
                    }
                }
            }
        }

        $valid = empty($errors);

        if (! $valid) {
            Log::warning('Backup validation failed', ['errors' => $errors]);
        }

        return ['valid' => $valid, 'errors' => $errors];
    }

    /**
     * Generate signature for backup data integrity.
     */
    public static function generateSignature(array $data, string $secretKey): string
    {
        $canonical = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_SORT_KEYS);
        return hash_hmac('sha256', $canonical, $secretKey);
    }

    /**
     * Verify backup signature.
     */
    public static function verifySignature(array $data, string $signature, string $secretKey): bool
    {
        $expected = self::generateSignature($data, $secretKey);
        return hash_equals($expected, $signature);
    }

    /**
     * Check if timestamp is valid ISO 8601 format.
     */
    private static function isValidTimestamp(string $timestamp): bool
    {
        try {
            $date = new \DateTime($timestamp);
            return $date->format(\DateTime::ATOM) === $timestamp ||
                   $date->format(\DateTime::ISO8601) === $timestamp;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate column name (prevent SQL injection).
     */
    private static function isValidColumnName(string $name): bool
    {
        // Only allow alphanumeric and underscore
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name) === 1;
    }
}
