<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaVehicleStock extends Model
{
    protected $fillable = [
        'vehicle_id', 'sales_id', 'gula_product_id', 'qty_karung', 'qty_bal', 'qty_eceran'
    ];

    public function vehicle() { return $this->belongsTo(Vehicle::class, 'vehicle_id'); }
    public function sales() { return $this->belongsTo(User::class, 'sales_id'); }
    public function product() { return $this->belongsTo(GulaProduct::class, 'gula_product_id'); }
}
