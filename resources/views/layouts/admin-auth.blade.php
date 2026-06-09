<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login Admin') — Rumble Yard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-[Inter] antialiased">
    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="fixed inset-0 -z-10">
            <img src="https://images.unsplash.com/photo-1567220720374-a67f33b2a6b9?q=80&w=1632&auto=format&fit=crop"
                 alt="" class="h-full w-full object-cover scale-105 blur-[3px]" aria-hidden="true">
            <div class="absolute inset-0 bg-white/30"></div>
        </div>

        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
