@extends('layouts.app')
@section('title', 'Orders')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Manage and track all customer orders</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Order
    </a>
</div>

{{-- Filter Bar --}}
<div class="card px-4 py-3 mb-5">
    <form method="GET" action="{{ route('orders.index') }}" class="flex items-end gap-3">
        {{-- Search --}}
        <div class="flex-1">
            <label class="text-[10px] font-bold uppercase text-slate-400 mb-1 block ml-1">Search ID/Customer</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search orders…"
                       class="input-field pl-9 h-10">
            </div>
        </div>

        {{-- Status Filter --}}
        <div class="w-40">
            <label class="text-[10px] font-bold uppercase text-slate-400 mb-1 block ml-1">Status</label>
            <select name="status" class="select-field h-10" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Date Filters --}}
        <div class="flex items-center gap-2">
            <div class="w-32">
                <label class="text-[10px] font-bold uppercase text-slate-400 mb-1 block ml-1">From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-field h-10">
            </div>
            <div class="w-32">
                <label class="text-[10px] font-bold uppercase text-slate-400 mb-1 block ml-1">To</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-field h-10">
            </div>
        </div>

        <div class="flex flex-col">
            <span class="text-[10px] mb-1 block">&nbsp;</span>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary h-10 px-5 flex items-center gap-2 shadow-sm shadow-brand-500/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter</span>
                </button>
                
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                <a href="{{ route('orders.index') }}" class="btn-ghost h-10 px-4 flex items-center gap-2 text-slate-500 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Clear</span>
                </a>
                @endif
            </div>
        </div>
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
                    <th>Customer</th>
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
                        <div class="flex items-center gap-2.5">
                            @if($order->customer->user && $order->customer->user->photo)
                                <img src="{{ url('media/' . $order->customer->user->photo) }}" 
                                     alt="{{ $order->customer->name }}" 
                                     class="shrink-0 object-cover border border-slate-200 dark:border-slate-700"
                                     style="width:30px;height:30px;border-radius:8px;">
                            @else
                                <div class="shrink-0"
                                     style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:11px;color:white;font-weight:700;">
                                    {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $order->customer->name }}</p>
                                <p class="text-xs text-slate-400">{{ $order->customer->email }}</p>
                            </div>
                        </div>
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
                            @can('update', $order)
                            <a href="{{ route('orders.edit', $order) }}"
                               title="Update Status"
                               class="btn-icon text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endcan

                            @can('delete', $order)
                            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        data-confirm-delete="order"
                                        title="Delete"
                                        class="btn-icon text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endcan
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
