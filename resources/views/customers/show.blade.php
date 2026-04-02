@extends('layouts.app')

@section('title', 'Customer Details - ' . $customer->name)

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Customer Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
            <a href="{{ route('customers.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Information -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Personal Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 text-sm">Full Name</p>
                        <p class="font-semibold text-lg">{{ $customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email Address</p>
                        <p class="font-semibold text-lg">
                            <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:underline">{{ $customer->email }}</a>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Phone Number</p>
                        <p class="font-semibold text-lg">
                            <a href="tel:{{ $customer->phone }}" class="text-blue-600 hover:underline">{{ $customer->phone }}</a>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Member Since</p>
                        <p class="font-semibold text-lg">{{ $customer->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Orders -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Order History ({{ $customer->orders->count() }} orders)</h2>
                
                @if($customer->orders->isEmpty())
                    <p class="text-gray-600">No orders yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Order ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Items</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Total</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Date</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->orders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline font-semibold">#{{ $order->id }}</a>
                                        </td>
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
                                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusBg[$order->status] ?? 'bg-gray-100' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline text-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Statistics</h3>
                <div class="space-y-4">
                    <div class="text-center p-3 bg-blue-50 rounded">
                        <p class="text-gray-600 text-sm">Total Orders</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $customer->orders->count() }}</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded">
                        <p class="text-gray-600 text-sm">Delivered Orders</p>
                        <p class="text-3xl font-bold text-green-600">{{ $customer->orders->where('status', 'delivered')->count() }}</p>
                    </div>
                    <div class="text-center p-3 bg-orange-50 rounded">
                        <p class="text-gray-600 text-sm">Total Spent</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($customer->orders->sum(function($order) { return $order->getTotalPrice(); }), 2) }} MAD</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded">
                        <p class="text-gray-600 text-sm">Pending Orders</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $customer->orders->whereIn('status', ['pending', 'processing'])->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('orders.create') }}" class="w-full block text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Order</a>
                    <a href="{{ route('customers.edit', $customer) }}" class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit Details</a>
                </div>
            </div>
        </div>
    </div>
@endsection
