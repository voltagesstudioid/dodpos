<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class FingerprintEncryptionService
{
    /**
     * Encrypt a fingerprint ID for secure storage.
     * Uses Laravel's built-in encryption with AES-256-GCM.
     */
    public static function encrypt(?string $fingerprintId): ?string
    {
        if ($fingerprintId === null || $fingerprintId === '') {
            return null;
        }

        // Don't double-encrypt
        if (self::isEncrypted($fingerprintId)) {
            return $fingerprintId;
        }

        return Crypt::encryptString($fingerprintId);
    }

    /**
     * Decrypt a fingerprint ID for use.
     */
    public static function decrypt(?string $encrypted): ?string
    {
        if ($encrypted === null || $encrypted === '') {
            return null;
        }

        // Not encrypted, return as-is
        if (! self::isEncrypted($encrypted)) {
            return $encrypted;
        }

        try {
            return Crypt::decryptString($encrypted);
        } catch (\Exception $e) {
            // If decryption fails, assume it's plain text (backward compatibility)
            return $encrypted;
        }
    }

    /**
     * Check if a value is already encrypted.
     */
    public static function isEncrypted(?string $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }

        // Laravel encrypted strings start with base64 encoded header
        // eyJpdiI6 - base64 of {"iv":
        return str_starts_with($value, 'eyJpdiI6') || str_starts_with($value, 'eyJpdiI');
    }

    /**
     * Hash a fingerprint ID for searching (one-way, deterministic).
     * Use this when you need to search by fingerprint without decrypting.
     */
    public static function hash(?string $fingerprintId): ?string
    {
        if ($fingerprintId === null || $fingerprintId === '') {
            return null;
        }

        // Use a pepper (additional secret) for extra security
        $pepper = config('app.key');
        return hash_hmac('sha256', $fingerprintId, $pepper);
    }

    /**
     * Verify if a plain fingerprint ID matches a stored hash.
     */
    public static function verifyHash(?string $fingerprintId, ?string $hash): bool
    {
        if ($fingerprintId === null || $hash === null) {
            return false;
        }

        return hash_equals($hash, self::hash($fingerprintId));
    }
}
