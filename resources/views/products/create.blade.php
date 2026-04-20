
@extends("layouts.app")

@section("title", "Add Product")

@section("content")
    <div class="max-w-md mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-md">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">Add New Product</h1>

            <form action="{{ route("products.store") }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Product Name *</label>
                    <input type="text" name="name" value="{{ old("name") }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error("name") border-red-500! focus:border-red-500! @enderror">
                    @error("name")
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Price (MAD) *</label>
                    <input type="number" step="0.01" min="1" name="price" value="{{ old("price") }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error("price") border-red-500! focus:border-red-500! @enderror">
                    @error("price")
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 font-semibold text-white bg-green-600 rounded hover:bg-green-700">Add Product</button>
                    <a href="{{ route("products.index") }}" class="flex-1 px-4 py-2 font-semibold text-center text-white bg-gray-600 rounded hover:bg-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
