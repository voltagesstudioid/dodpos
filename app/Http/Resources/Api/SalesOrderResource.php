<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transformasi data Sales Order untuk API response.
 */
class SalesOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'so_number'      => $this->so_number,
            'order_date'     => $this->order_date?->toDateString(),
            'delivery_date'  => $this->delivery_date?->toDateString(),
            'status'         => $this->status,
            'status_label'   => $this->statusLabel(),
            'total_amount'   => (float) $this->total_amount,
            'notes'          => $this->notes,
            'customer'       => $this->when(
                $this->relationLoaded('customer'),
                fn() => [
                    'id'    => $this->customer?->id,
                    'name'  => $this->customer?->name,
                    'phone' => $this->customer?->phone,
                ]
            ),
            'items'          => $this->when(
                $this->relationLoaded('items'),
                fn() => SalesOrderItemResource::collection($this->items)
            ),
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * Label status dalam Bahasa Indonesia.
     */
    private function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->status ?? '-'),
        };
    }
}
