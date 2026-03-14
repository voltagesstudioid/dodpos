<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasgarDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_number',
        'pasgar_member_id',
        'deposit_date',
        'sales_amount',
        'collection_amount',
        'expense_amount',
        'total_amount',
        'status',
        'notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'deposit_date'  => 'date',
        'verified_at'   => 'datetime',
        'sales_amount'      => 'decimal:2',
        'collection_amount' => 'decimal:2',
        'expense_amount'    => 'decimal:2',
        'total_amount'      => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(PasgarMember::class, 'pasgar_member_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Generate unique deposit number: SET-YYYYMMDD-001
     */
    public static function generateNumber(): string
    {
        $prefix = 'SET-' . now()->format('Ymd') . '-';
        $last = self::where('deposit_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();
        $seq = $last ? (intval(substr($last->deposit_number, -3)) + 1) : 1;
        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'badge-indigo',
            'verified' => 'badge-success',
            'rejected' => 'badge-danger',
            default    => 'badge-indigo',
        };
    }
}
