<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderHistory;

class OrderHistoryService
{
    public function addHistory(Order $order, string $status): OrderHistory
    {
        return $order->histories()->create([
            'status' => $status
        ]);
    }

    public function getOrderHistory(Order $order)
    {
        return $order->histories()->orderBy('created_at')->get();
    }
}