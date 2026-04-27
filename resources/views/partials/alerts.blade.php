{{-- ══════════════════════════════════════════
    ALERTS — Toast-style flash messages
══════════════════════════════════════════ --}}

@if(session('success'))
<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 5000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="fixed top-20 right-5 z-50 max-w-sm w-full">
    <div class="flex items-start gap-3 px-4 py-3.5 bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-800 rounded-xl shadow-dropdown">
        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">Success</p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    {{-- Progress bar --}}
    <div class="h-0.5 bg-emerald-500 rounded-full mt-1 transition-all duration-[5000ms] ease-linear w-full"
         x-init="$nextTick(() => { $el.style.width = '0%'; })">
    </div>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 6000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="fixed top-20 right-5 z-50 max-w-sm w-full">
    <div class="flex items-start gap-3 px-4 py-3.5 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-800 rounded-xl shadow-dropdown">
        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/40 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-red-800 dark:text-red-300">Error</p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
@endif

@if($errors->any() && !request()->routeIs('profile'))
<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 7000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="fixed top-20 right-5 z-50 max-w-sm w-full">
    <div class="flex items-start gap-3 px-4 py-3.5 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-800 rounded-xl shadow-dropdown">
        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/40 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-red-800 dark:text-red-300">Validation Error</p>
            <ul class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 space-y-0.5 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
@endif