<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['warehouse_id', 'code', 'name', 'description'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
