
@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Customers</h1>
        <a href="{{ route('customers.create') }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">+ Add Customer</a>
    </div>

    <!-- Search Bar -->
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <form method="GET" action="{{ route('customers.index') }}" class="flex gap-2">
            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Search</button>
            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="px-6 py-2 text-white bg-gray-600 rounded hover:bg-gray-700">Clear</a>
            @endif
        </form>
    </div>

    @if($customers->isEmpty())
        <div class="p-8 text-center bg-white rounded-lg shadow-md">
            <p class="mb-4 text-lg text-gray-600 mb-5">No customers found.</p>
            <a href="{{ route('customers.create') }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Create First Customer</a>
        </div>
    @else
        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 border-b">
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Name</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Email</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Phone</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Orders</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $customer->name }}</td>
                            <td class="px-6 py-3">{{ $customer->email }}</td>
                            <td class="px-6 py-3">{{ $customer->phone }}</td>
                            <td class="px-6 py-3">{{ $customer->orders->count() }}</td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">View</a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="px-3 py-1 text-sm text-white bg-orange-500 rounded hover:bg-orange-600">Edit</a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-confirm-delete="customer" class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
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
            {{ $customers->links() }}
        </div>
    @endif
@endsection