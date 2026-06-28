<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['license_plate', 'type', 'capacity', 'status', 'description', 'warehouse_id', 'sales_type', 'sales_id'];

    protected $casts = [
        'capacity' => 'decimal:2',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function expenses()
    {
        return $this->hasMany(OperationalExpense::class, 'vehicle_id');
    }

    public function stocks()
    {
        return $this->hasMany(VehicleStock::class, 'vehicle_id');
    }

    public function assignments()
    {
        return $this->hasMany(VehicleAssignment::class, 'vehicle_id');
    }

    public function currentAssignment()
    {
        return $this->hasOne(VehicleAssignment::class, 'vehicle_id')
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', now());
            });
    }

    public function currentDriver()
    {
        return $this->hasOne(VehicleAssignment::class, 'vehicle_id')
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', now());
            })
            ->with('sales');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->whereIn('status', ['aktif', 'standby']);
    }

    public function getTotalStokAttribute(): float
    {
        return (float) $this->stocks()->sum('jumlah');
    }

    public function getSisaKapasitasAttribute(): float
    {
        return max(0, (float) $this->capacity - $this->total_stok);
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

    public function getDriverNameAttribute(): ?string
    {
        $assignment = $this->currentDriver;
        return $assignment?->sales?->nama;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->currentDriver?->role;
    }
}
