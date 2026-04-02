@extends('layouts.app')

@section('title', 'Product Details - ' . $product->name)

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Product Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
            <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Product Information -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Product Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 text-sm">Product Name</p>
                        <p class="font-semibold text-2xl text-gray-800">{{ $product->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Unit Price</p>
                        <p class="font-semibold text-3xl text-green-600">{{ number_format($product->price, 2) }} MAD</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Added to Catalog</p>
                        <p class="font-semibold">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Last Updated</p>
                        <p class="font-semibold">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Orders -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Orders Containing This Product</h2>
                
                @if($product->orders->isEmpty())
                    <p class="text-gray-600">This product has not been ordered yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Order ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Customer</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Quantity</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Subtotal</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Date</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->orders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline font-semibold">{{ $order->id }}</a>
                                        </td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('customers.show', $order->customer) }}" class="text-blue-600 hover:underline">
                                                {{ $order->customer->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 font-semibold">{{ $order->pivot->quantity }}</td>
                                        <td class="px-4 py-2 font-semibold">{{ number_format($product->price * $order->pivot->quantity, 2) }} MAD</td>
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
                <h3 class="text-lg font-bold mb-4 text-gray-800">Sales Statistics</h3>
                <div class="space-y-4">
                    <div class="text-center p-3 bg-blue-50 rounded">
                        <p class="text-gray-600 text-sm">Total Orders</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $product->orders->count() }}</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded">
                        <p class="text-gray-600 text-sm">Total Units Sold</p>
                        <p class="text-3xl font-bold text-green-600">{{ $product->orders->sum('pivot.quantity') }}</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded">
                        <p class="text-gray-600 text-sm">Total Revenue</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($product->price * $product->orders->sum('pivot.quantity'), 2) }} MAD</p>
                    </div>
                    <div class="text-center p-3 bg-orange-50 rounded">
                        <p class="text-gray-600 text-sm">Delivered Orders</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $product->orders->where('status', 'delivered')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('products.edit', $product) }}" class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit Product</a>
                    <a href="{{ route('products.index') }}" class="w-full block text-center bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">All Products</a>
                </div>
            </div>
        </div>
    </div>
@endsection
