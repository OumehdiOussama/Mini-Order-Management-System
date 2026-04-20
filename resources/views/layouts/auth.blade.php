<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset("favicon.svg") }}">
    <title>@yield("title") - Order Management System</title>
    @vite('resources/css/app.css')
</head>
<body class="flex items-center justify-center min-h-screen px-4 text-gray-100 bg-gray-900">
    @yield('content')
</body>
</html>