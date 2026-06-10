<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinyakProduk extends Model
{
    use SoftDeletes;

    protected $table = 'minyak_produk';

    protected $fillable = [
        'kode_produk', 'nama', 'jenis', 'satuan', 'harga_jual', 'harga_modal',
        'stok_gudang', 'stok_minimum', 'keterangan', 'status',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'harga_modal' => 'decimal:2',
    ];

    public function loadings()
    {
        return $this->hasMany(MinyakLoading::class, 'produk_id');
    }

    public function penjualans()
    {
        return $this->hasMany(MinyakPenjualan::class, 'produk_id');
    }

    public function regionalHarga()
    {
        return $this->hasMany(MinyakRegionalHarga::class, 'produk_id');
    }

    /**
     * Get harga jual for a specific regional.
     * Falls back to default harga_jual if not set for regional.
     */
    public function getHargaForRegional(?int $regionalId): float
    {
        if (!$regionalId) {
            return (float) $this->harga_jual;
        }

        $harga = $this->regionalHarga()->where('regional_id', $regionalId)->first();
        if ($harga && $harga->harga_jual > 0) {
            return (float) $harga->harga_jual;
        }

        return (float) $this->harga_jual;
    }

    public static function generateKode()
    {
        $prefix = 'PRD';
        $date = date('Ymd');
        $last = self::where('kode_produk', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_produk', 'desc')
            ->first();
        
        if (!$last) {
            return "{$prefix}{$date}001";
        }
        
        $lastNum = (int) substr($last->kode_produk, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}
