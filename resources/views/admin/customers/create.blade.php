@extends('layouts.app')
@section('title', 'New Customer')

@section('content')

<div class="page-header">
    <div class="flex items-center gap-3">
        <a href="{{ route('customers.index') }}" class="btn-ghost btn-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Customers
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <h1 class="page-title">New Customer</h1>
    </div>
</div>

<div class="max-w-2xl">
    <div class="card p-6">
        <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-5">
            Customer Information
        </h2>

        <form method="POST" action="{{ route('customers.store') }}" class="space-y-5">
            @csrf

            <div class="form-group">
                <label for="name" class="input-label">Full Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="input-field" placeholder="e.g. Yassine El Amrani" required autofocus>
                @error('name') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="input-label">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="input-field" placeholder="yassine@example.com" required>
                @error('email') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="input-label">Phone Number <span class="text-red-500">*</span></label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                       class="input-field" placeholder="+212 6XX XXX XXX" required>
                @error('phone') <span class="input-error">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Customer
                </button>
                <a href="{{ route('customers.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
