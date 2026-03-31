<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log a significant action to the audit trail.
     *
     * @param string $action The action type (e.g., 'transaction.void', 'user.create', 'product.update')
     * @param string $entityType The type of entity affected (e.g., 'Transaction', 'User', 'Product')
     * @param int|string|null $entityId The ID of the entity affected
     * @param array $details Additional context data
     * @param string $severity 'info', 'warning', 'critical'
     */
    public static function log(
        string $action,
        string $entityType,
        int|string|null $entityId = null,
        array $details = [],
        string $severity = 'info'
    ): void {
        try {
            $user = Auth::user();

            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'details' => $details,
                'ip_address' => Request::ip(),
                'user_agent' => substr(Request::userAgent() ?? '', 0, 255),
                'severity' => $severity,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Fail silently - don't break the application if audit logging fails
            Log::error('Failed to write audit log', [
                'action' => $action,
                'entity_type' => $entityType,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Log transaction-related actions.
     */
    public static function logTransaction(string $action, $transaction, array $details = []): void
    {
        self::log(
            "transaction.{$action}",
            'Transaction',
            $transaction?->id,
            $details + [
                'transaction_id' => $transaction?->id,
                'amount' => $transaction?->total_amount ?? 0,
                'user_id' => $transaction?->user_id,
            ],
            $action === 'void' ? 'warning' : 'info'
        );
    }

    /**
     * Log user authentication actions.
     */
    public static function logAuth(string $action, ?int $userId = null, array $details = []): void
    {
        self::log(
            "auth.{$action}",
            'User',
            $userId,
            $details,
            in_array($action, ['failed', 'locked', 'suspicious']) ? 'warning' : 'info'
        );
    }

    /**
     * Log inventory/stock actions.
     */
    public static function logInventory(string $action, string $entityType, $entityId, array $details = []): void
    {
        self::log(
            "inventory.{$action}",
            $entityType,
            $entityId,
            $details,
            in_array($action, ['delete', 'transfer', 'adjustment']) ? 'warning' : 'info'
        );
    }

    /**
     * Log backup/restore actions.
     */
    public static function logBackup(string $action, array $details = []): void
    {
        self::log(
            "backup.{$action}",
            'System',
            null,
            $details,
            $action === 'restore' ? 'critical' : 'info'
        );
    }

    /**
     * Get recent audit logs for a specific entity.
     */
    public static function getEntityHistory(string $entityType, int|string $entityId, int $limit = 50): array
    {
        return ActivityLog::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get audit logs for a specific user.
     */
    public static function getUserHistory(int $userId, int $limit = 100): array
    {
        return ActivityLog::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Clean up old audit logs (retention policy).
     */
    public static function cleanup(int $daysToKeep = 90): int
    {
        $cutoff = now()->subDays($daysToKeep);

        // Keep critical events longer
        $deleted = ActivityLog::where('created_at', '<', $cutoff)
            ->where('severity', '!=', 'critical')
            ->delete();

        // Delete very old critical events (1 year)
        $criticalCutoff = now()->subDays(365);
        $deleted += ActivityLog::where('created_at', '<', $criticalCutoff)
            ->where('severity', 'critical')
            ->delete();

        Log::info('Audit log cleanup completed', [
            'deleted_records' => $deleted,
            'retention_days' => $daysToKeep,
        ]);

        return $deleted;
    }
}
