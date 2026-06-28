<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VehicleAssignment extends Model
{
    protected $table = 'vehicle_assignments';

    protected $fillable = [
        'vehicle_id',
        'sales_id',
        'role',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function sales()
    {
        return $this->belongsTo(MineralSales::class, 'sales_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInti(Builder $query): Builder
    {
        return $query->where('role', 'inti');
    }

    public function scopeSub(Builder $query): Builder
    {
        return $query->where('role', 'sub');
    }

    public function scopeForDate(Builder $query, $date): Builder
    {
        $date = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        return $query->where('tanggal_mulai', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $date);
            });
    }

    public static function isVehicleAvailable(int $vehicleId, $tanggal, ?int $excludeId = null): bool
    {
        $query = self::where('vehicle_id', $vehicleId)
            ->where('status', 'aktif')
            ->forDate($tanggal);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    public static function isSalesAvailable(int $salesId, string $role, $tanggal, ?int $excludeId = null): bool
    {
        $query = self::where('sales_id', $salesId)
            ->where('role', $role)
            ->where('status', 'aktif')
            ->forDate($tanggal);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    public static function getActiveAssignment(int $vehicleId, ?string $role = null): ?self
    {
        $query = self::where('vehicle_id', $vehicleId)
            ->where('status', 'aktif')
            ->forDate(now());

        if ($role) {
            $query->where('role', $role);
        }

        return $query->first();
    }
}
