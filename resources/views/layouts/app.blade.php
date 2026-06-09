<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rumble Yard') — Main Jadi Lebih Mudah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    @include('layouts.partials.navbar')

    @if(session('success') || session('error') || session('status'))
        <div class="max-w-7xl mx-auto w-full px-4 pt-4">
            @include('components.alert')
        </div>
    @endif

    <main class="flex-1">@yield('content')</main>

    @include('layouts.partials.footer')
    @stack('scripts')
</body>
</html>
