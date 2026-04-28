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

        // Customer Dashboard
        if ($user->role === 'customer') {
            $customer = $user->customer;
            
            $totalOrders = $customer ? $customer->orders()->count() : 0;
            $ordersByStatus = [
                'pending' => $customer ? $customer->orders()->where('status', 'pending')->count() : 0,
                'processing' => $customer ? $customer->orders()->where('status', 'processing')->count() : 0,
                'shipped' => $customer ? $customer->orders()->where('status', 'shipped')->count() : 0,
                'delivered' => $customer ? $customer->orders()->where('status', 'delivered')->count() : 0,
                'cancelled' => $customer ? $customer->orders()->where('status', 'cancelled')->count() : 0,
            ];
            $recentOrders = $customer ? $customer->orders()->with(['products'])->orderBy('created_at', 'desc')->limit(5)->get() : collect();
            
            return view('dashboard', compact('totalOrders', 'ordersByStatus', 'recentOrders'));
        }

        // Admin and Staff Dashboard
        $totalOrders = Order::count();

        $ordersByStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $recentOrders = Order::with(['customer', 'products'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        return view('dashboard', compact('totalOrders', 'ordersByStatus', 'recentOrders', 'totalCustomers', 'totalProducts'));
    }
}
