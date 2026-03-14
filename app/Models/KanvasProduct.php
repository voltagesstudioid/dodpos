<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price_cash' => 'decimal:2',
        'price_tempo' => 'decimal:2',
    ];

    public function warehouseStocks()
    {
        return $this->hasMany(KanvasWarehouseStock::class, 'product_id');
    }

    public function vehicleStocks()
    {
        return $this->hasMany(KanvasVehicleStock::class, 'product_id');
    }

    public function loadingItems()
    {
        return $this->hasMany(KanvasLoadingItem::class, 'product_id');
    }
}
