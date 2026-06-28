<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralLoading extends Model
{
    protected $table = 'mineral_loading';

    protected $fillable = [
        'tanggal', 'sales_id', 'vehicle_inti_id', 'vehicle_sub_id', 'produk_id',
        'jumlah_loading', 'sisa_stok', 'terjual', 'status', 'keterangan', 'created_by',
        'status_approval', 'approved_by', 'approved_at', 'alasan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
        'jumlah_loading' => 'decimal:2',
        'sisa_stok' => 'decimal:2',
        'terjual' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->belongsTo(MineralSales::class, 'sales_id');
    }

    public function vehicleInti()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_inti_id');
    }

    public function vehicleSub()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_sub_id');
    }

    public function produk()
    {
        return $this->belongsTo(MineralProduk::class, 'produk_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getVehicleIntiNameAttribute(): ?string
    {
        return $this->vehicleInti?->license_plate;
    }

    public function getVehicleSubNameAttribute(): ?string
    {
        return $this->vehicleSub?->license_plate;
    }
}
