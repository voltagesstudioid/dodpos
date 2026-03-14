<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'product_id' => $this->product_id,
            'product'    => $this->when(
                $this->relationLoaded('product'),
                fn() => [
                    'id'    => $this->product?->id,
                    'name'  => $this->product?->name,
                    'sku'   => $this->product?->sku,
                    'stock' => (int) ($this->product?->stock ?? 0),
                ]
            ),
            'quantity'   => (int) $this->quantity,
            'price'      => (float) $this->price,
            'subtotal'   => (float) $this->subtotal,
        ];
    }
}
