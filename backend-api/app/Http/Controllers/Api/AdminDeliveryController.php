<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryResource;
use App\Models\Order;

class AdminDeliveryController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'courier', 'address', 'delivery'])
            ->whereIn('status', [
                Order::STATUS_ASSIGNED,
                Order::STATUS_PICKED_UP,
                Order::STATUS_DELIVERED,
            ])
            ->latest()
            ->get();

        return ApiResponse::success(
            $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'invoice_number' => $order->invoice_number,

                    'customer_name' => $order->user?->name,
                    'courier_name' => $order->courier?->name,
                    'courier_phone' => $order->courier?->phone,

                    'address' => $order->address?->full_address,

                    'status' => $order->status,
                    'status_label' => $order->status_label,

                    'delivery' => $order->delivery
                        ? new DeliveryResource($order->delivery)
                        : null,
                ];
            }),
            'List deliveries berhasil diambil'
        );
    }


    public function show(Order $order)
    {
        $order->load([
            'user',
            'courier',
            'address',
            'items.product',
            'delivery',
            'histories'
        ]);

        return ApiResponse::success(
            [
                'id' => $order->id,
                'invoice_number' => $order->invoice_number,

                // CUSTOMER
                'customer_name' => $order->user?->name,
                'customer_phone' => $order->user?->phone,
                'customer_email' => $order->user?->email,

                // COURIER
                'courier_name' => $order->courier?->name,
                'courier_phone' => $order->courier?->phone,

                // ADDRESS
                'address' => $order->address?->full_address,

                // ORDER STATUS
                'status' => $order->status,
                'status_label' => $order->status_label,

                // PAYMENT
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,

                // ITEMS
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->product?->name,
                        'qty' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->quantity * $item->price,
                    ];
                }),

                // TOTAL
                'total_price' => $order->total_price,

                // DELIVERY
                'delivery' => $order->delivery
                    ? new DeliveryResource($order->delivery)
                    : null,

                // HISTORY
                'histories' => $order->histories
            ],
            'Detail delivery berhasil diambil'
        );
    }
}
