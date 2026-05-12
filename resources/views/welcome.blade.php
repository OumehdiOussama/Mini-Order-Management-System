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

    <!-- Inter & Tajawal Fonts: non-render-blocking async load -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap">
    </noscript>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js pinned to stable version -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .gradient-text {
            @apply bg-clip-text text-transparent bg-gradient-to-r from-brand-600 to-violet-600;
        }
        .hero-pattern {
            background-image: radial-gradient(var(--color-brand-200) 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        .dark .hero-pattern {
            background-image: radial-gradient(rgba(99, 102, 241, 0.1) 0.5px, transparent 0.5px);
        }
    </style>
</head>
<body class="bg-[var(--surface-bg)] text-[var(--text-primary)] font-sans antialiased overflow-x-hidden">

    <!-- ── Navigation ── -->
    <nav class="fixed top-0 w-full z-50 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md transition-all duration-300"
         x-data="{ mobileMenuOpen: false, scrolled: false }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'h-16 shadow-sm' : 'h-20'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-brand-600 rounded-lg flex items-center justify-center shadow-lg shadow-brand-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-xl font-extrabold tracking-tight">OMS<span class="text-brand-600">Enterprise</span></span>
            </div>

            <!-- Desktop Links -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="#features" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition">{{ __('Features') }}</a>
                <a href="#workflow" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition">{{ __('Workflow') }}</a>
                <a href="#faq" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition">{{ __('FAQ') }}</a>
                
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
                    <a href="{{ route('register') }}" class="btn-primary py-2.5 px-6 rounded-full shadow-brand-500/20">{{ __('Get Started') }}</a>
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
            <a href="#faq" @click="mobileMenuOpen = false" class="block p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold">{{ __('FAQ') }}</a>
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
    <section class="relative pt-32 lg:pt-52 pb-20 lg:pb-40 hero-pattern overflow-hidden">
        <!-- Floating shapes -->
        <div class="absolute top-1/4 -right-20 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 -left-20 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-4xl mx-auto mb-16 animate-slide-up">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-50 dark:bg-brand-900/30 border border-brand-100 dark:border-brand-800 rounded-full mb-6">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 dark:text-brand-300">New: Enterprise Workflow Engine v2.0</span>
                </div>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tight leading-[1.1] mb-8">
                    Smart Order Management <br />
                    <span class="gradient-text">Built for Modern SaaS</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto mb-10 leading-relaxed">
                    Automate your logistics lifecycle from intake to delivery. Monitor real-time analytics, manage inventory, and delight customers with transparency.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 items-center">
                    <a href="{{ route('register') }}" class="btn-primary py-4 px-10 rounded-full text-lg shadow-xl shadow-brand-500/25 transition-transform hover:-translate-y-1">
                        {{ __('Get Started for Free') }}
                    </a>
                    <a href="#workflow" class="btn-ghost py-4 px-10 rounded-full text-lg border border-slate-200 dark:border-slate-800 transition-transform hover:-translate-y-1">
                        {{ __('See the Workflow') }}
                    </a>
                </div>
            </div>

            <!-- Mockup -->
            <div class="relative max-w-5xl mx-auto mt-20 animate-slide-up" style="animation-delay: 0.1s">
                <div class="absolute -inset-1 bg-gradient-to-r from-brand-500 to-violet-600 rounded-[2.5rem] blur opacity-20"></div>
                <div class="relative bg-white dark:bg-slate-900 rounded-2xl border-4 border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden p-2">
                    <img src="{{ asset('dashboard_mockup.png') }}" alt="Dashboard Mockup" class="w-full h-auto rounded-xl shadow-inner">
                </div>
            </div>

            <!-- Trusted By -->
            <div class="mt-20 lg:mt-32 text-center animate-fade-in" style="animation-delay: 0.3s">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-8">{{ __('Trusted by fast-growing companies') }}</p>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                    @foreach(['LogiTech', 'CloudShip', 'SpeedOrder', 'GlobalSync', 'RetailFlow'] as $brand)
                        <span class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $brand }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ── Stats Section ── -->
    <section class="py-12 border-y border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $stats = [
                        ['val' => '99.9%', 'label' => 'Uptime SLA', 'color' => 'text-brand-600'],
                        ['val' => '1.2M+', 'label' => 'Orders Processed', 'color' => 'text-emerald-600'],
                        ['val' => '24/7', 'label' => 'Global Support', 'color' => 'text-amber-600'],
                        ['val' => '0.5s', 'label' => 'API Latency', 'color' => 'text-violet-600'],
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
    <section id="features" class="py-24 lg:py-32 bg-white dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-4">{{ __('Features') }}</h2>
                <h3 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white leading-tight">Everything you need to <span class="gradient-text">scale your logistics</span></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $features = [
                        [
                            'title' => 'Order Tracking',
                            'desc' => 'Real-time visibility into every order status, from pending to delivery.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                            'color' => 'brand'
                        ],
                        [
                            'title' => 'Inventory Sync',
                            'desc' => 'Automatic inventory updates across all your sales channels.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>',
                            'color' => 'violet'
                        ],
                        [
                            'title' => 'Smart Analytics',
                            'desc' => 'Powerful data visualization to identify trends and optimize performance.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                            'color' => 'emerald'
                        ],
                        [
                            'title' => 'Secure RBAC',
                            'desc' => 'Granular roles and permissions to keep your data protected at all times.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                            'color' => 'amber'
                        ],
                    ];
                @endphp

                @foreach($features as $f)
                <div class="card p-8 group hover:shadow-xl hover:shadow-slate-200 dark:hover:shadow-slate-900 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-{{ $f['color'] }}-100 dark:bg-{{ $f['color'] }}-900/30 flex items-center justify-center mb-6 text-{{ $f['color'] }}-600 dark:text-{{ $f['color'] }}-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $f['icon'] !!}
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">{{ $f['title'] }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── Workflow Section ── -->
    <section id="workflow" class="py-24 lg:py-32 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                <div class="lg:w-1/2">
                    <h2 class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-4">{{ __('How It Works') }}</h2>
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-8">From order to doorstep <span class="text-brand-600">in 4 steps</span></h3>
                    
                    <div class="space-y-10">
                        @php
                            $steps = [
                                ['t' => 'Create Order', 'd' => 'Client or staff creates a new order in the system with products and customer details.', 'icon' => 'plus'],
                                ['t' => 'Process & Approve', 'd' => 'Admins review and confirm the order, moving it into the processing queue.', 'icon' => 'check'],
                                ['t' => 'Ship & Track', 'd' => 'The order is picked, packed, and shipped. Real-time tracking IDs are generated automatically.', 'icon' => 'truck'],
                                ['t' => 'Deliver & Delight', 'd' => 'Customers receive their packages and can leave feedback. The order is archived in history.', 'icon' => 'home'],
                            ];
                        @endphp
                        @foreach($steps as $idx => $s)
                        <div class="relative pl-12 group">
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-white dark:bg-slate-800 border-2 border-brand-500 flex items-center justify-center font-bold text-brand-600 z-10 transition-colors group-hover:bg-brand-500 group-hover:text-white">
                                {{ $idx + 1 }}
                            </div>
                            @if($idx < 3)
                            <div class="absolute left-[15px] top-8 w-px h-12 bg-slate-200 dark:bg-slate-800"></div>
                            @endif
                            <h4 class="text-xl font-bold mb-2">{{ $s['t'] }}</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">{{ $s['d'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:w-1/2 relative group">
                    <div class="absolute -inset-4 bg-brand-500/10 rounded-[2.5rem] blur-2xl group-hover:opacity-100 opacity-0 transition duration-500"></div>
                    <div class="relative bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-xl">
                        <div class="space-y-4">
                            @for($i=0; $i<3; $i++)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800 animate-pulse" style="animation-delay: {{ $i * 0.2 }}s">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-brand-100 dark:bg-brand-900/30"></div>
                                    <div class="space-y-2">
                                        <div class="w-24 h-2.5 bg-slate-200 dark:bg-slate-800 rounded"></div>
                                        <div class="w-16 h-2 bg-slate-100 dark:bg-slate-800/50 rounded"></div>
                                    </div>
                                </div>
                                <div class="w-12 h-6 bg-emerald-100 dark:bg-emerald-900/30 rounded-full"></div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Why Us Section ── -->
    <section class="py-24 lg:py-32 bg-white dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1 grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="space-y-4 sm:space-y-6">
                        <div class="card p-6 bg-brand-50/50 dark:bg-brand-900/10 border-brand-100 dark:border-brand-800 transform translate-y-8">
                            <h5 class="text-2xl font-bold text-brand-600 mb-2">99%</h5>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Customer Satisfaction</p>
                        </div>
                        <div class="card p-6">
                            <h5 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">50ms</h5>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Data Latency</p>
                        </div>
                    </div>
                    <div class="space-y-4 sm:space-y-6">
                        <div class="card p-6">
                            <h5 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">10k+</h5>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Daily Orders</p>
                        </div>
                        <div class="card p-6 bg-violet-50/50 dark:bg-violet-900/10 border-violet-100 dark:border-violet-800 transform -translate-y-8">
                            <h5 class="text-2xl font-bold text-violet-600 mb-2">#1</h5>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">OMS Solution</p>
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <h2 class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-4">{{ __('Why Choose Us') }}</h2>
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-8">Performance that drives <span class="text-brand-600">business growth</span></h3>
                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-8 leading-relaxed font-medium">
                        Our infrastructure is built for speed and reliability. We ensure your orders are processed instantly, giving you a competitive edge in a fast-paced market.
                    </p>
                    <ul class="space-y-4 mb-10">
                        @foreach(['Ultra-fast data indexing', 'Hyper-accurate real-time inventory', 'Automated email/SMS notifications', 'Bank-level data encryption'] as $item)
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-semibold">
                            <div class="w-5 h-5 rounded-full bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ── FAQ Section ── -->
    <section id="faq" class="py-24 lg:py-32 bg-slate-50 dark:bg-slate-900/30" x-data="{ active: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-4">{{ __('FAQ') }}</h2>
                <h3 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-8">Got questions? <br /> We have <span class="text-brand-600">answers</span></h3>
            </div>

            <div class="space-y-4">
                @php
                    $faqs = [
                        ['q' => 'Is this system suitable for small businesses?', 'a' => 'Yes! OMS Enterprise scales from single-store startups to large multinational corporations with thousands of orders per hour.'],
                        ['q' => 'How secure is my data?', 'a' => 'We use AES-256 encryption for data at rest and TLS 1.3 for data in transit. Your business data is isolated and backed up hourly.'],
                        ['q' => 'Can I integrate with other platforms?', 'a' => 'Absolutely. We provide a robust REST API and pre-built webhooks to connect with Shopify, Amazon, WooCommerce, and more.'],
                        ['q' => 'What kind of support do you offer?', 'a' => 'We offer 24/7 technical support via live chat, email, and dedicated account managers for enterprise plans.'],
                    ];
                @endphp
                @foreach($faqs as $idx => $faq)
                <div class="card p-2 overflow-hidden transition-all duration-300" :class="active === {{ $idx }} ? 'shadow-lg border-brand-200 dark:border-brand-800' : ''">
                    <button @click="active = (active === {{ $idx }} ? null : {{ $idx }})"
                            class="w-full flex items-center justify-between p-4 text-left font-bold text-slate-800 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition">
                        <span>{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 transition-transform duration-300" :class="active === {{ $idx }} ? 'rotate-180 text-brand-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="active === {{ $idx }}" 
                         x-collapse
                         class="px-4 pb-4 text-sm text-slate-500 dark:text-slate-400 leading-relaxed"
                         x-cloak>
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── Final CTA ── -->
    <section class="py-24 lg:py-40 relative overflow-hidden bg-slate-950">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-900/50 to-slate-950 z-0"></div>
        <div class="absolute top-0 left-0 w-full h-full hero-pattern opacity-10"></div>
        
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-8">Ready to transform your <span class="text-brand-500">order operations?</span></h2>
            <p class="text-xl text-slate-400 mb-12 font-medium">Join 50,000+ businesses automating their logistics today.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6 items-center">
                <a href="{{ route('register') }}" class="btn-primary py-5 px-12 rounded-full text-xl shadow-2xl shadow-brand-500/20">Start Free Trial</a>
                <a href="#" class="text-white font-bold text-lg hover:text-brand-400 transition underline underline-offset-8">Contact Sales</a>
            </div>
        </div>
    </section>

    <!-- ── Footer ── -->
    <footer class="bg-white dark:bg-slate-900 py-20 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-10 h-10 bg-brand-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-2xl font-black tracking-tight">OMS<span class="text-brand-600">Enterprise</span></span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm leading-relaxed mb-8">
                        The ultimate order management platform for high-growth SaaS companies. Scale faster, automate smarter, and delight customers.
                    </p>
                    <div class="flex gap-4">
                        @foreach(['twitter', 'github', 'linkedin'] as $sm)
                        <a href="#" class="p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl hover:bg-brand-50 dark:hover:bg-brand-900/30 hover:text-brand-600 dark:hover:text-brand-400 transition text-slate-400">
                            <span class="sr-only">{{ $sm }}</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.82c0-1.065-.453-1.834-1.322-1.834-.657 0-1.132.441-1.332 1.017-.073.203-.122.463-.122.726v2.911h-1.998v-6h1.998v.826c.282-.425.769-1.026 1.868-1.026 1.365 0 2.842 1.232 2.842 3.528v3.672z"/></svg>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-xs">Platform</h5>
                    <ul class="space-y-4 text-sm font-semibold text-slate-500 dark:text-slate-400">
                        <li><a href="#features" class="hover:text-brand-600 transition">Features</a></li>
                        <li><a href="#workflow" class="hover:text-brand-600 transition">Workflow</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">Integrations</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">SaaS Dashboard</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-xs">Resources</h5>
                    <ul class="space-y-4 text-sm font-semibold text-slate-500 dark:text-slate-400">
                        <li><a href="#" class="hover:text-brand-600 transition">Documentation</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">API Reference</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">Support Center</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">Community</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-xs">Company</h5>
                    <ul class="space-y-4 text-sm font-semibold text-slate-500 dark:text-slate-400">
                        <li><a href="#" class="hover:text-brand-600 transition">About Us</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">Careers</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-12 mt-12 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-sm font-bold text-slate-400">© {{ date('Y') }} OMS Inc. Engineered in 2026.</p>
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2 text-xs font-bold text-emerald-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        All Systems Operational
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chatbot Component -->
    <x-chatbot />

</body>
</html>
