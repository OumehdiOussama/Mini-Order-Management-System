<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset("favicon.svg") }}">
    <title>@yield("title") - Order Management System</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>

<body class="min-h-screen text-gray-900 bg-gray-100">

    @include("partials.sidebar")

    <div class="flex flex-col min-h-screen lg:ml-72">
        @include("partials.header") <!-- header -->
        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
            <div class="w-full mx-auto max-w-7xl">

                @if(!request()->routeIs('profile'))
                    @include("partials.alerts")
                @endif
                
                @yield("content")

            </div>
        </main>

        @include("partials.footer")

    </div>

    @include("partials.modal")

</body>
</html>
