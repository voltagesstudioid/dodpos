<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPickOrder extends Model
{
    protected $fillable = [
        'pick_number', 'transaction_id', 'warehouse_id', 'status',
        'pos_type', 'requested_by', 'processed_by', 'confirmed_by',
        'ready_at', 'confirmed_at', 'notes',
    ];

    protected $casts = [
        'ready_at'     => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    // Status labels
    public static array $statusLabels = [
        'pending'    => 'Menunggu',
        'processing' => 'Diproses',
        'ready'      => 'Siap Diambil',
        'completed'  => 'Selesai',
    ];

    public static array $statusColors = [
        'pending'    => 'warning',
        'processing' => 'info',
        'ready'      => 'success',
        'completed'  => 'secondary',
    ];

    // Auto-generate pick number
    public static function generateNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = 'PO-' . $date . '-';
        $last   = static::where('pick_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(PosPickOrderItem::class, 'pick_order_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    // Scopes
    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeReady($q)   { return $q->where('status', 'ready'); }
    public function scopeActive($q)  { return $q->whereIn('status', ['pending', 'processing', 'ready']); }

    // Helpers
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'secondary';
    }
}
