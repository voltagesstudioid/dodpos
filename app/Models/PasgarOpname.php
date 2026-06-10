<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarOpname extends Model
{
    protected $table = 'pasgar_opnames';

    protected $fillable = [
        'nomor_opname',
        'loading_id',
        'sales_id',
        'tanggal',
        'catatan',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'confirmed_at' => 'datetime',
    ];

    // --- Relationships ---

    public function loading()
    {
        return $this->belongsTo(PasgarLoading::class, 'loading_id');
    }

    public function sales()
    {
        return $this->belongsTo(PasgarSales::class, 'sales_id');
    }

    public function items()
    {
        return $this->hasMany(PasgarOpnameItem::class, 'opname_id');
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    // --- Scopes ---

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // --- Accessors ---

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Terkonfirmasi',
            default => 'Tidak Diketahui',
        };
    }

    public function getTotalFisikAttribute(): int
    {
        return $this->items->sum('qty_fisik');
    }

    public function getTotalSelisihAttribute(): int
    {
        return $this->items->sum('qty_selisih');
    }

    // --- Static Helpers ---

    public static function generateNomor(): string
    {
        $prefix = 'OPN-PSG-';
        $date = date('Ymd');
        $last = self::where('nomor_opname', 'like', "{$prefix}{$date}-%")
            ->orderBy('nomor_opname', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}-001";
        }

        $parts = explode('-', $last->nomor_opname);
        $lastNum = (int) end($parts);
        return "{$prefix}{$date}-" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}
