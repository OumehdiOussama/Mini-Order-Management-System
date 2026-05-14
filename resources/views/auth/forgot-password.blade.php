@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="animate-slide-up">
    <div class="mb-7 text-center">
        <div class="w-14 h-14 bg-brand-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Reset your password</h1>
        <p class="text-slate-500 text-sm mt-2">Enter your email and we'll send you a reset link</p>
    </div>

    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl animate-fade-in">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm text-emerald-700 leading-relaxed">{{ session('success') }}</p>
    </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <div class="form-group">
            <label for="email" class="input-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="input-field" placeholder="you@example.com" autofocus>
            @error('email') <span class="input-error">{{ $message }}</span> @enderror
        </div>
        <div class="pt-2">
            <button type="submit" class="btn-primary w-full justify-center py-2.5">
                Send Reset Link
            </button>
        </div>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Remember your password?
        <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-600 font-semibold">Sign in</a>
    </p>
</div>
@endsection
