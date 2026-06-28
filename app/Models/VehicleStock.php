<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleStock extends Model
{
    protected $table = 'vehicle_stocks';

    protected $fillable = [
        'vehicle_id', 'produk_id', 'jumlah',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function produk()
    {
        return $this->belongsTo(MineralProduk::class, 'produk_id');
    }
}
