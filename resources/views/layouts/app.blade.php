<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Order Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-gray-100">

    @include('partials.navbar')

    <div class="flex flex-col flex-1">

        <main class="container flex-1 px-4 mx-auto mt-8">

            @include('partials.alerts')

            @yield('content')

        </main>

        @include('partials.footer')

    </div>

    @include('partials.modal')

</body>
</html>