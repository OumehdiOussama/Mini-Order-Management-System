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
                products: @js($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price, 'category' => $p->category ?? 'General', 'image' => $p->image_path ? url('media/'.$p->image_path) : null])),
                selected: {},
                quantities: {},
                search: '',
                categoryFilter: '',
                minPrice: '',
                maxPrice: '',
                
                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesSearch = p.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchesCategory = !this.categoryFilter || p.category === this.categoryFilter;
                        const matchesMin = !this.minPrice || p.price >= parseFloat(this.minPrice);
                        const matchesMax = !this.maxPrice || p.price <= parseFloat(this.maxPrice);
                        return matchesSearch && matchesCategory && matchesMin && matchesMax;
                    });
                },

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
                },
                getSelectedCount() {
                    return Object.keys(this.selected).length;
                }
             }">

            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Products <span class="text-red-500">*</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5" x-show="getSelectedCount() === 0">Select products and set quantities</p>
                        <p class="text-xs font-bold text-brand-600 dark:text-brand-400 mt-0.5" x-show="getSelectedCount() > 0" x-cloak>
                            <span x-text="getSelectedCount()"></span> products selected
                        </p>
                    </div>
                </div>

                {{-- Product Filters --}}
                <div class="flex flex-col gap-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="search" placeholder="Search name..." 
                                   class="input-field pl-9 py-1.5 text-xs h-9">
                        </div>
                        
                        <select x-model="categoryFilter" class="select-field py-1.5 text-xs h-9">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="minPrice" placeholder="Min Price" class="input-field py-1.5 text-xs h-9 w-full">
                            <input type="number" x-model="maxPrice" placeholder="Max Price" class="input-field py-1.5 text-xs h-9 w-full">
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="button" @click="search = ''; categoryFilter = ''; minPrice = ''; maxPrice = ''" 
                                    class="text-[10px] font-bold text-slate-400 hover:text-brand-600 uppercase tracking-wider transition-colors">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @error('products')
            <div class="mx-5 mt-3">
                <span class="input-error">{{ $message }}</span>
            </div>
            @enderror

            <div class="divide-y divide-slate-100 dark:divide-slate-700 max-h-[500px] overflow-y-auto">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                         :class="isSelected(product.id) ? 'bg-brand-50/50 dark:bg-brand-900/10' : ''">
                        
                        <input type="checkbox"
                               :name="'products[' + product.id + ']'"
                               :value="product.id"
                               :id="'product_' + product.id"
                               @change="toggle(product.id)"
                               :checked="isSelected(product.id)"
                               class="w-4 h-4 accent-brand-500 cursor-pointer rounded">

                        {{-- Product Image --}}
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 overflow-hidden border border-slate-200 dark:border-slate-600">
                            <template x-if="product.image">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!product.image">
                                <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </template>
                        </div>

                        {{-- Product Info --}}
                        <label :for="'product_' + product.id" class="flex-1 cursor-pointer min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400" x-text="product.category"></p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate" x-text="product.name"></p>
                            <p class="text-xs text-slate-400 font-medium"><span x-text="product.price.toFixed(2)"></span> MAD / unit</p>
                        </label>

                        {{-- Quantity Input --}}
                        <div x-show="isSelected(product.id)" x-cloak class="flex items-center gap-3">
                            <div class="flex items-center">
                                <button type="button" @click="if(quantities[product.id] > 1) quantities[product.id]--" 
                                        class="w-8 h-8 flex items-center justify-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-l-lg hover:bg-slate-50 text-slate-500">-</button>
                                <input type="number"
                                       :name="'quantities[' + product.id + ']'"
                                       x-model.number="quantities[product.id]"
                                       min="1" max="999"
                                       class="w-12 h-8 text-center text-xs font-bold border-y border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 focus:outline-none focus:ring-0">
                                <button type="button" @click="quantities[product.id]++" 
                                        class="w-8 h-8 flex items-center justify-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-r-lg hover:bg-slate-50 text-slate-500">+</button>
                            </div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 w-24 text-right"
                                  x-text="getSubtotal(product.id) + ' MAD'"></span>
                        </div>
                    </div>
                </template>
                
                {{-- No Results --}}
                <div x-show="filteredProducts.length === 0" x-cloak class="px-5 py-10 text-center">
                    <p class="text-slate-400 text-sm">No products match your filters</p>
                    <button type="button" @click="search = ''; categoryFilter = ''; minPrice = ''; maxPrice = ''" 
                            class="text-brand-600 text-xs font-bold mt-2 hover:underline">Clear all filters</button>
                </div>
            </div>

            {{-- Live Total --}}
            <div class="px-5 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Estimated Total</span>
                    <span class="text-xl font-black text-emerald-600 dark:text-emerald-400" x-text="getTotal() + ' MAD'">0.00 MAD</span>
                </div>
                <div x-show="getSelectedCount() > 0" x-cloak class="text-right">
                    <span class="text-xs text-slate-500 font-medium"><span x-text="getSelectedCount()"></span> items selected</span>
                </div>
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
