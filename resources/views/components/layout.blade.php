<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My App' }}</title>
</head>
<body class="bg-gray-100 text-gray-900">
    <header class="bg-blue-600 text-white p-4">
        <h1 class="text-xl font-bold">{{ $header ?? 'Welcome' }}</h1>
    </header>

    <main class="container mx-auto p-4">
        {{ $slot }}
    </main>

    <footer class="bg-gray-800 text-white text-center p-4 mt-4">
        &copy; {{ date('Y') }} My App. All rights reserved.
    </footer>
</body>
</html>

