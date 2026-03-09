<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        $config = config('services.midtrans');

        Config::$serverKey = $config['server_key'];
        Config::$clientKey = $config['client_key'];
        Config::$isProduction = $config['is_production'];
        Config::$isSanitized = $config['is_sanitized'];
        Config::$is3ds = $config['is_3ds'];
    }

    public function getSnapToken(Order $order): array
    {
        // WAJIB: gunakan UUID agar unik global
        $midtransOrderId = 'MG-' . Str::uuid();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            // 🔥 redirect setelah pembayaran selesai
            'callbacks' => [
                'finish' => config('app.frontend_url') . '/customer/orders'
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan ke DB agar callback bisa cocokkan
        $order->update([
            'midtrans_order_id' => $midtransOrderId,
            'snap_token' => $snapToken,
        ]);

        return [
            'midtrans_order_id' => $midtransOrderId,
            'snap_token' => $snapToken,
        ];
    }
}
