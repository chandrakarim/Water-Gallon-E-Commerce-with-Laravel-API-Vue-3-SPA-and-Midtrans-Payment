<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalQty = 0;
        $totalPrice = 0;

        $items = $this->items->map(function ($item) use (&$totalQty, &$totalPrice) {

            $subtotal = $item->qty * $item->product->price;

            $totalQty += $item->qty;
            $totalPrice += $subtotal;

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (int) $item->product->price,
                'qty' => $item->qty,
                'subtotal' => $subtotal,
                'image' => $item->product->image,
                'image_url' => $item->product->image
                    ? url('storage/' . $item->product->image)
                    : null
            ];
        });

        return [
            'items' => $items,
            'total_qty' => $totalQty,
            'total_price' => $totalPrice
        ];
    }
}
