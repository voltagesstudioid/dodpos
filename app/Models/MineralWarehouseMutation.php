<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralWarehouseMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type', // 'in', 'out_damage', 'out_loading', 'in_return'
        'qty_dus',
        'user_id',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(MineralProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
