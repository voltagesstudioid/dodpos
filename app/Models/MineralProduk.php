<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MineralProduk extends Model
{
    use SoftDeletes;

    protected $table = 'mineral_produk';

    protected $fillable = [
        'kode_produk', 'nama', 'jenis', 'satuan', 'satuan_id', 'harga_jual', 'harga_modal',
        'stok_gudang', 'stok_minimum', 'keterangan', 'status',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'harga_modal' => 'decimal:2',
    ];

    public function satuanRel()
    {
        return $this->belongsTo(MineralSatuan::class, 'satuan_id');
    }

    public function vehicleStocks()
    {
        return $this->hasMany(VehicleStock::class, 'produk_id');
    }

    public function loadings()
    {
        return $this->hasMany(MineralLoading::class, 'produk_id');
    }

    public function penjualans()
    {
        return $this->hasMany(MineralPenjualan::class, 'produk_id');
    }

    public function regionalHarga()
    {
        return $this->hasMany(MineralRegionalHarga::class, 'produk_id');
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
        $prefix = 'PRM';
        $date = date('Ymd');
        $last = self::where('kode_produk', 'like', "{$prefix}{$date}%")
            ->lockForUpdate()
            ->orderBy('kode_produk', 'desc')
            ->first();
        
        if (!$last) {
            return "{$prefix}{$date}001";
        }
        
        $lastNum = (int) substr($last->kode_produk, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }

    public function recalculateStokGudang(): void
    {
        $total = (float) VehicleStock::where('produk_id', $this->id)->sum('jumlah');
        self::withoutEvents(fn () => $this->update(['stok_gudang' => $total]));
    }

    public static function recalculateAllStokGudang(): void
    {
        foreach (self::all() as $produk) {
            $produk->recalculateStokGudang();
        }
    }
}
