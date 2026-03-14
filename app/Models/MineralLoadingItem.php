<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralLoadingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'loading_id',
        'product_id',
        'qty_dus',
    ];

    public function loading()
    {
        return $this->belongsTo(MineralLoading::class, 'loading_id');
    }

    public function product()
    {
        return $this->belongsTo(MineralProduct::class, 'product_id');
    }
}
