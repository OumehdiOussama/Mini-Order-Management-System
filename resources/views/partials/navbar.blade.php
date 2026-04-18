<nav class="p-4 text-white bg-blue-600 shadow-lg">
    <div class="container flex items-center justify-between mx-auto">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-2xl font-bold transition hover:opacity-80">
            <img src="{{ asset('favicon.svg') }}" class="w-8 h-8" alt="logo">
            <span style="color: #76d141">Order</span>
            <span style="color: #facc15">Management</span>
        </a>

        <div class="flex space-x-6">
            <a href="{{ route('dashboard') }}" class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-blue-700 font-bold' : '' }}">Dashboard</a>
            <a href="{{ route('customers.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('customers.*') ? 'bg-blue-700 font-bold' : '' }}">Customers</a>
            <a href="{{ route('products.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('products.*') ? 'bg-blue-700 font-bold' : '' }}">Products</a>
            <a href="{{ route('orders.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('orders.*') ? 'bg-blue-700 font-bold' : '' }}">Orders</a>
        </div>

    </div>
</nav>