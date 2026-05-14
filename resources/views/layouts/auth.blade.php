<!DOCTYPE html>
<html lang="en" class="h-full">
    <script>
        // ULTIMATE ANTI-FLASH: Direct variable injection
        (function() {
            const theme = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (!theme && systemDark);

            const root = document.documentElement;
            if (isDark) {
                root.classList.add('dark');
                root.style.colorScheme = 'dark';
                root.style.setProperty('--surface-bg', '#0f172a');
                root.style.setProperty('--text-primary', '#f1f5f9');
            } else {
                root.classList.remove('dark');
                root.style.colorScheme = 'light';
                root.style.setProperty('--surface-bg', '#f8fafc');
                root.style.setProperty('--text-primary', '#0f172a');
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon2.svg') }}">
    <title>@yield('title', 'Sign In') | OMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        .gradient-text {
            @apply bg-clip-text text-transparent bg-gradient-to-r from-brand-600 to-violet-600;
        }
        .hero-pattern {
            background-image: radial-gradient(rgba(99, 102, 241, 0.1) 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="h-full font-sans antialiased bg-[var(--surface-bg)] text-[var(--text-primary)] overflow-hidden">

    <div class="h-screen flex flex-col lg:flex-row overflow-hidden">

        <!-- Left: Brand Panel -->
        <div class="hidden lg:flex w-[480px] h-full relative overflow-hidden shrink-0 flex-col justify-between p-12 bg-[var(--surface-sidebar)] border-r border-[var(--border)] z-10 hero-pattern">
            
            <!-- Glowing Blobs -->
            <div class="absolute top-1/4 -right-20 w-64 h-64 bg-brand-500/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 4s;"></div>
            <div class="absolute bottom-1/4 -left-20 w-80 h-80 bg-violet-500/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 6s;"></div>

            <!-- Top logo -->
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 group w-fit">
                    <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-white">OMS<span class="text-brand-500">Enterprise</span></span>
                </a>
            </div>

            <!-- Center content -->
            <div class="relative z-10 flex-1 flex flex-col justify-center py-16">
                <h2 class="text-4xl font-black text-white leading-[1.15] mb-4 tracking-tight">
                    Manage Orders<br><span class="gradient-text">With Precision.</span>
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed mb-10 font-medium">
                    A powerful platform to streamline your order operations, track shipments, and grow your business.
                </p>

                <!-- Feature list -->
                <div class="space-y-2">
                    @foreach([
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Full order lifecycle management'],
                        ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'text' => 'Real-time notifications & alerts'],
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text' => 'Dashboard analytics & reporting'],
                    ] as $feat)
                    <div class="flex items-center gap-4 group">
                        <div class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 group-hover:border-brand-500/50 transition-all duration-300">
                            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feat['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 text-sm font-semibold">{{ $feat['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom tagline -->
            <div class="relative z-10">
                <p class="text-slate-500 dark:text-slate-500 text-[10px] font-bold tracking-widest uppercase">© {{ date('Y') }} OMS ENTERPRISE · Built for modern businesses</p>
            </div>
        </div>

        <!-- Right: Form Area -->
        <div class="flex-1 flex flex-col items-center p-8 bg-[var(--surface-bg)] overflow-y-auto">
            <div class="w-full max-w-md py-8 my-auto shrink-0">
                @yield('content')
            </div>
        </div>

    </div>

</body>
</html>