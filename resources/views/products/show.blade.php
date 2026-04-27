@extends('layouts.app')
@section('title', $product->name)

@section('content')

<div class="page-header">
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('products.index') }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Products
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">{{ $product->name }}</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Product
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Main --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Product Card --}}
        <div class="card overflow-hidden">
            <div class="h-48 bg-gradient-to-br from-brand-50 to-violet-50 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
                <svg class="w-20 h-20 text-brand-200 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <div class="p-5">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $product->name }}</h2>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($product->price, 2) }} MAD
                    </span>
                    <span class="badge-info">Per unit</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <div>
                        <p class="text-xs text-slate-400">Added to catalog</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Last updated</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders containing this product --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    In Orders ({{ $product->orders->count() }})
                </h2>
            </div>
            @if($product->orders->isEmpty())
            <div class="empty-state py-10">
                <div class="empty-state-icon mx-auto">
                    <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                </div>
                <p class="empty-state-title">Not ordered yet</p>
                <p class="empty-state-desc">This product hasn't been included in any orders</p>
            </div>
            @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order) }}"
                               class="font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('customers.show', $order->customer) }}"
                               class="text-sm text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">
                                {{ $order->customer->name }}
                            </a>
                        </td>
                        <td><span class="badge-default">× {{ $order->pivot->quantity }}</span></td>
                        <td class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ number_format($product->price * $order->pivot->quantity, 2) }} MAD
                        </td>
                        <td>
                            <span class="badge-{{ $order->status }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-xs text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('orders.show', $order) }}" class="btn-ghost btn-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Right Sidebar: Stats --}}
    <div class="space-y-5">
        @php
            $totalUnits    = $product->orders->sum('pivot.quantity');
            $totalRevenue  = $product->price * $totalUnits;
            $deliveredOrds = $product->orders->where('status','delivered')->count();
        @endphp
        <div class="card p-5">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Sales Statistics</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-brand-50 dark:bg-brand-900/20 rounded-lg">
                    <span class="text-sm text-slate-600 dark:text-slate-300">Total Orders</span>
                    <span class="text-lg font-bold text-brand-600 dark:text-brand-400">{{ $product->orders->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <span class="text-sm text-slate-600 dark:text-slate-300">Units Sold</span>
                    <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $totalUnits }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-violet-50 dark:bg-violet-900/20 rounded-lg">
                    <span class="text-sm text-slate-600 dark:text-slate-300">Revenue</span>
                    <span class="text-base font-bold text-violet-600 dark:text-violet-400">
                        {{ number_format($totalRevenue, 2) }} MAD
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                    <span class="text-sm text-slate-600 dark:text-slate-300">Delivered</span>
                    <span class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $deliveredOrds }}</span>
                </div>
            </div>
        </div>

        <div class="card p-4 space-y-2">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Actions</h3>
            <a href="{{ route('products.edit', $product) }}" class="btn-secondary w-full justify-center">Edit Product</a>
            <form method="POST" action="{{ route('products.destroy', $product) }}">
                @csrf
                @method('DELETE')
                <button type="submit" data-confirm-delete="product"
                        class="btn-danger w-full justify-center">Delete Product</button>
            </form>
        </div>
    </div>
</div>

@endsection
