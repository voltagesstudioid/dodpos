<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transformasi data pelanggan untuk API response.
 */
class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'phone'                 => $this->phone,
            'address'               => $this->address,
            'credit_limit'          => (float) ($this->credit_limit ?? 0),
            'current_debt'          => (float) ($this->current_debt ?? 0),
            'remaining_credit_limit'=> (float) ($this->remaining_credit_limit ?? 0),
            'is_active'             => (bool) ($this->active ?? true),
        ];
    }
}
