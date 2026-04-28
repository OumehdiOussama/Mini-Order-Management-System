<!DOCTYPE html>
<html lang="en" x-data="darkMode()">
<head>
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
            } else {
                root.classList.remove('dark');
                root.style.colorScheme = 'light';
            }
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', 'Dashboard') — OrderFlow</title>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- HTMX for SPA Navigation -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    
    @stack('head')
</head>

<body hx-boost="true" class="min-h-screen bg-[var(--surface-bg)] text-[var(--text-primary)] font-sans antialiased">

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Wrapper -->
    <div class="flex flex-col min-h-screen" id="main-content"
         style="margin-left: 256px; transition: margin-left 0.3s ease;" x-ref="mainContent">

        <!-- Top Header -->
        @include('partials.header')

        <!-- Page Content -->
        <main class="flex-1 px-5 py-6 sm:px-6 lg:px-8 lg:py-7">
            <div class="w-full mx-auto max-w-7xl">

                @if(!request()->routeIs('profile'))
                    @include('partials.alerts')
                @endif

                @yield('content')

            </div>
        </main>

    </div>

    <!-- Delete Confirmation Modal -->
    @include('partials.modal')

    <!-- Dark Mode Script -->
    <script>
        function darkMode() {
            return {
                isDark: document.documentElement.classList.contains('dark'),
                toggleTheme() {
                    this.isDark = !this.isDark;
                    const root = document.documentElement;
                    if (this.isDark) {
                        root.classList.add('dark');
                        root.style.colorScheme = 'dark';
                        localStorage.setItem('theme', 'dark');
                    } else {
                        root.classList.remove('dark');
                        root.style.colorScheme = 'light';
                        localStorage.setItem('theme', 'light');
                    }
                }
            }
        }

        // Sidebar collapse persistence
        var sidebar = document.getElementById('sidebar');
        var mainContent = document.getElementById('main-content');
        function applySidebarState(collapsed) {
            if (collapsed) {
                mainContent.style.marginLeft = '72px';
            } else {
                mainContent.style.marginLeft = '256px';
            }
        }
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            applySidebarState(true);
        }
        window.addEventListener('sidebar-toggle', (e) => {
            applySidebarState(e.detail.collapsed);
            localStorage.setItem('sidebarCollapsed', e.detail.collapsed);
        });

        // Mobile sidebar
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1024) {
                mainContent.style.marginLeft = '0';
            } else {
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                applySidebarState(collapsed);
            }
        });
        if (window.innerWidth < 1024) mainContent.style.marginLeft = '0';
    </script>

    @stack('scripts')

</body>
</html>
