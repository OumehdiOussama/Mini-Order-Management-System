@extends('layouts.app')
@section('title', $product->name)

@section('content')

<div class="page-header">
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('products.index') }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Products
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">{{ $product->name }}</h1>
    </div>
    <div class="flex gap-2">
        {{-- View only for customers --}}
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Main --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Product Info Card --}}
        <div class="card overflow-hidden">
            <div class="h-72 bg-slate-100 dark:bg-slate-700 flex items-center justify-center relative">
                @if($product->image_path)
                    <img src="{{ url('media/' . $product->image_path) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-20 h-20 text-slate-300 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <span class="text-slate-400 text-xs font-medium">No image available</span>
                    </div>
                @endif
            </div>
            <div class="p-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ $product->name }}</h2>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($product->price, 2) }} MAD
                    </span>
                    <span class="badge-info">Per unit</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-6 pt-6  border-slate-100 dark:border-slate-700">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Added to catalog</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mt-1">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Last updated</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mt-1">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
