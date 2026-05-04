@extends('layouts.app')
@section('title', 'Orders')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Track your order status and history</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Order
    </a>
</div>

{{-- Filter Bar --}}
<div x-data="{ search: '{{ request('search') }}', status: '{{ request('status') }}' }"
     class="card px-4 py-3 mb-5 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
    <form method="GET" action="{{ route('orders.index') }}" class="flex-1 flex flex-col sm:flex-row gap-3 w-full">
        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by customer or order ID…"
                   class="input-field pl-9">
        </div>
        {{-- Status Filter --}}
        <select name="status" class="select-field w-full sm:w-44" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
        </button>
        @if(request()->anyFilled(['search','status']))
        <a href="{{ route('orders.index') }}" class="btn-ghost">Clear</a>
        @endif
    </form>
</div>

{{-- Orders Table --}}
@if($orders->isEmpty())
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
        </div>
        <p class="empty-state-title">No orders found</p>
        <p class="empty-state-desc">
            @if(request()->anyFilled(['search','status']))
                Try adjusting your filters or search query
            @else
                Get started by creating your first order
            @endif
        </p>
        <a href="{{ route('orders.create') }}" class="btn-primary btn-sm">
            + New Order
        </a>
    </div>
</div>
@else
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $order) }}"
                           class="font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    
                    <td>
                        <span class="badge-default">{{ $order->products->count() }} items</span>
                    </td>
                    <td>
                        <span class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ number_format($order->getTotalPrice(), 2) }} MAD
                        </span>
                    </td>
                    <td>
                        <span class="badge-{{ $order->status }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-slate-500 dark:text-slate-400 text-xs">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('orders.show', $order) }}"
                               title="View"
                               class="btn-icon text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-700">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endif

@endsection
