{{-- ══════════════════════════════════════════════════
    ORDERFLOW SIDEBAR
    Alpine.js powered: collapsible + mobile drawer
══════════════════════════════════════════════════ --}}
@php
    $navSections = [
        [
            'label' => 'Main',
            'items' => [
                [
                    'label'   => 'Dashboard',
                    'route'   => 'dashboard.index',
                    'pattern' => 'dashboard.*',
                    'icon'    => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                ],
                [
                    'label'   => 'Orders',
                    'route'   => 'orders.index',
                    'pattern' => 'orders.*',
                    'icon'    => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                ],
            ],
        ],
        [
            'label' => 'Management',
            'hidden' => auth()->user()->role === 'customer',
            'items' => [
                [
                    'label'   => 'Customers',
                    'route'   => 'customers.index',
                    'pattern' => 'customers.*',
                    'icon'    => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                ],
                [
                    'label'   => 'Products',
                    'route'   => 'products.index',
                    'pattern' => 'products.*',
                    'icon'    => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                ],
            ],
        ],
        [
            'label' => 'Account',
            'items' => [
                [
                    'label'   => 'Settings',
                    'route'   => 'profile',
                    'pattern' => 'profile',
                    'icon'    => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                ],
            ],
        ],
    ];
@endphp

{{-- Mobile Overlay --}}
<div id="sidebar-overlay"
    class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden backdrop-blur-sm"
    onclick="closeSidebar()">
</div>

{{-- Sidebar --}}
<aside id="sidebar"
    x-data="{
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', this.collapsed);
            if (this.collapsed) {
                document.documentElement.classList.add('sidebar-collapsed');
            } else {
                document.documentElement.classList.remove('sidebar-collapsed');
            }
        }
    }"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 border-r border-slate-800
           transition-[width,transform] duration-300 ease-in-out
           -translate-x-full lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center h-16 px-4 border-b border-slate-800 shrink-0">
        <a href="{{ route('dashboard.index') }}" class="flex items-center min-w-0 gap-3">
            <div class="flex items-center justify-center w-9 h-9 bg-brand-500 rounded-xl shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span x-show="!collapsed" x-transition:enter="transition-all duration-200"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  class="overflow-hidden text-lg font-bold tracking-tight text-white whitespace-nowrap">
                OMS
            </span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-2 py-4 space-y-5 overflow-y-auto">
        @foreach($navSections as $section)
        @if(!($section['hidden'] ?? false))
        <div>
            {{-- Section label --}}
            <div x-show="!collapsed" x-transition
                 class="px-3 mb-2 text-xs font-semibold tracking-wider uppercase text-slate-500">
                {{ $section['label'] }}
            </div>

            <div class="space-y-0.5">
                @foreach($section['items'] as $item)
                @if(!($item['hidden'] ?? false))
                @php $isActive = request()->routeIs($item['pattern']); @endphp
                <a href="{{ route($item['route']) }}"
                   :title="collapsed ? '{{ $item['label'] }}' : ''"
                   class="sidebar-nav-link {{ $isActive ? 'sidebar-nav-link-active' : 'sidebar-nav-link-inactive' }}"
                   :class="collapsed ? 'justify-center px-2' : ''">

                    {{-- Icon --}}
                    <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="{{ $item['icon'] }}"/>
                    </svg>

                    {{-- Label --}}
                    <span x-show="!collapsed"
                          x-transition:enter="transition-all duration-150"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="overflow-hidden whitespace-nowrap">
                        {{ $item['label'] }}
                    </span>
                </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </nav>

    {{-- Bottom: User + Collapse --}}
    <div class="p-3 space-y-2 border-t border-slate-800 shrink-0">

        {{-- Collapse Toggle (desktop) --}}
        <button @click="toggle()"
                class="items-center hidden w-full gap-3 px-2 py-2 text-sm transition-all duration-150 rounded-lg lg:flex text-slate-400 hover:text-white hover:bg-slate-800"
                :class="collapsed ? 'justify-center' : ''"
                :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <svg class="w-5 h-5 transition-transform duration-300 shrink-0"
                 :class="collapsed ? 'rotate-180' : ''"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <span x-show="!collapsed" class="text-xs font-medium whitespace-nowrap">Collapse</span>
        </button>

        <div class="flex items-center gap-3 px-2 py-2 rounded-lg"
             :class="collapsed ? 'justify-center' : ''">
            @if(auth()->user()->photo)
                <img src="{{ url('media/' . auth()->user()->photo) }}" 
                     class="w-10 h-10 rounded-xl object-cover ring-2 ring-white/10 shadow-sm border border-white/10">
            @else
                <div class="avatar-sm shrink-0"
                     style="background: linear-gradient(135deg, #6366f1, #8b5cf6); width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:12px; color:white; font-weight:700;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
            @endif
            <div x-show="!collapsed" class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-xs truncate text-slate-400">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    :class="collapsed ? 'justify-center px-2' : ''"
                    class="flex items-center w-full gap-3 px-3 py-2 text-sm font-medium transition-all duration-150 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-500/10">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Logout</span>
            </button>
        </form>
    </div>

</aside>

{{-- Mobile Toggle Button --}}
<button onclick="openSidebar()"
        class="fixed z-30 flex items-center justify-center w-12 h-12 text-white transition-all duration-200 rounded-full shadow-lg lg:hidden bottom-5 right-5 bg-brand-500 hover:bg-brand-600">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }
</script>
