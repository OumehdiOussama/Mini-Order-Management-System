
@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Add New Customer</h1>

            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('name') border-red-500! focus:border-red-500! @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('email') border-red-500! focus:border-red-500! @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('phone') border-red-500! focus:border-red-500! @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded font-semibold hover:bg-green-700">Add Customer</button>
                    <a href="{{ route('customers.index') }}" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded font-semibold hover:bg-gray-700 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
