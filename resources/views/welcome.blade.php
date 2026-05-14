<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              document.documentElement.classList.toggle('dark', this.darkMode);
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
          }
      }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Welcome') }} | OMS Enterprise</title>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon2.svg') }}">

    <!-- Inter & Tajawal Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .gradient-text {
            @apply bg-clip-text text-transparent bg-gradient-to-r from-brand-600 to-violet-600;
        }
        .hero-pattern {
            background-image: radial-gradient(rgba(99, 102, 241, 0.1) 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        .glass-nav {
            @apply bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-800/50;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased overflow-x-hidden">

    <!-- ── Navigation ── -->
    <nav class="fixed top-0 w-full z-50 bg-white dark:bg-slate-900 border-b border-slate-200/50 dark:border-slate-800/50 transition-all duration-300"
         x-data="{ mobileMenuOpen: false, scrolled: false }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'h-16 shadow-lg shadow-brand-500/5' : 'h-20'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-brand-600 rounded-lg flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-xl font-extrabold tracking-tight">OMS<span class="text-brand-600">Enterprise</span></span>
            </a>

            <!-- Desktop Links -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="#features" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition">{{ __('Features') }}</a>
                <a href="#workflow" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition">{{ __('Workflow') }}</a>
                
                <div class="h-4 w-px bg-slate-200 dark:bg-slate-700"></div>

                <!-- Theme Toggle -->
                <button @click="toggleTheme()" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                @auth
                    <a href="{{ route('dashboard.index') }}" class="btn-primary py-2 px-6 rounded-full">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-brand-600 transition">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn-primary py-2.5 px-6 rounded-full shadow-brand-500/20">{{ __('Join Now') }}</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 dark:text-slate-400">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 p-4 space-y-3 shadow-xl"
             x-cloak>
            <a href="#features" @click="mobileMenuOpen = false" class="block p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold">{{ __('Features') }}</a>
            <a href="#workflow" @click="mobileMenuOpen = false" class="block p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold">{{ __('Workflow') }}</a>
            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 gap-3">
                @auth
                    <a href="{{ route('dashboard.index') }}" class="btn-primary justify-center">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost justify-center">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn-primary justify-center">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ── Hero Section ── -->
    <section class="relative pt-20 lg:pt-32 pb-12 lg:pb-24 hero-pattern overflow-hidden">
        <div class="absolute top-1/4 -right-20 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 -left-20 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-4xl mx-auto mb-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-50 dark:bg-brand-900/30 border border-brand-100 dark:border-brand-800 rounded-full mb-5">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 dark:text-brand-300">Modern Order Management</span>
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-5">
                    Smart Operations <br />
                    <span class="gradient-text">Tailored for Efficiency</span>
                </h1>
                <p class="text-base md:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-8 leading-relaxed font-medium">
                    Maintain full control over your logistics lifecycle. Real-time inventory tracking, intuitive order management, and multi-role dashboards built to scale with your business needs.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 items-center">
                    <a href="{{ route('register') }}" class="btn-primary py-3.5 px-8 rounded-full text-base shadow-xl shadow-brand-500/25 transition-transform hover:-translate-y-1">
                        {{ __('Get Started Now') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Stats Section ── -->
    <section class="py-12 border-y border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $stats = [
                        ['val' => '10k+', 'label' => 'Monthly Orders', 'color' => 'text-brand-600'],
                        ['val' => '99.9%', 'label' => 'System Uptime', 'color' => 'text-emerald-600'],
                        ['val' => '500+', 'label' => 'Active Products', 'color' => 'text-amber-600'],
                        ['val' => '0.5s', 'label' => 'Avg. Latency', 'color' => 'text-violet-600'],
                    ];
                @endphp
                @foreach($stats as $s)
                <div class="text-center group">
                    <p class="text-3xl md:text-4xl font-black {{ $s['color'] }} mb-1 transition-transform group-hover:scale-110 duration-300">{{ $s['val'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $s['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── Features Section ── -->
    <section id="features" class="py-16 lg:py-24 bg-white dark:bg-slate-950 relative overflow-hidden">
        <!-- Floating Blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-brand-500/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-violet-500/5 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-4">{{ __('Core Capabilities') }}</h2>
                <h3 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white leading-tight">Engineered for <span class="gradient-text">Complete Transparency</span></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        [
                            'title' => 'Inventory Tracking',
                            'desc' => 'Maintain accurate stock levels with automatic deductions and restorations on every transaction.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
                            'color' => 'brand'
                        ],
                        [
                            'title' => 'Lifecycle Tracking',
                            'desc' => 'End-to-end visibility of every order status from creation to final delivery.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                            'color' => 'violet'
                        ],
                        [
                            'title' => 'Secure RBAC',
                            'desc' => 'Granular role-based access control for Administrators and Customers.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
                            'color' => 'emerald'
                        ],
                        [
                            'title' => 'Smart Filtering',
                            'desc' => 'Advanced search and filtering capabilities across Products, Customers, and Orders.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />',
                            'color' => 'blue'
                        ],
                        [
                            'title' => 'Multi-role Portal',
                            'desc' => 'Dedicated dashboards for different user types to ensure a personalized experience.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
                            'color' => 'amber'
                        ],
                        [
                            'title' => 'Responsive UI',
                            'desc' => 'Seamless experience on mobile, tablet, and desktop for management on the go.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />',
                            'color' => 'rose'
                        ],
                    ];
                @endphp

                @foreach($features as $f)
                <div class="group p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center mb-6 text-brand-600 dark:text-brand-400 group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            {!! $f['icon'] !!}
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-900 dark:text-white">{{ $f['title'] }}</h4>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed font-medium text-sm">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── Workflow Section ── -->
    <section id="workflow" class="py-16 lg:py-24 bg-slate-900 text-white relative overflow-hidden">
        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#6366f1 0.5px, transparent 0.5px); background-size: 30px 30px;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <h2 class="text-sm font-bold text-brand-500 uppercase tracking-widest mb-4">{{ __('Streamlined Process') }}</h2>
                <h3 class="text-3xl md:text-5xl font-black mb-8">How the Platform <span class="text-brand-500">Works</span></h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                @php
                    $steps = [
                        ['t' => 'Order Placement', 'd' => 'Customers select products and place orders through the intuitive system.', 'icon' => 'plus'],
                        ['t' => 'Admin Review', 'd' => 'Admins review orders, update stock levels, and prepare for fulfillment.', 'icon' => 'check'],
                        ['t' => 'Status Updates', 'd' => 'Real-time visibility into the order lifecycle from processing to delivery.', 'icon' => 'truck'],
                        ['t' => 'Data Reporting', 'd' => 'Completed orders are archived for future analytics and performance tracking.', 'icon' => 'home'],
                    ];
                @endphp
                @foreach($steps as $idx => $s)
                <div class="relative group text-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-white/5 border border-white/10 mx-auto flex items-center justify-center font-black text-3xl text-brand-500 mb-8 group-hover:bg-brand-500 group-hover:text-white group-hover:rotate-12 transition-all duration-500 shadow-2xl">
                        {{ $idx + 1 }}
                    </div>
                    <h4 class="text-xl font-bold mb-4">{{ $s['t'] }}</h4>
                    <p class="text-sm text-slate-400 leading-relaxed font-medium">{{ $s['d'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── CTA Section ── -->
    <section class="py-20 lg:py-28 relative overflow-hidden bg-white dark:bg-slate-950">
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h2 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white mb-8 tracking-tight">Ready to <span class="gradient-text">Get Started?</span></h2>
            <p class="text-xl text-slate-500 dark:text-slate-400 mb-12 font-medium">Join our platform today and manage your orders with professional precision.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6 items-center">
                <a href="{{ route('register') }}" class="btn-primary py-5 px-14 rounded-2xl text-xl font-bold shadow-2xl shadow-brand-500/30 transition-transform hover:-translate-y-1 active:scale-95">
                    {{ __('Create Free Account') }}
                </a>
            </div>
        </div>
    </section>

    <!-- ── Premium Footer ── -->
    <footer class="bg-slate-50 dark:bg-slate-950 pt-24 pb-12 border-t border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 lg:gap-8 mb-20">
                <!-- Brand Column -->
                <div class="lg:col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-8">
                        <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-2xl font-black tracking-tight">OMS<span class="text-brand-600">Enterprise</span></span>
                    </a>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm leading-relaxed mb-8 font-medium text-sm">
                        Streamlining the bridge between businesses and customers through intelligent order management and real-time inventory synchronization.
                    </p>
                    <div class="flex gap-4">
                        @php
                            $socials = [
                                'x' => '<path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>',
                                'github' => '<path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>',
                                'linkedin' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 11.002-4.125 2.062 2.062 0 01-.002 4.125zm1.777 13.019H3.56V9h3.554v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>',
                                'instagram' => '<path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>',
                            ];
                        @endphp
                        @foreach($socials as $name => $svg)
                        <a href="#" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-slate-500 hover:bg-brand-600 hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                {!! $svg !!}
                            </svg>
                            <span class="sr-only">{{ ucfirst($name) }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Links Columns -->
                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-xs">Platform</h5>
                    <ul class="space-y-4 text-sm font-bold text-slate-500 dark:text-slate-400">
                        <li><a href="#features" class="hover:text-brand-600 transition-colors">Features</a></li>
                        <li><a href="#workflow" class="hover:text-brand-600 transition-colors">Workflow</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Solutions</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Integrations</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-xs">Resources</h5>
                    <ul class="space-y-4 text-sm font-bold text-slate-500 dark:text-slate-400">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">API Status</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Community</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-xs">Legal</h5>
                    <ul class="space-y-4 text-sm font-bold text-slate-500 dark:text-slate-400">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">SLA</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="pt-8 border-t border-slate-200/60 dark:border-slate-800/60 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                    © {{ date('Y') }} OMS ENTERPRISE. ALL RIGHTS RESERVED.
                </p>
                <div class="flex items-center gap-8">
                    <span class="flex items-center gap-2 text-[10px] font-black text-emerald-500 uppercase tracking-tighter">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Network Operational
                    </span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Built for High-Growth Teams</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chatbot Component -->
    <x-chatbot />

</body>
</html>

    <!-- Chatbot Component -->
    <x-chatbot />

</body>
</html>
