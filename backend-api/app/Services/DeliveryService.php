<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DeliveryService
{
    public function deliver(Order $order, $photo, $notes = null): Order
    {
        return DB::transaction(function () use ($order, $photo, $notes) {

            // simpan foto
            $photoPath = $photo->store('deliveries', 'public');

            // simpan ke tabel deliveries
            Delivery::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier_id' => auth()->id(),
                    'delivered_at' => now(),
                    'delivery_photo' => $photoPath,
                    'notes' => $notes
                ]
            );

            // COD → pembayaran dicatat saat barang sampai
            if ($order->payment_method === 'COD') {

                $order->update([
                    'status' => 'DELIVERED',
                    'payment_status' => 'PAID',
                    'paid_at' => now(),
                ]);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'user_id' => $order->user_id,
                        'payment_method' => 'COD',
                        'status' => 'PAID',
                        'paid_at' => now(),
                        'gateway' => 'COD'
                    ]
                );

            } else {
                // Midtrans
                $order->update([
                    'status' => 'DELIVERED'
                ]);
            }

            return $order;
        });
    }
}