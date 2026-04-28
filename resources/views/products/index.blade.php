@extends('layouts.app')
@section('title', 'Products')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">{{ \App\Models\Product::count() }} products in your catalog</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Product
    </a>
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
{{-- Product Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($products as $product)
    <div class="card group hover:shadow-card-hover transition-all duration-200 overflow-hidden flex flex-col h-full">
        {{-- Product Image --}}
        <div class="h-40 bg-slate-100 dark:bg-slate-700 flex items-center justify-center relative overflow-hidden">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-500">
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
            <a href="{{ route('products.show', $product) }}"
               class="text-sm font-bold text-slate-800 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 line-clamp-2 mb-1">
                {{ $product->name }}
            </a>
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

{{-- Pagination --}}
@if($products->hasPages())
<div class="mt-8 px-5 py-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
    {{ $products->links() }}
</div>
@endif
@endif

@endsection