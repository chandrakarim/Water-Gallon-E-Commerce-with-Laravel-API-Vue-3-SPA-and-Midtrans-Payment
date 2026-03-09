<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Http\Resources\OrderHistoryResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderHistoryService;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderHistoryService $historyService;

    public function __construct(OrderHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.product', 'address', 'delivery', 'courier'])
            ->latest()
            ->get();

        return ApiResponse::success(OrderResource::collection($orders));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'address', 'delivery', 'courier']);

        return ApiResponse::success(new OrderResource($order));
    }

    public function checkout(CheckoutRequest $request, OrderService $service)
    {
        $order = $service->checkout(
            $request->user(),
            $request->address_id,
            $request->payment_method
        );

        return ApiResponse::success(new OrderResource($order), 'Order berhasil dibuat', 201);
    }

    // Tracking order
    public function tracking(Order $order)
    {
        $tracks = $order->tracks()
            ->orderBy('created_at')
            ->get();

        $timeline = [];

        foreach ($tracks as $track) {

            $status = $track->status;

            $timeline[] = [
                'status' => $status,
                'label' => (new Order(['status' => $status]))->status_label,
                'courier' => $status === Order::STATUS_ASSIGNED ? $order->courier?->name : null,
                'time' => $track->created_at->format('d M Y H:i')
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $timeline
        ]);
    }
}
