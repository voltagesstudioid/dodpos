<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarPenjualan extends Model
{
    protected $table = 'pasgar_penjualans';

    protected $fillable = [
        'nomor_transaksi',
        'loading_id',
        'sales_id',
        'pelanggan_id',
        'nama_pelanggan',
        'telepon_pelanggan',
        'alamat_pelanggan',
        'tanggal',
        'total',
        'uang_muka',
        'metode_bayar',
        'id_transaksi_transfer',
        'foto_bukti_transfer',
        'latitude',
        'longitude',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total' => 'decimal:2',
        'uang_muka' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function loading()
    {
        return $this->belongsTo(PasgarLoading::class, 'loading_id');
    }

    public function sales()
    {
        return $this->belongsTo(PasgarSales::class, 'sales_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(PasgarPelanggan::class, 'pelanggan_id');
    }

    public function items()
    {
        return $this->hasMany(PasgarPenjualanItem::class, 'penjualan_id');
    }

    public function hutang()
    {
        return $this->hasOne(PasgarHutang::class, 'penjualan_id');
    }

    public static function generateNomor()
    {
        $prefix = 'TRX-PSG-';
        $date = date('Ymd');
        $last = self::where('nomor_transaksi', 'like', "{$prefix}{$date}-%")
            ->orderBy('nomor_transaksi', 'desc')
            ->first();
        
        if (!$last) {
            return "{$prefix}{$date}-0001";
        }
        
        $parts = explode('-', $last->nomor_transaksi);
        $lastNum = (int) end($parts);
        return "{$prefix}{$date}-" . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
    }
}
