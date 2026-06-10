<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralRegionalHarga extends Model
{
    protected $table = 'mineral_regional_harga';

    protected $fillable = [
        'regional_id',
        'produk_id',
        'harga_jual',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
    ];

    public function regional()
    {
        return $this->belongsTo(MineralRegional::class, 'regional_id');
    }

    public function produk()
    {
        return $this->belongsTo(MineralProduk::class, 'produk_id');
    }
}
