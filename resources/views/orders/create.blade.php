
@extends("layouts.app")

@section("title", "Create Order")

@section("content")
    <div class="max-w-2xl mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-md">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">Create New Order</h1>

            <form action="{{ route("orders.store") }}" method="POST">
                @csrf

                <!-- Select Customer -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Select Customer *</label>
                    <select name="customer_id" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error("customer_id") border-red-500! focus:border-red-500! @enderror">
                        <option value="">-- Select a customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old("customer_id") == $customer->id ? "selected" : "" }}>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                    @error("customer_id")
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pb-6 mb-6 border-b">
                    <label class="block mb-4 text-sm font-semibold text-gray-700">Select Products & Quantities *</label>
                    @if($products->isEmpty())
                        <p class="text-gray-600">No products available. Please <a href="{{ route("products.create") }}" class="text-blue-600 hover:underline">create a product</a> first.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($products as $product)
                                <div class="flex items-center gap-4 p-3 border border-gray-200 rounded hover:bg-gray-50">
                                    <input type="checkbox" name="products[]" value="{{ $product->id }}" id="product_{{ $product->id }}" class="w-4 h-4">
                                    <label for="product_{{ $product->id }}" class="flex-1 cursor-pointer">
                                        <span class="font-semibold">{{ $product->name }}</span>
                                        <span class="ml-2 text-gray-600">{{ number_format($product->price, 2) }} MAD</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <label for="qty_{{ $product->id }}" class="text-sm">Qty:</label>
                                        <input type="number" id="qty_{{ $product->id }}" name="quantities[{{ $product->id }}]" value="1" min="1" class="w-20 px-2 py-1 border border-gray-300 rounded focus:outline-none">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error("products")
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 font-semibold text-white bg-green-600 rounded hover:bg-green-700">Create Order</button>
                    <a href="{{ route("orders.index") }}" class="flex-1 px-4 py-2 font-semibold text-center text-white bg-gray-600 rounded hover:bg-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
