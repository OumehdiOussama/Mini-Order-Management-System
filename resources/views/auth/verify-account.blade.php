@extends('layouts.auth')
@section('title', 'Verify Account')

@section('content')

<div class="animate-slide-up">
    <div class="flex items-center gap-2 mb-8 lg:hidden">
        <div class="w-9 h-9 bg-brand-500 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <span class="text-slate-800 font-bold text-lg">OMS</span>
    </div>

    <div class="mb-7">
        <h1 class="text-2xl font-bold text-slate-900">Verify your account</h1>
        <p class="text-slate-500 text-sm mt-1">Enter the 6-digit code sent to your email or phone</p>
    </div>

    @if(session('error'))
    <div class="mb-4 flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-xl">
        <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-sm text-red-600">{{ session('error') }}</p>
    </div>
    @endif

    <form id="otpForm" action="{{ url('/verify-account') }}" method="POST"
          x-data="{
            otp: ['', '', '', '', '', ''],
            canSubmit: false,
            check() {
                this.canSubmit = this.otp.every(v => v.length === 1);
            },
            handleInput(e, index) {
                const val = e.target.value.replace(/\D/g, '');
                this.otp[index] = val;
                if (val && index < 5) {
                    this.$refs['input' + (index + 1)].focus();
                }
                this.check();
            },
            handleKeydown(e, index) {
                if (e.key === 'Backspace' && !this.otp[index] && index > 0) {
                    this.$refs['input' + (index - 1)].focus();
                }
            },
            handlePaste(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                paste.split('').forEach((char, i) => {
                    this.otp[i] = char;
                });
                this.check();
                const nextIdx = Math.min(paste.length, 5);
                this.$refs['input' + nextIdx].focus();
            }
          }">
        @csrf
        <input type="hidden" name="identifier" value="{{ $identifier }}">

        {{-- OTP INPUTS --}}
        <div class="flex justify-between gap-2 mb-8">
            @for ($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    maxlength="1"
                    name="otp[]"
                    x-model="otp[{{ $i }}]"
                    x-ref="input{{ $i }}"
                    @input="handleInput($event, {{ $i }})"
                    @keydown="handleKeydown($event, {{ $i }})"
                    @paste="handlePaste($event)"
                    class="w-12 h-14 text-center text-xl font-bold bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-brand-500 focus:ring-0 transition-all duration-150 text-slate-800 dark:text-slate-100"
                    {{ $i === 0 ? 'autofocus' : '' }}
                >
            @endfor
        </div>

        <button type="submit"
                :disabled="!canSubmit"
                :class="canSubmit ? 'bg-gradient-to-r from-brand-500 to-violet-500 hover:from-brand-600 hover:to-violet-600 opacity-100 shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                class="w-full py-3 text-white font-semibold rounded-lg transition-all duration-200 text-sm mb-4">
            Verify Account
        </button>

        {{-- Verification Options --}}
        <div x-data="{ open: false }">
            <p @click="open = !open"
               class="text-center text-sm text-slate-500 hover:text-brand-600 cursor-pointer transition-colors">
                Didn't receive the code? <span class="font-semibold text-brand-500">Try another method</span>
            </p>

            {{-- Modal for Methods --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                 style="display: none;">
                <div @click.outside="open = false"
                     class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6 animate-slide-up">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 text-center">Resend Verification Code</h3>

                    <div class="space-y-3">
                        <form action="{{ url('send-verification-otp') }}" method="POST">
                            @csrf
                            <input type="hidden" name="identifier" value="{{ $identifier }}">
                            <input type="hidden" name="method" value="email">
                            <button class="w-full flex items-center justify-center gap-3 py-2.5 px-4 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors text-sm font-medium text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Resend to Email
                            </button>
                        </form>

                        <form action="{{ url('send-verification-otp') }}" method="POST">
                            @csrf
                            <input type="hidden" name="identifier" value="{{ $identifier }}">
                            <input type="hidden" name="method" value="phone">
                            <button class="w-full flex items-center justify-center gap-3 py-2.5 px-4 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors text-sm font-medium text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Resend to SMS
                            </button>
                        </form>
                    </div>

                    <button @click="open = false" class="w-full mt-4 py-2 text-slate-400 hover:text-slate-600 text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </form>

    <p class="text-center text-sm text-slate-500 mt-10">
        Wrong account?
        <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-600 font-semibold">Sign in here</a>
    </p>
</div>

@endsection