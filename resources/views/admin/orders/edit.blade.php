@extends('layouts.app')
@section('title', 'Update Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT))

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('orders.show', $order) }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">Update Status</h1>
    </div>
</div>

@php
    $statusIcons = [
        'pending'    => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'processing' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'shipped'    => 'M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V8l-4-4H8zm4 9l-3-3m0 0l3-3m-3 3h7',
        'delivered'  => 'M5 13l4 4L19 7',
        'cancelled'  => 'M6 18L18 6M6 6l12 12',
    ];
    $statusColors = [
        'pending'    => 'border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 text-amber-700 dark:text-amber-300',
        'processing' => 'border-blue-300 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700 text-blue-700 dark:text-blue-300',
        'shipped'    => 'border-violet-300 bg-violet-50 dark:bg-violet-900/20 dark:border-violet-700 text-violet-700 dark:text-violet-300',
        'delivered'  => 'border-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300',
        'cancelled'  => 'border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-700 text-red-700 dark:text-red-300',
    ];
    $selectedColors = [
        'pending'    => 'ring-amber-400',
        'processing' => 'ring-blue-400',
        'shipped'    => 'ring-violet-400',
        'delivered'  => 'ring-emerald-400',
        'cancelled'  => 'ring-red-400',
    ];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Main Form --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Current Status Info --}}
        <div class="card p-4 flex items-center gap-4">
            <div class="shrink-0 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-center">
                <p class="text-xs text-slate-400">Current</p>
                <span class="badge-{{ $order->status }} mt-1">{{ ucfirst($order->status) }}</span>
            </div>
            <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Choose the new status below</p>
                <p class="text-xs text-slate-400">Only valid transitions are shown</p>
            </div>
        </div>

        <form method="POST" action="{{ route('orders.update', $order) }}"
              x-data="{ selectedStatus: '{{ old('status', $order->status) }}' }"
              class="space-y-5"
              hx-boost="false">
        @csrf
        @method('PUT')

            {{-- Status Radio Cards --}}
            <div class="card p-5">
                <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                    New Status <span class="text-red-500">*</span>
                </h2>

                @error('status')
                <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                </div>
                @enderror

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($statuses as $status)
                    <label class="relative cursor-pointer">
                        <input type="radio"
                               name="status"
                               value="{{ $status }}"
                               x-model="selectedStatus"
                               class="sr-only peer"
                               {{ old('status') === $status ? 'checked' : '' }}>
                        <div class="flex items-center gap-3 p-4 border-2 rounded-xl transition-all duration-150 cursor-pointer
                                    {{ $statusColors[$status] ?? 'border-slate-200 bg-slate-50' }}
                                    peer-checked:ring-2 {{ $selectedColors[$status] ?? 'peer-checked:ring-slate-400' }} peer-checked:ring-offset-1">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/60 dark:bg-black/20 shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="{{ $statusIcons[$status] ?? '' }}"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm capitalize">{{ $status }}</span>
                            <div class="ml-auto w-4 h-4 border-2 border-current rounded-full flex items-center justify-center">
                                <div x-show="selectedStatus === '{{ $status }}'"
                                     class="w-2 h-2 bg-current rounded-full"></div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Notes --}}
            <div class="card p-5">
                <div class="form-group">
                    <label class="input-label">Notes (optional)</label>
                    <textarea name="notes"
                              class="textarea-field"
                              rows="3"
                              placeholder="Add a note about this status change…">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Tracking Fields (shown only for 'shipped') --}}
            <div x-show="selectedStatus === 'shipped'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="card p-5 border-violet-200 dark:border-violet-800">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V8l-4-4H8z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-violet-700 dark:text-violet-300">Shipping Details Required</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="input-label">Tracking Number <span class="text-red-500">*</span></label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                               class="input-field font-mono" placeholder="e.g. 1Z999AA10123456784">
                        @error('tracking_number')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="input-label">Carrier <span class="text-red-500">*</span></label>
                        <input type="text" name="carrier" value="{{ old('carrier', $order->carrier) }}"
                               class="input-field" placeholder="e.g. DHL, FedEx, UPS">
                        @error('carrier')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Warning for irreversible states --}}
            <div x-show="selectedStatus === 'delivered' || selectedStatus === 'cancelled'"
                 x-transition
                 class="flex items-start gap-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Irreversible Action</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">
                        This status change cannot be undone. Please confirm before proceeding.
                    </p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1 justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Status
                </button>
                <a href="{{ route('orders.show', $order) }}" class="btn-secondary px-6">
                    Cancel
                </a>
            </div>

        </form>
    </div>

    {{-- Sidebar: Order Info --}}
    <div class="space-y-5">
        <div class="card p-5">
            <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Order Info</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Order ID</span>
                    <span class="font-mono font-semibold text-slate-800 dark:text-slate-200">
                        #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Customer</span>
                    <div class="flex items-center gap-2">
                        @if($order->customer->user && $order->customer->user->photo)
                            <img src="{{ url('media/' . $order->customer->user->photo) }}" 
                                 class="w-6 h-6 rounded-md object-cover">
                        @endif
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $order->customer->name }}</span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Items</span>
                    <span class="badge-default">{{ $order->products->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Total</span>
                    <span class="font-bold text-slate-900 dark:text-white">
                        {{ number_format($order->getTotalPrice(), 2) }} MAD
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Created</span>
                    <span class="text-slate-600 dark:text-slate-400">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
