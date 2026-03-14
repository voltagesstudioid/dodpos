<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralWarehouseStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'qty_dus',
    ];

    public function product()
    {
        return $this->belongsTo(MineralProduct::class, 'product_id');
    }
}
