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
    public function index()
    {
        // Total number of orders
        $totalOrders = Order::count();

        // Orders by status
        $ordersByStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // 5 most recent orders
        $recentOrders = Order::with(['customer', 'products'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        // Total customers
        $totalCustomers = Customer::count();

        // Total products
        $totalProducts = Product::count();

        return view('dashboard', compact('totalOrders', 'ordersByStatus', 'recentOrders', 'totalCustomers', 'totalProducts'));
    }
}
