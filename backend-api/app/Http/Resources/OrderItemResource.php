<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'product_id' => $this->product_id,
            // AMBIL DARI SNAPSHOT
            'name' => $this->product_name_snapshot,
            'price' => (int) $this->price_snapshot,
            'qty' => (int) $this->quantity,
            'subtotal' => (int) $this->subtotal,
        ];
    }
}
