
@extends("layouts.app")

@section("title", "Products")

@section("content")
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Products</h1>
        <a href="{{ route("products.create") }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">+ Add Product</a>
    </div>

    @if($products->isEmpty())
        <div class="p-8 text-center bg-white rounded-lg shadow-md">
            <p class="mb-4 text-lg text-gray-600">No products found.</p>
            <a href="{{ route("products.create") }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Create First Product</a>
        </div>
    @else
        <div class="overflow-hidden bg-white rounded-lg shadow-md ">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 border-b">
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Name</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Price</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Orders</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $product->name }}</td>
                            <td class="px-6 py-3 font-semibold">{{ number_format($product->price, 2) }} MAD</td>
                            <td class="px-6 py-3">{{ $product->orders->count() }}</td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route("products.show", $product) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">View</a>
                                    <a href="{{ route("products.edit", $product) }}" class="px-3 py-1 text-sm text-white bg-orange-500 rounded hover:bg-orange-600">Edit</a>
                                    <form method="POST" action="{{ route("products.destroy", $product) }}" style="display:inline;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" data-confirm-delete="product" class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection