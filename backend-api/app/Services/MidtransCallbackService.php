<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransCallbackService
{
    public function handle(array $data)
    {
        $serverKey = config('services.midtrans.server_key');

        $signature = hash(
            'sha512',
            $data['order_id'] .
                $data['status_code'] .
                $data['gross_amount'] .
                $serverKey
        );

        if (!isset($data['signature_key']) || $signature !== $data['signature_key']) {
            Log::error('Midtrans Signature INVALID', $data);
            return false;
        }

        $order = Order::where('midtrans_order_id', $data['order_id'])->first();

        if (!$order) {
            Log::error('Order tidak ditemukan dari Midtrans', $data);
            return false;
        }

        // 🔐 Cek nominal sesuai
        if ((int)$data['gross_amount'] !== (int)$order->total_price) {
            Log::error('Gross amount tidak sesuai', $data);
            return false;
        }

        $transactionStatus = $data['transaction_status'];

        // ⛔ Hindari double update
        if ($order->isPaid()) {
            return true;
        }

        // =========================
        // BERHASIL BAYAR
        // =========================
        if (in_array($transactionStatus, ['settlement', 'capture'])) {

            $order->update([
                'payment_status' => 'PAID',
                'status' => Order::STATUS_WAITING_ASSIGN,
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                'midtrans_payment_type' => $data['payment_type'] ?? null,
                'midtrans_payload' => $data,
                'paid_at' => now(),
            ]);

            Payment::where('order_id', $order->id)->update([
                'status' => 'PAID',
                'paid_at' => now(),
                'transaction_id' => $data['transaction_id'] ?? null,
                'payment_type' => $data['payment_type'] ?? null,
                'callback_payload' => json_encode($data),
            ]);

            return true;
        }

        // =========================
        // PENDING
        // =========================
        if ($transactionStatus === 'pending') {

            $order->update([
                'payment_status' => 'PENDING'
            ]);

            return true;
        }

        // =========================
        // GAGAL
        // =========================
        if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {

            $order->update([
                'payment_status' => 'FAILED',
                'status' => Order::STATUS_CANCELLED,
            ]);

            Payment::where('order_id', $order->id)->update([
                'status' => 'REJECTED'
            ]);

            return true;
        }

        return true;
    }
}
