@php
    $navItems = [
        [
            "label" => "Dashboard",
            "route" => "dashboard.index",
            "pattern" => "dashboard.*",
        ],
        [
            "label" => "Customers",
            "route" => "customers.index",
            "pattern" => "customers.*",
        ],
        [
            "label" => "Products",
            "route" => "products.index",
            "pattern" => "products.*",
            
        ],
        [
            "label" => "Orders",
            "route" => "orders.index",
            "pattern" => "orders.*",
            
        ],
    ];
@endphp

<aside class="w-full text-white bg-gray-800 shadow-xl lg:fixed lg:inset-y-0 lg:left-0 lg:h-screen lg:w-72">
    <div class="flex flex-col h-full px-5 py-4">
        <a href="{{ route("dashboard.index") }}" class="flex items-center gap-3 pb-3 font-bold transition border-b text-1xl border-white/10 hover:opacity-90">
            <img src="{{ asset("favicon.svg") }}" class="h-10 w-10 rounded-2xl bg-white/10 p-1.5" alt="logo">
            <div>
                <span  style="color: #76d141">Order</span>
                <span  style="color: #facc15">Management</span>
            </div>
        </a>

        

        <nav class="flex flex-col gap-2 mt-6">
            @foreach($navItems as $item)
                <a
                    href="{{ route($item["route"]) }}"
                    class="sidebar-nav-link {{ request()->routeIs($item["pattern"]) ? "sidebar-nav-link-active" : "sidebar-nav-link-inactive" }}"
                >
                    <span class="font-semibold text-m">{{ $item["label"] }}</span>
                    <span class="text-1xl {{ request()->routeIs($item["pattern"]) ? "text-slate-500" : "text-slate-300" }}">
                    
                    </span>
                </a>
            @endforeach
            
        </nav>
        <form method="POST" action="{{ route("logout") }}" class="mt-auto">
            @csrf

            <button type="submit"
                class="flex items-center justify-center w-full gap-2 px-4 py-2 mt-6 text-sm font-semibold text-white transition bg-red-600 rounded-lg hover:bg-red-700">
                Logout
            </button>
        </form>
        
        
    </div>
</aside>
