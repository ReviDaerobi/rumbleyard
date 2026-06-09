<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth') — Rumble Yard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-[Inter] antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row">
        @yield('visual')
        <div class="flex-1 flex items-center justify-center px-6 py-10 lg:px-16 lg:py-12 bg-white">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
