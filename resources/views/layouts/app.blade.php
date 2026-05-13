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

            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                root.classList.add('sidebar-collapsed');
            }
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* 
           VIEW TRANSITIONS API 
           This makes navigation feel like a smooth SPA 
        */
        @view-transition {
            navigation: auto;
        }

        /* Sidebar Layout Control */
        #sidebar { 
            width: 256px; 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            contain: flex; 
            view-transition-name: main-sidebar;
        }
        #main-header {
            view-transition-name: main-header;
        }
        #main-content { 
            margin-left: 256px; 
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }
        
        .sidebar-collapsed #sidebar { width: 72px; }
        .sidebar-collapsed #main-content { margin-left: 72px; }
        
        @media (max-width: 1023px) {
            #main-content { margin-left: 0 !important; }
            #sidebar { transform: translateX(-100%); }
        }

        /* Turbo Progress Bar Color */
        .turbo-progress-bar {
            background-color: #6366f1;
            height: 3px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon2.svg') }}">
    <title>@yield('title', 'Dashboard') | OMS</title>
    <!-- Inter Font: non-render-blocking async load -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js pinned to stable version -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <!-- Turbo: Prevents full page reloads and layout flashes -->
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>

    @stack('head')
</head>

<body class="min-h-screen bg-[var(--surface-bg)] text-[var(--text-primary)] font-sans antialiased">

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Wrapper -->
    <div class="flex flex-col min-h-screen" id="main-content">

        <!-- Top Header -->
        @include('partials.header')

        <!-- Page Content -->
        <main class="flex-1 px-5 py-6 sm:px-6 lg:px-8 lg:py-7">
            <div class="w-full mx-auto max-w-7xl">

                @include('partials.alerts')

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
    </script>

    @stack('scripts')

</body>
</html>
