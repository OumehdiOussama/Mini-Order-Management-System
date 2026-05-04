@extends('layouts.app')
@section('title', 'Edit ' . $customer->name)

@section('content')

    <div class="page-header">
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('customers.show', $customer) }}" class="btn-ghost btn-sm text-slate-500">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $customer->name }}
            </a>
            <span class="text-slate-300 dark:text-slate-600">/</span>
            <h1 class="page-title">Edit Customer</h1>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="card p-6">
            <div class="flex items-center gap-4 mb-6 pb-5 border-b border-slate-100 dark:border-slate-700">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:16px;color:white;font-weight:700;">
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $customer->name }}</p>
                    <p class="text-sm text-slate-400">Editing customer profile</p>
                </div>
            </div>

            <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="input-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                        class="input-field" required>
                    @error('name') <span class="input-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="input-label">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                        class="input-field" required>
                    @error('email') <span class="input-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="input-label">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="input-field" required>
                    @error('phone') <span class="input-error">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                    <a href="{{ route('customers.show', $customer) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection