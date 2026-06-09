@php
    $links = match(true) {
        auth()->user()->isAdmin() => [
            ['route' => 'admin.dashboard', 'label' => 'Overview', 'icon' => 'layout-dashboard'],
        ],
        auth()->user()->isVenueOwner() => [
            ['route' => 'owner.dashboard', 'label' => 'Overview', 'icon' => 'layout-dashboard'],
            ['route' => 'owner.venues.index', 'label' => 'Venue Saya', 'icon' => 'map-pin'],
        ],
        default => [
            ['route' => 'dashboard', 'label' => 'Overview', 'icon' => 'layout-dashboard'],
            ['route' => 'bookings.history', 'label' => 'Riwayat Booking', 'icon' => 'calendar'],
            ['route' => 'favorites.index', 'label' => 'Favorit', 'icon' => 'heart'],
            ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'user'],
        ],
    };
@endphp
<aside class="fixed inset-y-0 left-0 z-40 w-64 glass border-r hidden lg:block">
    <div class="p-6 font-bold text-xl">Rumble Yard</div>
    <nav class="px-3 space-y-1">
        @foreach($links as $link)
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs($link['route'].'*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4"></i>
                {{ $link['label'] }}
            </a>
        @endforeach
        <a href="{{ route('venues.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-gray-600 hover:bg-gray-100">
            <i data-lucide="search" class="w-4 h-4"></i> Cari Lapangan
        </a>
    </nav>
</aside>
