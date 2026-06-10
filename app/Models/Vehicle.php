<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['license_plate', 'type', 'description', 'warehouse_id', 'sales_type', 'sales_id'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function expenses()
    {
        return $this->hasMany(OperationalExpense::class, 'vehicle_id');
    }

    /**
     * Polymorphic: linked sales profile (GulaSales | MineralSales | MinyakSales | PasgarSales)
     */
    public function sales()
    {
        return $this->morphTo();
    }

    /**
     * Human-readable module label for sales_type.
     */
    public function getSalesModuleLabel(): string
    {
        return match ($this->sales_type) {
            GulaSales::class    => 'Gula',
            MineralSales::class => 'Mineral',
            MinyakSales::class  => 'Minyak',
            PasgarSales::class  => 'Pasgar',
            default             => '—',
        };
    }
}
