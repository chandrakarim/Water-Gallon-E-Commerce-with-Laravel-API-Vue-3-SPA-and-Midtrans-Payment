<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,

            'status' => [
                'order' => $this->status,
                'payment' => $this->payment_status,
                'delivery' => $this->delivery
                    ? ($this->delivery->delivered_at ? 'DELIVERED' : 'ON_DELIVERY')
                    : 'NOT_ASSIGNED'
            ],
            'status_label' => $this->status_label,
            'payment' => [
                'method' => $this->payment_method,
                'snap_token' => $this->snap_token,
                'payment_url' => $this->snap_token
                    ? "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $this->snap_token
                    : null,
                'va_number' => $this->midtrans_payload['va_numbers'][0]['va_number'] ?? null,
                'bank' => $this->midtrans_payload['va_numbers'][0]['bank'] ?? null,
                'paid_at' => $this->paid_at,
            ],
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'name' => $item->product->name ?? null,
                        'image' => $item->product->image,
                        'image_url' => $item->product->image
                            ? url('storage/' . $item->product->image)
                            : null,
                        'qty' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->quantity * $item->price,
                    ];
                });
            }),
            'courier' => $this->whenLoaded('courier', function () {
                return [
                    'id' => $this->courier->id,
                    'name' => $this->courier->name,
                    'email' => $this->courier->email,
                    'phone' => $this->courier->phone,
                ];
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'phone' => $this->user->phone,
                ];
            }),

            'address' => $this->whenLoaded('address', function () {
                return [
                    'id' => $this->address->id,

                    'recipient_name' => $this->address->recipient_name,
                    'phone' => $this->address->phone,

                    'detail' => $this->address->detail,
                    'district' => $this->address->district,
                    'city' => $this->address->city,
                    'province' => $this->address->province,

                    'note' => $this->address->note,

                    // alamat lengkap siap pakai frontend
                    'full_address' =>
                    $this->address->detail . ', ' .
                        $this->address->district . ', ' .
                        $this->address->city . ', ' .
                        $this->address->province,
                ];
            }),

            'delivery' => $this->whenLoaded('delivery', function () {
                return new DeliveryResource($this->delivery);
            }),

            'total_price' => $this->total_price,
            'ordered_at' => $this->ordered_at,
        ];
    }
}
