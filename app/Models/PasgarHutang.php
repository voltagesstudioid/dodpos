<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarHutang extends Model
{
    protected $table = 'pasgar_hutang';

    protected $fillable = [
        'pelanggan_id', 'penjualan_id', 'total_hutang', 'dibayar', 'sisa',
        'jatuh_tempo', 'status', 'keterangan',
    ];

    protected $casts = [
        'total_hutang' => 'decimal:2',
        'dibayar' => 'decimal:2',
        'sisa' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(PasgarPelanggan::class, 'pelanggan_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(PasgarPenjualan::class, 'penjualan_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(PasgarHutangBayar::class, 'hutang_id');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'belum_lunas')
                     ->where('jatuh_tempo', '<', now());
    }

    /**
     * Mark all overdue hutang records.
     */
    public static function markAllOverdue(): int
    {
        return self::where('status', 'belum_lunas')
            ->where('jatuh_tempo', '<', now())
            ->update(['status' => 'overdue']);
    }

    /**
     * Recalculate dibayar and sisa from actual payment records.
     */
    public function recalculate(): void
    {
        $totalDibayar = $this->pembayarans()->where('status', 'confirmed')->sum('jumlah');
        $this->dibayar = $totalDibayar;
        $this->sisa    = max(0, (float) $this->total_hutang - (float) $totalDibayar);

        if ($this->sisa <= 0) {
            $this->status = 'lunas';
        } elseif ($this->jatuh_tempo && $this->jatuh_tempo->isPast()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'belum_lunas';
        }

        $this->save();
    }
}
