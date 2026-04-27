<header class="sticky top-0 z-40 h-16 flex items-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-5 sm:px-6 lg:px-8 gap-4">

    {{-- Page Title --}}
    <div class="flex-1 min-w-0">
        <h1 class="text-base font-semibold text-slate-800 dark:text-white truncate">
            @yield('title', 'Dashboard')
        </h1>
    </div>

    {{-- Right Controls --}}
    <div class="flex items-center gap-2">

        {{-- Dark Mode Toggle --}}
        <button @click="toggle()"
                class="btn-icon text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800"
                :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
            {{-- Sun --}}
            <svg x-show="isDark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            {{-- Moon --}}
            <svg x-show="!isDark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        {{-- Notifications Bell --}}
        @include('partials.notifications-dropdown')

        {{-- User Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-150">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0"
                     style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 leading-none">
                        {{ auth()->user()->name ?? 'User' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">Administrator</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-150"
                     :class="open ? 'rotate-180' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                 @click.outside="open = false"
                 class="absolute right-0 top-full mt-2 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-dropdown border border-slate-200 dark:border-slate-700 py-1 z-50">

                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>

                <a href="{{ route('profile') }}"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-100">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile & Settings
                </a>

                <div class="border-t border-slate-100 dark:border-slate-700 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors duration-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>