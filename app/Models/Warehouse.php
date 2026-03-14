<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name', 'code', 'address', 'phone', 'pic', 'description', 'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'warehouse_id');
    }
}
