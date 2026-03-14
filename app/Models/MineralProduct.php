<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_cash',
        'price_tempo',
        'is_active',
    ];

    protected $casts = [
        'price_cash' => 'decimal:2',
        'price_tempo' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function warehouseStocks()
    {
        return $this->hasMany(MineralWarehouseStock::class, 'product_id');
    }

    public function vehicleStocks()
    {
        return $this->hasMany(MineralVehicleStock::class, 'product_id');
    }
}
