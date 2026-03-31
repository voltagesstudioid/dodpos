<?php

namespace App\Support;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Cache;

class WarehouseConfig
{
    /**
     * Default warehouse codes matching roles.
     */
    public const MAIN_WAREHOUSE_CODE = 'main';
    public const BRANCH_WAREHOUSE_CODE = 'branch';

    /**
     * Get warehouse ID by role.
     * Uses cache to minimize DB queries.
     */
    public static function getIdByRole(string $role): ?int
    {
        $mapping = self::getRoleMapping();

        return $mapping[$role] ?? null;
    }

    /**
     * Get role mapping from config or database.
     */
    public static function getRoleMapping(): array
    {
        return Cache::remember('warehouse_role_mapping', 3600, function () {
            // Try to get from database by code only (is_main/is_branch columns don't exist)
            $main = Warehouse::where('code', self::MAIN_WAREHOUSE_CODE)->first();
            $branch = Warehouse::where('code', self::BRANCH_WAREHOUSE_CODE)->first();

            // Fallback to hardcoded only if DB doesn't have the warehouses
            return [
                'admin3' => $main?->id ?? 1,
                'admin4' => $branch?->id ?? 2,
            ];
        });
    }

    /**
     * Check if user can access specific warehouse.
     */
    public static function canAccess(?string $role, int $warehouseId): bool
    {
        if (! $role) {
            return false;
        }

        $allowedId = self::getIdByRole($role);

        // Supervisor can access all warehouses
        if ($role === 'supervisor') {
            return true;
        }

        return $allowedId === $warehouseId;
    }

    /**
     * Get allowed warehouse ID for role.
     * Returns null for roles with no restriction.
     */
    public static function getAllowedId(?string $role): ?int
    {
        if (! $role) {
            return null;
        }

        if ($role === 'supervisor' || $role === 'admin' || $role === 'owner') {
            return null; // No restriction
        }

        return self::getIdByRole($role);
    }

    /**
     * Clear the cache (call when warehouse config changes).
     */
    public static function clearCache(): void
    {
        Cache::forget('warehouse_role_mapping');
    }

    /**
     * Get main warehouse ID.
     */
    public static function getMainId(): int
    {
        $mapping = self::getRoleMapping();

        return $mapping['admin3'] ?? 1;
    }

    /**
     * Get branch warehouse ID.
     */
    public static function getBranchId(): int
    {
        $mapping = self::getRoleMapping();

        return $mapping['admin4'] ?? 2;
    }
}
