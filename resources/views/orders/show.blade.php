@extends('layouts.app')
@section('title', 'Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT))

@section('content')

@php
    $statusConfig = [
        'pending'    => ['dot' => 'bg-amber-500',   'badge' => 'badge-pending'],
        'processing' => ['dot' => 'bg-blue-500',    'badge' => 'badge-processing'],
        'shipped'    => ['dot' => 'bg-violet-500',  'badge' => 'badge-shipped'],
        'delivered'  => ['dot' => 'bg-emerald-500', 'badge' => 'badge-delivered'],
        'cancelled'  => ['dot' => 'bg-red-500',     'badge' => 'badge-cancelled'],
    ];
    $timelineDotColors = [
        'pending'    => 'bg-amber-400',
        'processing' => 'bg-blue-500',
        'shipped'    => 'bg-violet-500',
        'delivered'  => 'bg-emerald-500',
        'cancelled'  => 'bg-red-400',
    ];
@endphp

{{-- Page Header --}}
<div class="page-header">
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('orders.index') }}"
           class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Orders
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
        <span class="{{ $statusConfig[$order->status]['badge'] ?? 'badge-default' }}">
            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
            {{ ucfirst($order->status) }}
        </span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('orders.edit', $order) }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Update Status
        </a>
    </div>
</div>

{{-- Two-Column Layout --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT — Main Content --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Order Summary Card --}}
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                Order Summary
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Order Date</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        {{ $order->created_at->format('M d, Y') }}
                    </p>
                    <p class="text-xs text-slate-400">{{ $order->created_at->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Status</p>
                    <span class="{{ $statusConfig[$order->status]['badge'] ?? 'badge-default' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Total Items</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        {{ $order->products->count() }} products
                    </p>
                </div>
            </div>
        </div>

        {{-- Customer Card --}}
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                Customer
            </h2>
            <div class="flex items-start gap-4">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:16px;color:white;font-weight:700;shrink:0">
                    {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <p class="text-xs text-slate-400">Name</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Email</p>
                        <a href="mailto:{{ $order->customer->email }}"
                           class="text-sm font-medium text-brand-600 dark:text-brand-400 hover:underline">
                            {{ $order->customer->email }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Phone</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $order->customer->phone }}</p>
                    </div>
                </div>
                <a href="{{ route('customers.show', $order->customer) }}"
                   class="btn-ghost btn-sm shrink-0">View Profile</a>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Ordered Products
                </h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="text-slate-600 dark:text-slate-300">{{ number_format($product->price, 2) }} MAD</td>
                        <td class="text-center">
                            <span class="badge-default">× {{ $product->pivot->quantity }}</span>
                        </td>
                        <td class="text-right font-semibold text-slate-800 dark:text-slate-200">
                            {{ number_format($product->price * $product->pivot->quantity, 2) }} MAD
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-slate-400 py-8">No products in this order</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 dark:bg-slate-900/50">
                        <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-slate-600 dark:text-slate-400">
                            Order Total
                        </td>
                        <td class="px-4 py-3 text-right text-base font-bold text-slate-900 dark:text-white">
                            {{ number_format($order->getTotalPrice(), 2) }} MAD
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- RIGHT — Sidebar --}}
    <div class="space-y-5">

        {{-- Shipping Info (conditional) --}}
        @if(in_array($order->status, ['shipped', 'delivered']))
        <div class="card p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V8l-4-4H8zm4 6v4m0 0l-2-2m2 2l2-2"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Shipping Info</h2>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-400 mb-1">Tracking Number</p>
                    <p class="font-mono text-sm bg-slate-100 dark:bg-slate-700 px-3 py-2 rounded-lg text-slate-800 dark:text-slate-200">
                        {{ $order->tracking_number ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Carrier</p>
                    <span class="badge-shipped">{{ $order->carrier ?? 'Not specified' }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Order Timeline --}}
        <div class="card p-5">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 bg-brand-100 dark:bg-brand-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Order Timeline</h2>
            </div>

            @forelse($order->timeline as $entry)
            <div class="timeline-item">
                <div class="timeline-line bg-slate-200 dark:bg-slate-600"></div>
                <div class="timeline-dot {{ $timelineDotColors[$entry->status] ?? 'bg-slate-400' }}"></div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge-{{ $entry->status }} text-[10px]">{{ ucfirst($entry->status) }}</span>
                        <span class="text-[10px] text-slate-400">{{ $entry->created_at->format('M d, H:i') }}</span>
                    </div>
                    @if($entry->notes)
                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $entry->notes }}</p>
                    @endif
                    @if($entry->tracking_number || $entry->carrier)
                    <div class="mt-2 p-2 bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-800 rounded-lg text-xs space-y-0.5">
                        @if($entry->tracking_number)
                        <p class="text-slate-600 dark:text-slate-400">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Tracking:</span>
                            <span class="font-mono">{{ $entry->tracking_number }}</span>
                        </p>
                        @endif
                        @if($entry->carrier)
                        <p class="text-slate-600 dark:text-slate-400">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Carrier:</span>
                            {{ $entry->carrier }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">No timeline entries yet</p>
            @endforelse
        </div>

        {{-- Quick Actions --}}
        <div class="card p-4 space-y-2">
            <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Quick Actions</h2>
            <a href="{{ route('orders.edit', $order) }}" class="btn-primary w-full justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Update Status
            </a>
            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" data-confirm-delete="order"
                        class="btn-danger w-full justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Order
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
