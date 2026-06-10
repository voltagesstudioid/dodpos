<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarLoading extends Model
{
    protected $table = 'pasgar_loadings';

    protected $fillable = [
        'nomor_loading',
        'sales_id',
        'sumber',
        'warehouse_id',
        'tanggal',
        'status',
        'catatan',
        'approved_by',
        'approved_at',
        'prepared_by',
        'prepared_at',
        'confirmed_by',
        'ready_at',
        'picked_up_by',
        'picked_up_at',
        'cross_check_notes',
        'loaded_by',
        'loaded_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
        'prepared_at' => 'datetime',
        'ready_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'loaded_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsTo(PasgarSales::class, 'sales_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(PasgarLoadingItem::class, 'loading_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function preparer()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function pickedUpByUser()
    {
        return $this->belongsTo(User::class, 'picked_up_by');
    }

    public function loadedByUser()
    {
        return $this->belongsTo(User::class, 'loaded_by');
    }

    public function penjualans()
    {
        return $this->hasMany(PasgarPenjualan::class, 'loading_id');
    }

    public function setoran()
    {
        return $this->hasOne(PasgarSetoran::class, 'loading_id');
    }

    public function opname()
    {
        return $this->hasOne(PasgarOpname::class, 'loading_id');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'preparing' => 'info',
            'ready' => 'primary',
            'picked_up' => 'success',
            'loaded' => 'success',
            'completed' => 'secondary',
            'opnamed' => 'info',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Approval',
            'preparing' => 'Sedang Disiapkan',
            'ready' => 'Siap Dijemput',
            'picked_up' => 'Terkumpul & Cross-Check',
            'loaded' => 'Dimuat ke Kendaraan',
            'completed' => 'Selesai (Setoran)',
            'opnamed' => 'Selesai (Opname)',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'pending' => '⏳',
            'preparing' => '📦',
            'ready' => '✅',
            'picked_up' => '🚗',
            'loaded' => '🚚',
            'completed' => '🎉',
            'opnamed' => '📋',
            'rejected' => '❌',
            default => '❓',
        };
    }

    public function getSumberLabelAttribute()
    {
        return match ($this->sumber) {
            'toko' => 'Toko',
            'gudang' => 'Gudang',
            'grosir' => 'Grosir',
            'mixed' => 'Gudang & Grosir',
            default => '-',
        };
    }

    /**
     * Determine the sumber value from a list of item sumber values.
     * Returns 'gudang', 'grosir', or 'mixed' if items come from both.
     */
    public static function computeSumberFromItems(array $itemSumberList): string
    {
        $unique = array_unique($itemSumberList);
        if (count($unique) === 0) return 'gudang';
        if (count($unique) === 1) return $unique[0];
        // Multiple sources
        if (in_array('gudang', $unique) && in_array('grosir', $unique)) return 'mixed';
        return $unique[0];
    }

    public static function generateNomor()
    {
        $prefix = 'LDG-PSG-';
        $date = date('Ymd');
        $last = self::where('nomor_loading', 'like', "{$prefix}{$date}-%")
            ->orderBy('nomor_loading', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}-001";
        }

        $parts = explode('-', $last->nomor_loading);
        $lastNum = (int) end($parts);
        return "{$prefix}{$date}-" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}
