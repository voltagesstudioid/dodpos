<?php

namespace App\Support;

/**
 * Application role constants to replace hardcoded role strings.
 * This ensures type safety and prevents typos in role checks.
 */
class Roles
{
    public const SUPERVISOR = 'supervisor';
    public const ADMIN_SALES = 'admin_sales';
    public const ADMIN1 = 'admin1';     // Kasir Utama
    public const ADMIN2 = 'admin2';     // Kasir Biasa
    public const ADMIN3 = 'admin3';     // Gudang Masuk
    public const ADMIN4 = 'admin4';     // Gudang Keluar
    public const KASIR = 'kasir';
    public const GUDANG = 'gudang';
    public const SALES = 'sales';
    public const PENDING = 'pending';

    /**
     * Roles that can approve transfers and certain transactions.
     */
    public static function transferApprovers(): array
    {
        return [self::ADMIN3, self::SUPERVISOR];
    }

    /**
     * Roles that can access Kasir/POS features.
     */
    public static function kasirRoles(): array
    {
        return [self::ADMIN1, self::ADMIN2, self::KASIR];
    }

    /**
     * Roles that can access warehouse features.
     */
    public static function warehouseRoles(): array
    {
        return [self::ADMIN3, self::ADMIN4, self::GUDANG];
    }

    /**
     * All valid roles in the system.
     */
    public static function all(): array
    {
        return [
            self::SUPERVISOR,
            self::ADMIN_SALES,
            self::ADMIN1,
            self::ADMIN2,
            self::ADMIN3,
            self::ADMIN4,
            self::KASIR,
            self::GUDANG,
            self::SALES,
            self::PENDING,
        ];
    }

    /**
     * Check if a role is valid.
     */
    public static function isValid(string $role): bool
    {
        return in_array($role, self::all(), true);
    }

    /**
     * Check if role can perform transfer operations.
     */
    public static function canTransfer(string $role): bool
    {
        return in_array($role, self::transferApprovers(), true);
    }
}
