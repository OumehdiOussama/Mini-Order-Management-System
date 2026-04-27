@extends('layouts.app')
@section('title', 'New Product')

@section('content')

<div class="page-header">
    <div class="flex items-center gap-3">
        <a href="{{ route('products.index') }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Products
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">New Product</h1>
    </div>
</div>

<div class="max-w-2xl">
    <div class="card p-6">
        <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-5">
            Product Information
        </h2>
        <form method="POST" action="{{ route('products.store') }}" class="space-y-5">
            @csrf

            <div class="form-group">
                <label for="name" class="input-label">Product Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="input-field" placeholder="e.g. Wireless Headphones" required autofocus>
                @error('name') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="price" class="input-label">Price (MAD) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" id="price" name="price" value="{{ old('price') }}"
                           class="input-field pr-14" placeholder="0.00" step="0.01" min="0.01" required>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">MAD</span>
                </div>
                @error('price') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Product
                </button>
                <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
