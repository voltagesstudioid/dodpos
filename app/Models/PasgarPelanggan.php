<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasgarPelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'pasgar_pelanggan';

    protected $fillable = [
        'regional_id', 'kode_pelanggan', 'nama_toko', 'nama_pemilik', 'no_hp', 'email',
        'alamat', 'kecamatan', 'kota', 'latitude', 'longitude', 'foto_toko',
        'tipe', 'status', 'limit_hutang',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'limit_hutang' => 'decimal:2',
    ];

    public function regional()
    {
        return $this->belongsTo(PasgarRegional::class, 'regional_id');
    }

    public function hutangs()
    {
        return $this->hasMany(PasgarHutang::class, 'pelanggan_id');
    }

    public function penjualans()
    {
        return $this->hasMany(PasgarPenjualan::class, 'pelanggan_id');
    }

    /**
     * Get total outstanding hutang (confirmed payments only).
     */
    public function getTotalHutangAttribute(): float
    {
        return (float) $this->hutangs()
            ->where('status', '!=', 'lunas')
            ->sum('sisa');
    }

    /**
     * Get remaining credit limit (limit_hutang - total outstanding).
     */
    public function getSisaLimitAttribute(): float
    {
        return max(0, (float) ($this->limit_hutang ?? 0) - $this->total_hutang);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public static function generateKode()
    {
        $prefix = 'PPG'; // Pelanggan Pasukan Garuda
        $date = date('Ymd');
        $last = self::where('kode_pelanggan', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_pelanggan', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}001";
        }

        $lastNum = (int) substr($last->kode_pelanggan, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}
