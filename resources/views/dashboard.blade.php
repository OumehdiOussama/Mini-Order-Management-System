@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Dashboard</h1>
        <p class="text-gray-600">Welcome to the Order Management System</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h3 class="text-gray-600 text-sm font-semibold">Total Orders</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h3 class="text-gray-600 text-sm font-semibold">Total Customers</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalCustomers }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
            <h3 class="text-gray-600 text-sm font-semibold">Total Products</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-orange-500">
            <h3 class="text-gray-600 text-sm font-semibold">Delivered Orders</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $ordersByStatus['delivered'] }}</p>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Orders by Status</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'processing' => 'bg-blue-100 text-blue-800',
                    'shipped' => 'bg-purple-100 text-purple-800',
                    'delivered' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                ];
            @endphp
            @foreach($ordersByStatus as $status => $count)
                <div class="p-4 {{ $statusColors[$status] }} rounded-lg text-center">
                    <p class="text-2xl font-bold">{{ $count }}</p>
                    <p class="text-sm capitalize font-semibold">{{ $status }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Most Recent Orders</h2>
            <a href="{{ route('orders.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">View All Orders</a>
        </div>
        
        @if($recentOrders->isEmpty())
            <p class="text-gray-600">No orders found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Products</th>
                            <th class="px-4 py-2 text-left">Total Price</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a>
                                </td>
                                <td class="px-4 py-2">{{ $order->customer->name }}</td>
                                <td class="px-4 py-2">{{ $order->products->count() }} items</td>
                                <td class="px-4 py-2 font-semibold">{{ number_format($order->getTotalPrice(), 2) }} MAD</td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusBg = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-sm font-semibold {{ $statusBg[$order->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
