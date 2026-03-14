<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasVehicleStock extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(KanvasProduct::class, 'product_id');
    }
}
