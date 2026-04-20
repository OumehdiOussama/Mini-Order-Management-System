
@extends("layouts.app")

@section("title", "Orders")

@section("content")
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Orders</h1>
        <a href="{{ route("orders.create") }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">+ New Order</a>
    </div>

    @if($orders->isEmpty())
        <div class="p-8 text-center bg-white rounded-lg shadow-md">
            <p class="mb-4 text-lg text-gray-600">No orders found.</p>
            <a href="{{ route("orders.create") }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Create First Order</a>
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 border-b">
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Order ID</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Customer</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Items</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Total Price</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Status</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Date</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <a href="{{ route("orders.show", $order) }}" class="font-semibold text-blue-600 hover:underline">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-3">
                                <div>
                                    <p class="font-semibold">{{ $order->customer->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->customer->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-3">{{ $order->products->count() }} items</td>
                            <td class="px-6 py-3 font-semibold">{{ number_format($order->getTotalPrice(), 2) }} MAD</td>
                            <td class="px-6 py-3">
                                @php
                                    $statusBg = [
                                        "pending" => "bg-yellow-100 text-yellow-800",
                                        "processing" => "bg-blue-100 text-blue-800",
                                        "shipped" => "bg-purple-100 text-purple-800",
                                        "delivered" => "bg-green-100 text-green-800",
                                        "cancelled" => "bg-red-100 text-red-800",
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded text-sm font-semibold {{ $statusBg[$order->status] ?? "bg-gray-100" }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">{{ $order->created_at->format("M d, Y") }}</td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route("orders.show", $order) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">View</a>
                                    <a href="{{ route("orders.edit", $order) }}" class="px-3 py-1 text-sm text-white bg-orange-500 rounded hover:bg-orange-600">Update</a>
                                    <form method="POST" action="{{ route("orders.destroy", $order) }}" style="display:inline;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" data-confirm-delete="order" class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
