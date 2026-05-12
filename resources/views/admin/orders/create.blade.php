@extends('layouts.app')
@section('title', 'New Order')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="flex items-center gap-3">
        <a href="{{ route('orders.index') }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Orders
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">New Order</h1>
    </div>
</div>

{{--
    hx-boost="false" CRITICAL: prevents HTMX from intercepting this POST,
    which would cause the redirect to fail silently.
--}}
<form method="POST" action="{{ route('orders.store') }}" id="orderForm" hx-boost="false">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT: Customer + Products --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Customer Selection --}}
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                Customer
            </h2>
            <div class="form-group">
                @if(auth()->user()->role === 'customer')
                    <label class="input-label">Customer Name</label>
                    <div class="input-field bg-slate-50 dark:bg-slate-900/50 cursor-not-allowed">
                        {{ auth()->user()->name }} — {{ auth()->user()->email }}
                    </div>
                    <input type="hidden" name="customer_id" value="{{ auth()->user()->customer?->id }}">
                @else
                    <label for="customer_id" class="input-label">Select Customer <span class="text-red-500">*</span></label>
                    <select name="customer_id" id="customer_id" class="select-field" required>
                        <option value="">Choose a customer…</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} — {{ $customer->email }}
                        </option>
                        @endforeach
                    </select>
                @endif
                @error('customer_id')
                    <span class="input-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Product Picker --}}
        <div class="card overflow-hidden"
             x-data="{
                products: @js($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price])),
                selected: {},
                quantities: {},
                toggle(id) {
                    if (this.selected[id]) {
                        delete this.selected[id];
                        delete this.quantities[id];
                    } else {
                        this.selected[id] = true;
                        this.quantities[id] = 1;
                    }
                },
                isSelected(id) { return !!this.selected[id]; },
                getSubtotal(productId) {
                    const product = this.products.find(p => p.id === productId);
                    const qty = parseInt(this.quantities[productId]) || 0;
                    return product ? (product.price * qty).toFixed(2) : '0.00';
                },
                getTotal() {
                    return this.products
                        .filter(p => this.selected[p.id])
                        .reduce((sum, p) => sum + (p.price * (parseInt(this.quantities[p.id]) || 0)), 0)
                        .toFixed(2);
                }
             }">

            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Products <span class="text-red-500">*</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Select products and set quantities</p>
            </div>

            @error('products')
            <div class="mx-5 mt-3">
                <span class="input-error">{{ $message }}</span>
            </div>
            @enderror
            @error('quantities')
            <div class="mx-5 mt-3">
                <span class="input-error">{{ $message }}</span>
            </div>
            @enderror

            <div class="divide-y divide-slate-100 dark:divide-slate-700 max-h-96 overflow-y-auto">
                @foreach($products as $product)
                <div class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                     :class="isSelected({{ $product->id }}) ? 'bg-brand-50/50 dark:bg-brand-900/10' : ''">
                    {{--
                        CRITICAL FIX: name="products[]" sends a plain array [id1, id2].
                        We use name="products[id]" so PHP receives an associative array
                        keyed by product ID, which OrderService then reads correctly.
                    --}}
                    <input type="checkbox"
                           name="products[{{ $product->id }}]"
                           value="{{ $product->id }}"
                           id="product_{{ $product->id }}"
                           @change="toggle({{ $product->id }})"
                           :checked="isSelected({{ $product->id }})"
                           class="w-4 h-4 accent-brand-500 cursor-pointer rounded">

                    {{-- Product Image --}}
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

                    {{-- Product Info --}}
                    <label for="product_{{ $product->id }}" class="flex-1 cursor-pointer">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ number_format($product->price, 2) }} MAD / unit</p>
                    </label>

                    {{-- Quantity Input --}}
                    <div x-show="isSelected({{ $product->id }})" class="flex items-center gap-2">
                        <input type="number"
                               name="quantities[{{ $product->id }}]"
                               x-model.number="quantities[{{ $product->id }}]"
                               min="1" max="999"
                               :disabled="!isSelected({{ $product->id }})"
                               class="input-field w-20 text-center py-1.5"
                               placeholder="Qty">
                        <span class="text-xs text-slate-500 w-24 text-right font-medium"
                              x-text="getSubtotal({{ $product->id }}) + ' MAD'"></span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Live Total --}}
            <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <span class="text-sm text-slate-500">Estimated Total</span>
                <span class="text-lg font-bold text-slate-900 dark:text-white" x-text="getTotal() + ' MAD'">0.00 MAD</span>
            </div>
        </div>
    </div>

    {{-- RIGHT: Summary & Submit --}}
    <div class="space-y-5">
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                Order Details
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Status</span>
                    <span class="badge-pending">Pending</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Date</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-700">
                <p class="text-xs text-slate-400">Customer will receive an email confirmation after order creation.</p>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Place Order
        </button>
        <a href="{{ route('orders.index') }}" class="btn-secondary w-full justify-center">
            Cancel
        </a>
    </div>
</div>

</form>
@endsection
