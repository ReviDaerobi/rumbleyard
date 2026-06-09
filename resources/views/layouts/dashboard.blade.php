<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Rumble Yard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface">
    <div class="flex min-h-screen">
        @include('layouts.partials.sidebar')
        <div class="flex-1 flex flex-col lg:ml-64">
            @include('layouts.partials.dashboard-header')
            <main class="flex-1 p-4 lg:p-8">
                @include('components.alert')
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
