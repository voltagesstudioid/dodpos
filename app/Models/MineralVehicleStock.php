<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralVehicleStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'product_id',
        'initial_qty',
        'sold_qty',
        'leftover_qty',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(MineralProduct::class, 'product_id');
    }
}
