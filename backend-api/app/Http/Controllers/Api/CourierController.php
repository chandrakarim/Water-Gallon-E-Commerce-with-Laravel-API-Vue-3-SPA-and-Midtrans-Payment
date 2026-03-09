<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courier\DeliverOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Payment;
use App\Services\DeliveryService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourierController extends Controller
{
    /**
     * List order milik kurir yang login
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::with(['user', 'address', 'items.product'])
            ->where('courier_id', $user->id)
            ->whereIn('status', ['ASSIGNED', 'PICKED_UP'])
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }

    public function dashboard(Request $request)
    {
        $courierId = $request->user()->id;

        $assigned = Order::where('courier_id', $courierId)
            ->where('status', 'ASSIGNED')->count();

        $onDelivery = Order::where('courier_id', $courierId)
            ->where('status', 'PICKED_UP')->count();

        $deliveredToday = Order::where('courier_id', $courierId)
            ->where('status', 'DELIVERED')
            ->whereDate('updated_at', now())->count();

        return ApiResponse::success([
            'assigned' => $assigned,
            'on_delivery' => $onDelivery,
            'delivered_today' => $deliveredToday,
        ]);
    }

    public function show(Order $order)
    {
        // Load relasi yang diperlukan
        $order->load(['user', 'address', 'items.product', 'courier', 'delivery']);
        //dd($order);
        // Kembalikan sebagai resource tunggal
        return ApiResponse::success(
            new OrderResource($order)
        );
    }

    /**
     * Kurir mengambil barang
     */
    public function pickup(Order $order, OrderService $service)
    {
        $this->authorize('pickup', $order);

        $order = $service->pickup($order);

        return ApiResponse::success(
            new OrderResource($order),
            'Kurir mengambil barang'
        );
    }

    /**
     * Kurir menyelesaikan pengantaran
     */
    public function deliver(DeliverOrderRequest $request, Order $order, OrderService $service)
    {
        $this->authorize('deliver', $order);

        $photoPath = null;

        if ($request->hasFile('delivery_photo')) {
            $photoPath = $request->file('delivery_photo')
                ->store('delivery_photos', 'public');
        }
        //dd($request->all(), $request->file('delivery_photo'));

        $order = $service->markAsDelivered(
            $order,
            $photoPath,
            $request->notes
        );

        return ApiResponse::success(
            new OrderResource($order->fresh(['delivery'])),
            'Pesanan selesai diantar'
        );
    }

    public function history(Request $request)
    {
        $courierId = $request->user()->id;

        $orders = Order::with([
            'user',
            'address',
            'items.product',
            'delivery'
        ])
            ->where('courier_id', $courierId)
            ->where('status', 'DELIVERED')
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }
}
