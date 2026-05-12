<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class DashboardController extends Controller
{
    /*
     * Display the dashboard with reports
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        $isCustomer = $user->role === 'customer';
        $customerId = $isCustomer ? ($user->customer->id ?? -1) : null;

        $ordersByStatus = [
            'pending' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'cancelled' => 0,
        ];

        // Optimize Status Counts
        $statusQuery = Order::query();
        if ($isCustomer) {
            $statusQuery->where('customer_id', $customerId);
        }
        
        $statusCounts = $statusQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        
        foreach ($statusCounts as $status => $count) {
            if (isset($ordersByStatus[$status])) {
                $ordersByStatus[$status] = $count;
            }
        }

        $totalOrders = array_sum($ordersByStatus);

        // Recent Orders with Eager Loading
        $recentOrdersQuery = Order::with(['customer', 'products']);
        if ($isCustomer) {
            $recentOrdersQuery->where('customer_id', $customerId);
        }
        $recentOrders = $recentOrdersQuery->orderBy('created_at', 'desc')->limit(5)->get();

        // Chart Data (Last 7 Days) - Optimized
        $last7Days = collect(range(6, 0))->map(fn($d) => now()->subDays($d));
        $dayLabels = $last7Days->map(fn($day) => $day->format('D'));
        
        $chartDataQuery = Order::query();
        if ($isCustomer) {
            $chartDataQuery->where('customer_id', $customerId);
        }
        
        $ordersPerDayRaw = $chartDataQuery->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $ordersPerDay = $last7Days->map(function($day) use ($ordersPerDayRaw) {
            return $ordersPerDayRaw->get($day->format('Y-m-d'), 0);
        });

        if ($isCustomer) {
            $totalSpent = \Illuminate\Support\Facades\DB::table('order_product')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->where('orders.customer_id', $customerId)
                ->where('orders.status', '!=', 'cancelled')
                ->sum(\Illuminate\Support\Facades\DB::raw('order_product.quantity * products.price'));

            return view('dashboard', compact('totalOrders', 'ordersByStatus', 'recentOrders', 'totalSpent', 'dayLabels', 'ordersPerDay'));
        }

        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        $totalRevenue = \Illuminate\Support\Facades\DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('orders.status', '!=', 'cancelled')
            ->sum(\Illuminate\Support\Facades\DB::raw('order_product.quantity * products.price'));

        // Top Selling Products
        $topProducts = Product::select('products.*')
            ->selectRaw('SUM(order_product.quantity) as total_sold')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Recent Activity Logs
        $recentActivity = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'totalOrders', 
            'ordersByStatus', 
            'recentOrders', 
            'totalCustomers', 
            'totalProducts', 
            'totalRevenue', 
            'dayLabels', 
            'ordersPerDay',
            'topProducts',
            'recentActivity'
        ));
    }
}
