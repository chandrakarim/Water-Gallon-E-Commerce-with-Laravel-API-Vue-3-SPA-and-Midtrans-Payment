<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'courier_id' => $this->courier_id,
            'picked_at' => $this->picked_at,
            'delivered_at' => $this->delivered_at,
            'delivery_photo' => $this->delivery_photo 
                ? asset('storage/' . $this->delivery_photo)
                : null,
            'notes' => $this->notes,
        ];
    }
}