@extends('layouts.app')

@section('title', 'Order Details #' . $order->id)

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Order #{{ $order->id }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('orders.edit', $order) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Status</a>
            <a href="{{ route('orders.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Order Summary -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Order Summary</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Order Date</p>
                        <p class="font-semibold">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Current Status</p>
                        @php
                            $statusBg = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-purple-100 text-purple-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded text-sm font-semibold {{ $statusBg[$order->status] ?? 'bg-gray-100' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Name</p>
                        <p class="font-semibold">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-semibold">{{ $order->customer->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Phone</p>
                        <p class="font-semibold">{{ $order->customer->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Ordered Products</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-left">Price</th>
                                <th class="px-4 py-2 text-center">Quantity</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->products as $product)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $product->name }}</td>
                                    <td class="px-4 py-2">{{ number_format($product->price,2) }} MAD</td>
                                    <td class="px-4 py-2 text-center">{{ $product->pivot->quantity }}</td>
                                    <td class="px-4 py-2 text-right font-semibold">{{ number_format($product->price * $product->pivot->quantity, 2) }} MAD</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-600">No products in this order</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                                <td class="px-4 py-2 text-right text-lg">{{ number_format($order->getTotalPrice(), 2) }} MAD</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Shipping Information -->
            @if($order->status === 'shipped' || $order->status === 'delivered')
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">Shipping Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-600 text-sm">Tracking Number</p>
                            <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded">{{ $order->tracking_number ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Carrier</p>
                            <p class="font-semibold">{{ $order->carrier ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Order Timeline</h2>
                @forelse($order->timeline as $entry)
                    <div class="mb-4 pb-4 border-b last:border-b-0">
                        <div class="flex items-start gap-3">
                            <div class="w-3 h-3 bg-blue-600 rounded-full mt-1.5 shrink-0"></div>
                            <div class="flex-1">
                                <p class="font-semibold capitalize">{{ $entry->status }}</p>
                                <p class="text-sm text-gray-600">{{ $entry->created_at->format('M d, Y H:i') }}</p>
                                @if($entry->notes)
                                    <p class="text-sm text-gray-700 mt-1">{{ $entry->notes }}</p>
                                @endif
                                @if($entry->tracking_number || $entry->carrier)
                                    <div class="mt-2 text-xs bg-blue-50 border border-blue-200 p-2 rounded">
                                        @if($entry->tracking_number)
                                            <p><span class="font-semibold">Tracking:</span> <span class="font-mono">{{ $entry->tracking_number }}</span></p>
                                        @endif
                                        @if($entry->carrier)
                                            <p><span class="font-semibold">Carrier:</span> {{ $entry->carrier }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">No timeline entries</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
