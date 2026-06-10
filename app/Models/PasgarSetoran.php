<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarSetoran extends Model
{
    protected $table = 'pasgar_setorans';

    protected $fillable = [
        'nomor_setoran',
        'loading_id',
        'sales_id',
        'tanggal',
        'total_penjualan',
        'total_tunai',
        'total_transfer',
        'total_setor',
        'selisih',
        'jumlah_transaksi',
        'bukti_setor',
        'catatan_sales',
        'catatan_verifikasi',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_penjualan' => 'decimal:2',
        'total_tunai' => 'decimal:2',
        'total_transfer' => 'decimal:2',
        'total_setor' => 'decimal:2',
        'selisih' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function loading()
    {
        return $this->belongsTo(PasgarLoading::class, 'loading_id');
    }

    public function sales()
    {
        return $this->belongsTo(PasgarSales::class, 'sales_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTerverifikasi($query)
    {
        return $query->where('status', 'terverifikasi');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'terverifikasi' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function getSelisihLabelAttribute(): string
    {
        if ($this->selisih == 0) return 'Pas';
        if ($this->selisih > 0) return 'Lebih';
        return 'Kurang';
    }

    public function getSelisihColorAttribute(): string
    {
        if ($this->selisih == 0) return 'green';
        if ($this->selisih > 0) return 'blue';
        return 'red';
    }

    /**
     * Calculate sales summary for a specific loading.
     */
    public static function calculateSalesSummary(int $loadingId): array
    {
        $penjualan = PasgarPenjualan::where('loading_id', $loadingId);

        // Total cash collected: tunai transactions (full amount)
        $totalTunai = (clone $penjualan)->where('metode_bayar', 'tunai')->sum('total');

        return [
            'total_penjualan' => (clone $penjualan)->sum('total'),
            'jumlah_transaksi' => (clone $penjualan)->count(),
            'total_tunai' => $totalTunai,
            'total_transfer' => (clone $penjualan)->whereIn('metode_bayar', ['transfer', 'qris'])->sum('total'),
        ];
    }


    public static function generateNomor()
    {
        $prefix = 'STR-PSG-';
        $date = date('Ymd');
        $last = self::where('nomor_setoran', 'like', "{$prefix}{$date}-%")
            ->orderBy('nomor_setoran', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}-001";
        }

        $parts = explode('-', $last->nomor_setoran);
        $lastNum = (int) end($parts);
        return "{$prefix}{$date}-" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}
