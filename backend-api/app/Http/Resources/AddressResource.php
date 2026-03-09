<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,

            'recipient_name' => $this->recipient_name,
            'phone' => $this->phone,

            // alamat gabungan
            'address' => $this->full_address,

            // data detail tetap dikirim (penting buat edit form / maps)
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'detail' => $this->detail,

            'note' => $this->note,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
