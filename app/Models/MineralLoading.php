<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralLoading extends Model
{
    protected $table = 'mineral_loading';

    protected $fillable = [
        'tanggal', 'sales_id', 'produk_id', 'jumlah_loading', 'sisa_stok',
        'terjual', 'status', 'keterangan', 'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function sales()
    {
        return $this->belongsTo(MineralSales::class, 'sales_id');
    }

    public function produk()
    {
        return $this->belongsTo(MineralProduk::class, 'produk_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
