<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakPenjualan extends Model
{
    protected $table = 'minyak_penjualan';

    protected $fillable = [
        'no_faktur', 'tanggal_jual', 'sales_id', 'pelanggan_id', 'produk_id',
        'jumlah', 'harga_satuan', 'total', 'tipe_bayar', 'bayar', 'kembali', 'hutang',
        'latitude', 'longitude', 'foto_nota', 'keterangan', 'status',
        'verified_by', 'verified_at',
    ];

    protected $casts = [
        'tanggal_jual' => 'datetime',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
        'bayar' => 'decimal:2',
        'kembali' => 'decimal:2',
        'hutang' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'verified_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsTo(MinyakSales::class, 'sales_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(MinyakPelanggan::class, 'pelanggan_id');
    }

    public function produk()
    {
        return $this->belongsTo(MinyakProduk::class, 'produk_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function hutang()
    {
        return $this->hasOne(MinyakHutang::class, 'penjualan_id');
    }

    public static function generateFaktur()
    {
        $prefix = 'FKT';
        $date = date('Ymd');
        $last = self::where('no_faktur', 'like', "{$prefix}{$date}%")
            ->orderBy('no_faktur', 'desc')
            ->first();
        
        if (!$last) {
            return "{$prefix}{$date}0001";
        }
        
        $lastNum = (int) substr($last->no_faktur, -4);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
    }
}
