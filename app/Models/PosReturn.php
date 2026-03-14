<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReturn extends Model
{
    protected $fillable = [
        'return_number',
        'transaction_id',
        'customer_id',
        'user_id',
        'return_date',
        'refund_method',
        'refund_reference',
        'refund_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'refund_amount' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PosReturnItem::class);
    }

    public static function generateNumber(): string
    {
        $prefix = 'RT-POS-'.now()->format('Ymd').'-';
        $last = static::query()
            ->where('return_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $next = 1;
        if ($last) {
            $parts = explode('-', (string) $last->return_number);
            $seq = (int) end($parts);
            $next = $seq + 1;
        }

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
