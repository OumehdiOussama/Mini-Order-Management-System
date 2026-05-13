@extends('layouts.app')
@section('title', $customer->name)

@section('content')

    {{-- Header --}}
    <div class="page-header">
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('customers.index') }}" class="btn-ghost btn-sm text-slate-500">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Customers
            </a>
            <span class="text-slate-300 dark:text-slate-600">/</span>
            <h1 class="page-title">{{ $customer->name }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('orders.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Profile Card --}}
            <div class="card p-5">
                <div class="flex items-start gap-5">
                    @if($customer->user && $customer->user->photo)
                        <img src="{{ url('media/' . $customer->user->photo) }}" 
                             alt="{{ $customer->name }}" 
                             class="shrink-0 object-cover border border-slate-200 dark:border-slate-700 shadow-sm"
                             style="width:56px;height:56px;border-radius:14px;">
                    @else
                        <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:20px;color:white;font-weight:700;shrink:0">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $customer->name }}</h2>
                        <p class="text-sm text-slate-400 mt-0.5">Member since {{ $customer->created_at->format('M d, Y') }}</p>
                        <div class="flex flex-wrap gap-3 mt-3">
                            <a href="mailto:{{ $customer->email }}"
                            class="flex items-center gap-2 text-sm text-brand-600 dark:text-brand-400 hover:underline">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $customer->email }}
                            </a>
                            <a href="tel:{{ $customer->phone }}"
                            class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300 hover:text-brand-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $customer->phone }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orders Table --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Order History ({{ $customer->orders->count() }})
                    </h2>
                </div>
                @if($customer->orders->isEmpty())
                    <div class="empty-state py-10">
                        <div class="empty-state-icon mx-auto">
                            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                            </svg>
                        </div>
                        <p class="empty-state-title">No orders yet</p>
                        <p class="empty-state-desc">This customer hasn't placed any orders</p>
                    </div>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}"
                                        class="font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                                            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        </a>
                                    </td>
                                    <td><span class="badge-default">{{ $order->products->count() }}</span></td>
                                    <td class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ number_format($order->getTotalPrice(), 2) }} MAD
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

        {{-- RIGHT: Stats --}}
        <div class="space-y-5">
            @php
                $totalSpent = $customer->orders->sum(fn($o) => $o->getTotalPrice());
                $delivered  = $customer->orders->where('status','delivered')->count();
                $active     = $customer->orders->whereIn('status',['pending','processing','shipped'])->count();
            @endphp
            <div class="card p-5">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-brand-50 dark:bg-brand-900/20 rounded-lg">
                        <span class="text-sm text-slate-600 dark:text-slate-300">Total Orders</span>
                        <span class="text-lg font-bold text-brand-600 dark:text-brand-400">{{ $customer->orders->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <span class="text-sm text-slate-600 dark:text-slate-300">Delivered</span>
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $delivered }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                        <span class="text-sm text-slate-600 dark:text-slate-300">Active</span>
                        <span class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $active }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-violet-50 dark:bg-violet-900/20 rounded-lg">
                        <span class="text-sm text-slate-600 dark:text-slate-300">Total Spent</span>
                        <span class="text-base font-bold text-violet-600 dark:text-violet-400">
                            {{ number_format($totalSpent, 2) }} MAD
                        </span>
                    </div>
                </div>
            </div>

            <div class="card p-4 space-y-2">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Actions</h3>
                <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary w-full justify-center">Edit Customer</a>
                <form method="POST" action="{{ route('customers.destroy', $customer) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" data-confirm-delete="customer"
                            class="btn-danger w-full justify-center">Delete Customer</button>
                </form>
            </div>
        </div>
    </div>

@endsection
