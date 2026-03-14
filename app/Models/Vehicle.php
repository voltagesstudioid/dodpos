<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['license_plate', 'type', 'description', 'warehouse_id'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function expenses()
    {
        return $this->hasMany(OperationalExpense::class, 'vehicle_id');
    }
}
