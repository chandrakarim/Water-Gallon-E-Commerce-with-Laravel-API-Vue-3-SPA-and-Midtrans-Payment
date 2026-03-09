<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $range = $request->range ?? 7;

        $startDate = now()->subDays($range);

        // SUMMARY
        $summary = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'revenue' => Order::where('payment_status', 'PAID')->sum('total_price'),
            'pending_orders' => Order::whereIn('status', [
                Order::STATUS_WAITING_PAYMENT,
                Order::STATUS_WAITING_ASSIGN,
            ])->count(),
            'delivered_orders' => Order::where('status', Order::STATUS_DELIVERED)->count(),
        ];

        //Revenue Line Chart
        $revenueChart = Order::where('payment_status', 'PAID')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        //Orders Bar Chart
        $ordersChart = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        //Status Doughnut Chart
        $statusChart = [
            'pending' => Order::whereIn('status', [
                Order::STATUS_WAITING_PAYMENT,
                Order::STATUS_WAITING_ASSIGN,
            ])->count(),
            'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled' => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        // LATEST ORDERS
        $latestOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'code' => $order->invoice_number ?? 'INV-' . $order->id,
                    'customer' => $order->user?->name ?? '-',
                    'total' => $order->total_price,
                    'status' => $order->status_label,
                    'raw_status' => $order->status,
                    'date' => $order->created_at->format('Y-m-d'),
                ];
            });

        return ApiResponse::success([
            'summary' => $summary,
            'revenue_chart' => $revenueChart,
            'orders_chart' => $ordersChart,
            'status_chart' => $statusChart,
            'latest_orders' => $latestOrders,
        ], 'Dashboard loaded');
    }
}
