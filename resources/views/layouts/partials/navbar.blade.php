<nav class="sticky top-0 z-50 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-xl text-figma-navy">
                Rumble Yard
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-heading">
                @auth
                    <a href="{{ route('bookings.history') }}" class="hover:text-figma-blue transition">Pesanan Saya</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-figma-blue transition">Pesanan Saya</a>
                @endauth
                <a href="{{ route('venues.index') }}" class="hover:text-figma-blue transition">Cari Lapangan</a>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('venues.index') }}" class="hidden sm:inline-flex h-9 w-9 items-center justify-center rounded-lg text-body hover:bg-figma-surface hover:text-figma-blue transition" aria-label="Cari Lapangan">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-nav">
                            Admin
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </span>
                        </a>
                    @elseif(auth()->user()->isVenueOwner())
                        <a href="{{ route('owner.dashboard') }}" class="btn-nav">
                            Owner
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </span>
                        </a>
                    @endif
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="btn-nav hidden sm:inline-flex">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="btn-outline text-xs uppercase">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-nav">Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
