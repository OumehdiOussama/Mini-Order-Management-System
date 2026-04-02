
@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Customer</h1>

            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('name') border-red-500! focus:border-red-500! @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('email') border-red-500! focus:border-red-500! @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('phone') border-red-500! focus:border-red-500! @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Update Customer</button>
                    <a href="{{ route('customers.index') }}" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded font-semibold hover:bg-gray-700 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection