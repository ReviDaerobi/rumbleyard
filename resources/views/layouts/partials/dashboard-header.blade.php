<header class="glass border-b px-4 lg:px-8 py-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold">@yield('header', 'Dashboard')</h1>
        <p class="text-sm text-gray-500">@yield('subheader')</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600 hidden sm:inline">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn-outline text-xs py-2">Logout</button>
        </form>
    </div>
</header>
