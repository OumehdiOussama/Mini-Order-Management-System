<header class="sticky top-0 z-40 bg-white border-b shadow-sm">
    <div class="flex items-center justify-between px-4 py-3.5 sm:px-6 lg:px-10">

        <!-- Title -->
        <h1 class="text-lg font-semibold text-gray-800">
            <!-- @yield("title") -->
        </h1>
        
        <!-- Right -->
        <div class="flex items-center gap-3">

            <!-- Settings -->
            <a href="{{ route('profile') }}"
            class="p-2 text-gray-800 transition hover:text-blue-700 hover:underline">
                Settings
            </a>

            <!-- User -->
            <div class="flex items-center gap-2 p-2 py-1 bg-gray-100 rounded-lg">
                <span class="font-medium text-gray-700 text-md">
                    {{ auth()->user()->name ?? "User" }}
                </span>
            </div>

        </div>
    </div>
</header>