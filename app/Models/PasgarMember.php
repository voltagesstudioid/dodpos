<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasgarMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'area',
        'active',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function deposits()
    {
        return $this->hasMany(PasgarDeposit::class);
    }

    public function visitSchedules()
    {
        return $this->hasMany(PasgarVisitSchedule::class);
    }

    public function visitReports()
    {
        return $this->hasMany(PasgarVisitReport::class);
    }

    /**
     * Stok on-hand: ambil dari gudang kendaraan yang ditautkan
     */
    public function stockOnHand()
    {
        if (!$this->vehicle || !$this->vehicle->warehouse_id) {
            return collect();
        }
        return ProductStock::with('product.unit')
            ->where('warehouse_id', $this->vehicle->warehouse_id)
            ->where('stock', '>', 0)
            ->get();
    }

    /**
     * Total penjualan kanvas hari ini
     */
    public function todaySales()
    {
        return SalesOrder::where('user_id', $this->user_id)
            ->whereDate('order_date', today())
            ->sum('total_amount');
    }
}
