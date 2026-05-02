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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased bg-[var(--surface-bg)] text-[var(--text-primary)]">

    <div class="min-h-screen flex">

        <!-- Left: Brand Panel -->
        <div class="auth-brand-panel w-[480px] relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-white/5 rounded-full"></div>
            <div class="absolute top-1/3 right-8 w-32 h-32 bg-white/5 rounded-full"></div>

            <!-- Top logo -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl tracking-tight">Order Management System</span>
                </div>
            </div>

            <!-- Center content -->
            <div class="relative z-10 flex-1 flex flex-col justify-center py-16">
                <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                    Manage Orders<br>Like a Pro.
                </h2>
                <p class="text-indigo-100 text-lg leading-relaxed mb-10">
                    A powerful platform to streamline your order operations, track shipments, and grow your business.
                </p>

                <!-- Feature list -->
                <div class="space-y-4">
                    @foreach([
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Full order lifecycle management'],
                        ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'text' => 'Real-time notifications & alerts'],
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text' => 'Dashboard analytics & reporting'],
                    ] as $feat)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feat['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-indigo-100 text-sm font-medium">{{ $feat['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom tagline -->
            <div class="relative z-10">
                <p class="text-indigo-200 text-sm">© {{ date('Y') }} OrderFlow · Built for modern businesses</p>
            </div>
        </div>

        <!-- Right: Form Area -->
        <div class="flex-1 flex items-center justify-center p-8 bg-[var(--surface-bg)]">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>

    </div>

</body>
</html>