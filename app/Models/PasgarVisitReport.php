<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasgarVisitReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'pasgar_member_id',
        'customer_id',
        'visit_date',
        'status',
        'order_amount',
        'collection_amount',
        'notes',
    ];

    protected $casts = [
        'visit_date'        => 'date',
        'order_amount'      => 'decimal:2',
        'collection_amount' => 'decimal:2',
    ];

    public function schedule()
    {
        return $this->belongsTo(PasgarVisitSchedule::class, 'schedule_id');
    }

    public function member()
    {
        return $this->belongsTo(PasgarMember::class, 'pasgar_member_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'order'     => 'Ada Order',
            'no_order'  => 'Tanpa Order',
            'closed'    => 'Toko Tutup',
            'not_found' => 'Tidak Ditemukan',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'order'     => 'badge-success',
            'no_order'  => 'badge-indigo',
            'closed'    => 'badge-danger',
            'not_found' => 'badge-danger',
            default     => 'badge-indigo',
        };
    }
}
