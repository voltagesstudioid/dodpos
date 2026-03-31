<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'entity_type',
        'entity_id',
        'details',
        'ip_address',
        'user_agent',
        'severity',
        'created_at',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => $this->user_name ?? 'System',
        ]);
    }

    /**
     * Scope for filtering by action type.
     */
    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by entity.
     */
    public function scopeForEntity($query, string $type, $id = null)
    {
        $query = $query->where('entity_type', $type);
        if ($id !== null) {
            $query->where('entity_id', $id);
        }
        return $query;
    }

    /**
     * Scope for filtering by severity.
     */
    public function scopeWithSeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope for recent logs.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get a human-readable description of the action.
     */
    public function getDescriptionAttribute(): string
    {
        $descriptions = [
            'transaction.void' => 'Transaksi dibatalkan',
            'transaction.create' => 'Transaksi baru dibuat',
            'transaction.refund' => 'Transaksi direfund',
            'auth.login' => 'Login berhasil',
            'auth.logout' => 'Logout',
            'auth.failed' => 'Login gagal',
            'user.create' => 'Pengguna baru dibuat',
            'user.update' => 'Data pengguna diubah',
            'user.delete' => 'Pengguna dihapus',
            'product.create' => 'Produk baru ditambahkan',
            'product.update' => 'Data produk diubah',
            'product.delete' => 'Produk dihapus',
            'product.import' => 'Import produk',
            'inventory.transfer' => 'Transfer stok',
            'inventory.adjustment' => 'Penyesuaian stok',
            'backup.export' => 'Backup data diexport',
            'backup.restore' => 'Backup data direstore',
        ];

        return $descriptions[$this->action] ?? $this->action;
    }

    /**
     * Get severity badge color.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'red',
            'warning' => 'yellow',
            default => 'blue',
        };
    }
}
