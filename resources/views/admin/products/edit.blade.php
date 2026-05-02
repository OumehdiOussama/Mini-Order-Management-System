@extends('layouts.app')
@section('title', 'Edit ' . $product->name)

@section('content')

<div class="page-header">
    <div class="flex items-center gap-3">
        <a href="{{ route('products.show', $product) }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ $product->name }}
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">Edit Product</h1>
    </div>
</div>

<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="input-label">Product Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                       class="input-field" required>
                @error('name') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="price" class="input-label">Price (MAD) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}"
                           class="input-field pr-14" step="0.01" min="0.01" required>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">MAD</span>
                </div>
                @error('price') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="image" class="input-label">Product Image</label>
                
                @if($product->image_path)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" 
                             class="w-24 h-24 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                        <label class="flex items-center gap-2 text-sm text-red-600 dark:text-red-400 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 dark:border-slate-600">
                            Remove current photo
                        </label>
                    </div>
                @endif

                <input type="file" id="image" name="image" class="input-field py-2" accept="image/*">
                <p class="mt-1.5 text-xs text-slate-500">Supported formats: JPG, PNG, GIF. Max 2MB. Leave empty to keep current image.</p>
                @error('image') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('products.show', $product) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
