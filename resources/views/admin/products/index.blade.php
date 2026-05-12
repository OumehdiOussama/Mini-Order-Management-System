@extends('layouts.app')
@section('title', 'Products')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">{{ $products->total() }} products in your catalog</p>
    </div>
    <div class="flex gap-2">
        <button @click="$dispatch('toggle-selection-mode')" class="btn-ghost text-slate-500 hover:text-red-600 border border-slate-200 hover:border-red-100 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-200 font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete Many
        </button>
        <a href="{{ route('products.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Product
        </a>
    </div>
</div>
 
 {{-- Search --}}
 <div class="card px-4 py-3 mb-5 flex items-center gap-3">
     <form method="GET" action="{{ route('products.index') }}" class="flex-1 flex gap-3">
         <div class="relative flex-1">
             <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
             </svg>
             <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search products by name…"
                    class="input-field pl-9">
         </div>
         <button type="submit" class="btn-primary">Search</button>
         @if(request('search'))
         <a href="{{ route('products.index') }}" class="btn-ghost">Clear</a>
         @endif
     </form>
 </div>

@if($products->isEmpty())
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
        </div>
        <p class="empty-state-title">No products yet</p>
        <p class="empty-state-desc">Add your first product to start taking orders</p>
        <a href="{{ route('products.create') }}" class="btn-primary btn-sm">+ New Product</a>
    </div>
</div>
@else
<div x-data="{ 
    selected: [],
    allSelected: false,
    selectionMode: false,
    toggleAll() {
        if (this.allSelected) {
            this.selected = [];
            this.allSelected = false;
        } else {
            this.selected = Array.from(document.querySelectorAll('.product-checkbox')).map(el => el.value);
            this.allSelected = true;
        }
    },
    exitSelectionMode() {
        this.selectionMode = false;
        this.selected = [];
        this.allSelected = false;
    }
}" @toggle-selection-mode.window="selectionMode = !selectionMode; if(!selectionMode) exitSelectionMode()">
    {{-- Bulk Actions Bar --}}
    <div x-show="selectionMode" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mb-8 p-1 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 flex items-center overflow-hidden">
        
        <div class="px-5 py-3 flex items-center gap-3 bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-300 rounded-xl m-1">
            <div class="w-6 h-6 rounded-full bg-brand-500 text-white flex items-center justify-center text-[10px] font-bold">
                <span x-text="selected.length"></span>
            </div>
            <span class="text-sm font-bold tracking-tight">Selection Mode Active</span>
        </div>
        
        <div class="flex-1 px-4 flex items-center justify-end gap-2">
            <button @click="exitSelectionMode()" class="btn-ghost btn-sm text-slate-400 hover:text-slate-600 font-medium">Cancel</button>
            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 mx-2"></div>
            <form x-show="selected.length > 0" action="{{ route('products.bulkDestroy') }}" method="POST" @submit.prevent="if(confirm('Are you sure you want to delete ' + selected.length + ' products?')) $el.submit()">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="btn-primary btn-sm bg-red-500 hover:bg-red-600 border-none shadow-lg shadow-red-500/20 px-6">
                    Delete Selection (<span x-text="selected.length"></span>)
                </button>
            </form>
        </div>
    </div>

    {{-- Select All Header --}}
    <div x-show="selectionMode" x-transition class="flex items-center gap-3 mb-6 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-slate-700/50 w-fit">
        <input type="checkbox" @click="toggleAll()" x-model="allSelected" 
               class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 transition-all cursor-pointer">
        <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Select All Products</span>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div :class="selectionMode ? (selected.includes('{{ $product->id }}') ? 'ring-2 ring-brand-500 bg-brand-50/5' : 'cursor-pointer hover:bg-slate-50') : 'hover:shadow-card-hover'"
             @click="if(selectionMode) { if(selected.includes('{{ $product->id }}')) selected = selected.filter(id => id !== '{{ $product->id }}'); else selected.push('{{ $product->id }}'); }"
             class="card group transition-all duration-300 overflow-hidden flex flex-col h-full relative border border-slate-100 dark:border-slate-800">
            
            {{-- Checkbox Overlay --}}
            <div x-show="selectionMode" x-transition class="absolute top-3 left-3 z-20">
                <input type="checkbox" value="{{ $product->id }}" x-model="selected" @click.stop
                       class="product-checkbox w-4 h-4 rounded border-slate-300 text-brand-600 bg-white/80 dark:bg-slate-800/80 focus:ring-brand-500 cursor-pointer transition-all">
            </div>

            {{-- Product Image --}}
            <div class="h-40 bg-slate-100 dark:bg-slate-700 flex items-center justify-center relative overflow-hidden">
                @if(!$product->is_active)
                    <div class="absolute top-2 right-2 z-10">
                        <span class="px-2 py-0.5 bg-slate-800/80 text-white text-[10px] font-bold uppercase tracking-wider rounded backdrop-blur-sm">
                            Hidden
                        </span>
                    </div>
                @endif

                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <svg class="w-14 h-32 text-slate-300 dark:text-slate-600/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8"  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                @endif
                {{-- Quick action overlay --}}
                <div class="absolute inset-0 bg-brand-500/0 group-hover:bg-brand-500/5 transition-all duration-200 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                    <a href="{{ route('products.show', $product) }}"
                       class="w-8 h-8 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center shadow-md text-slate-600 hover:text-brand-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('products.edit', $product) }}"
                       class="w-8 h-8 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center shadow-md text-slate-600 hover:text-amber-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-4 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <a href="{{ route('products.show', $product) }}"
                       class="text-sm font-bold text-slate-800 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 line-clamp-2">
                        {{ $product->name }}
                    </a>
                </div>
                
                <div class="flex items-center gap-2 mb-3">
                    @if($product->stock <= 0)
                        <span class="text-[10px] font-bold text-red-600 bg-red-50 dark:bg-red-900/20 px-1.5 py-0.5 rounded">Out of Stock</span>
                    @elseif($product->stock <= 5)
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-1.5 py-0.5 rounded">Low Stock: {{ $product->stock }}</span>
                    @else
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded">Stock: {{ $product->stock }}</span>
                    @endif
                </div>

                <div class="mt-auto pt-3 flex items-center justify-between">
                    <span class="text-base font-bold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($product->price, 2) }} MAD
                    </span>
                    <div class="flex gap-1">
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-confirm-delete="product"
                                    class="btn-icon text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div class="mt-8 px-5 py-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
    {{ $products->links() }}
</div>
@endif
@endif

@endsection