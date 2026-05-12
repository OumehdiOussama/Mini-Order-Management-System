<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardApiController extends Controller
{
    /**
     * Get real-time dashboard metrics as JSON.
     */
    public function metrics(Request $request)
    {
        $user = $request->user();
        $isCustomer = $user->role === 'customer';
        $customerId = $isCustomer ? ($user->customer->id ?? -1) : null;
        $cacheKey = "dashboard_metrics_" . $user->id;

        // Cache metrics for 2 minutes to keep response times < 50ms
        return Cache::remember($cacheKey, 120, function () use ($isCustomer, $customerId) {
            
            // Status Counts
            $statusQuery = Order::query();
            if ($isCustomer) $statusQuery->where('customer_id', $customerId);
            
            $statusCounts = $statusQuery->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $ordersByStatus = [
                'pending' => $statusCounts['pending'] ?? 0,
                'processing' => $statusCounts['processing'] ?? 0,
                'shipped' => $statusCounts['shipped'] ?? 0,
                'delivered' => $statusCounts['delivered'] ?? 0,
                'cancelled' => $statusCounts['cancelled'] ?? 0,
            ];

            $totalOrders = array_sum($ordersByStatus);

            // Revenue calculation (Optimized join)
            $revenueQuery = DB::table('order_product')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->where('orders.status', '!=', 'cancelled');

            if ($isCustomer) {
                $revenueQuery->where('orders.customer_id', $customerId);
            }

            $revenue = $revenueQuery->sum(DB::raw('order_product.quantity * products.price'));

            // Chart Data
            $last7Days = collect(range(6, 0))->map(fn($d) => now()->subDays($d));
            
            $chartQuery = Order::query();
            if ($isCustomer) $chartQuery->where('customer_id', $customerId);
            
            $ordersPerDayRaw = $chartQuery->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $chartData = $last7Days->map(function($day) use ($ordersPerDayRaw) {
                return [
                    'label' => $day->format('D'),
                    'count' => $ordersPerDayRaw->get($day->format('Y-m-d'), 0)
                ];
            });

            return [
                'success' => true,
                'totalOrders' => $totalOrders,
                'ordersByStatus' => $ordersByStatus,
                'revenue' => round($revenue, 2),
                'chart' => $chartData,
                'customers' => $isCustomer ? 0 : Customer::count(),
                'products' => $isCustomer ? 0 : Product::count(),
            ];
        });
    }
}
