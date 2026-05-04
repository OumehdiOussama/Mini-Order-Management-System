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
        @if($order->status === 'pending')
        <form method="POST" action="{{ route('orders.update', $order) }}"
              id="cancel-order-form-header">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="cancelled">
            <input type="hidden" name="notes" value="Order cancelled by customer.">
            <button type="button"
                    onclick="confirmCancel()"
                    class="btn-danger">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancel Order
            </button>
        </form>
        @endif
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
                <div class="flex-1 flex flex-col gap-3 min-w-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 min-w-0">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-xs text-slate-400">Name</p>
                            </div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate" title="{{ $order->customer->name }}">{{ $order->customer->name }}</p>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs text-slate-400">Email</p>
                            </div>
                            <a href="mailto:{{ $order->customer->email }}"
                               class="text-sm font-medium text-brand-600 dark:text-brand-400 hover:underline truncate block w-full"
                               title="{{ $order->customer->email }}">
                                {{ $order->customer->email }}
                            </a>
                        </div>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <p class="text-xs text-slate-400">Phone</p>
                        </div>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $order->customer->phone }}</p>
                    </div>
                </div>
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
                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 overflow-hidden">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                    @endif
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

        {{-- Cancel Order (only if pending) --}}
        @if($order->status === 'pending')
        <div class="card p-4 space-y-2">
            <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Actions</h2>
            <form method="POST" action="{{ route('orders.update', $order) }}" id="cancel-order-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <input type="hidden" name="notes" value="Order cancelled by customer.">
                <button type="button"
                        onclick="confirmCancel()"
                        class="btn-danger w-full justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel Order
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

{{-- Cancel Confirmation Dialog --}}
<div id="cancel-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,0.5)">
    <div class="card p-6 max-w-sm w-full shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Cancel this order?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">This action cannot be undone.</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">
            Are you sure you want to cancel <span class="font-semibold">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>?
            Once cancelled, you will need to place a new order.
        </p>
        <div class="flex gap-3">
            <button type="button"
                    onclick="closeModal()"
                    class="btn-secondary flex-1 justify-center">
                Keep Order
            </button>
            <button type="button"
                    onclick="submitCancel()"
                    class="btn-danger flex-1 justify-center">
                Yes, Cancel
            </button>
        </div>
    </div>
</div>

<script>
function confirmCancel() {
    const modal = document.getElementById('cancel-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal() {
    const modal = document.getElementById('cancel-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function submitCancel() {
    const form = document.getElementById('cancel-order-form') || document.getElementById('cancel-order-form-header');
    if (form) form.submit();
}
// Close on backdrop click
document.getElementById('cancel-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@endsection
