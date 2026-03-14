<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasgarVisitSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasgar_member_id',
        'customer_id',
        'scheduled_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(PasgarMember::class, 'pasgar_member_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function report()
    {
        return $this->hasOne(PasgarVisitReport::class, 'schedule_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'Terjadwal',
            'visited'   => 'Sudah Dikunjungi',
            'skipped'   => 'Dilewati',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'badge-indigo',
            'visited'   => 'badge-success',
            'skipped'   => 'badge-danger',
            default     => 'badge-indigo',
        };
    }
}
