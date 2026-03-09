<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'address', 'items.product', 'courier'])
            ->latest()
            ->get();

        return ApiResponse::success(OrderResource::collection($orders));
    }

    public function show(Order $order)
    {
        // Load relasi yang diperlukan
        $order->load(['user', 'address', 'items.product', 'courier']);
        //dd($order);
        // Kembalikan sebagai resource tunggal
        return ApiResponse::success(
            new OrderResource($order)
        );
    }

    public function assign(Request $request, Order $order, OrderService $service)
    {
        $this->authorize('assign', $order);

        $request->validate([
            'courier_id' => 'required|exists:users,id'
        ]);
        //dd($request);
        $order = $service->assignCourier(
            $order,
            $request->courier_id
        );

        $order->load(['user', 'address', 'items.product', 'delivery']);

        return ApiResponse::success(
            new OrderResource($order),
            'Kurir berhasil ditugaskan'
        );
    }
}
