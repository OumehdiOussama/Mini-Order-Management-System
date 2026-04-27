@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="animate-slide-up">
    <div class="mb-7">
        <h1 class="text-2xl font-bold text-slate-900">Set new password</h1>
        <p class="text-slate-500 text-sm mt-1">Enter your email and choose a new password</p>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-xl">
        <p class="text-sm text-red-600">{{ session('error') }}</p>
    </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email" class="input-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="input-field" placeholder="you@example.com" autofocus>
            @error('email') <span class="input-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" x-data="{ show: false }">
            <label for="password" class="input-label">New Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password"
                       class="input-field pr-10" placeholder="Minimum 8 characters">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password') <span class="input-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="input-label">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="input-field" placeholder="Repeat your new password">
            @error('password_confirmation') <span class="input-error">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 bg-gradient-to-r from-brand-500 to-violet-500 hover:from-brand-600 hover:to-violet-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Reset Password
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Remember your password?
        <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-600 font-semibold">Sign in</a>
    </p>
</div>
@endsection
