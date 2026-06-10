<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakRegionalHarga extends Model
{
    protected $table = 'minyak_regional_harga';

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
        return $this->belongsTo(MinyakRegional::class, 'regional_id');
    }

    public function produk()
    {
        return $this->belongsTo(MinyakProduk::class, 'produk_id');
    }
}
