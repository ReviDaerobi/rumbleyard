@extends('layouts.app')
@section('title', 'Cari Lapangan')

@section('content')
@php
    $selectedSports = (array) ($filters['sport_ids'] ?? []);
    $selectedFacilities = (array) ($filters['facilities'] ?? []);
    $currentSort = $filters['sort'] ?? 'popular';
@endphp

<div class="max-w-7xl mx-auto px-4 py-6 lg:py-8">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-body mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-figma-blue transition">Beranda</a></li>
            <li class="text-figma-gray">›</li>
            <li class="text-heading font-medium">Cari Lapangan</li>
        </ol>
    </nav>

    <form method="GET" action="{{ route('venues.index') }}" id="venue-search-form">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <h1 class="section-title !text-3xl lg:!text-4xl">Lapangan Tersedia</h1>
            <div class="flex items-center gap-3 shrink-0">
                <label for="sort" class="text-sm text-body whitespace-nowrap">Urutkan berdasarkan:</label>
                <select
                    id="sort"
                    name="sort"
                    onchange="document.getElementById('venue-search-form').submit()"
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-heading focus:border-figma-blue focus:ring-figma-blue"
                >
                    <option value="popular" @selected($currentSort === 'popular')>Rekomendasi</option>
                    <option value="price_low" @selected($currentSort === 'price_low')>Harga Terendah</option>
                    <option value="price_high" @selected($currentSort === 'price_high')>Harga Tertinggi</option>
                    <option value="rating" @selected($currentSort === 'rating')>Rating Tertinggi</option>
                </select>
            </div>
        </div>

        <div class="grid lg:grid-cols-[280px_1fr] gap-8 items-start">
            {{-- Sidebar Filter --}}
            <aside class="filter-sidebar rounded-xl border border-gray-200 bg-figma-surface p-5 lg:sticky lg:top-24">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-bold text-heading">Filter</h2>
                    <a href="{{ route('venues.index') }}" class="text-sm font-semibold text-figma-blue hover:underline">Hapus Semua</a>
                </div>

                {{-- Jenis Olahraga --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-heading mb-3">Jenis Olahraga</h3>
                    <ul class="space-y-2">
                        @foreach($sports as $sport)
                            <li>
                                <label class="flex items-center gap-2.5 cursor-pointer text-sm text-body">
                                    <input
                                        type="checkbox"
                                        name="sport_ids[]"
                                        value="{{ $sport->id }}"
                                        @checked(in_array((string) $sport->id, $selectedSports) || in_array($sport->id, $selectedSports))
                                        class="h-4 w-4 rounded border-gray-300 text-figma-blue focus:ring-figma-blue"
                                    >
                                    {{ $sport->name }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Harga --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-heading mb-3">Harga per Jam</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <input
                            type="number"
                            name="min_price"
                            value="{{ $filters['min_price'] ?? '' }}"
                            placeholder="Min"
                            class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-heading placeholder:text-body/60 focus:border-figma-blue focus:ring-figma-blue"
                        >
                        <input
                            type="number"
                            name="max_price"
                            value="{{ $filters['max_price'] ?? '' }}"
                            placeholder="Maks"
                            class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-heading placeholder:text-body/60 focus:border-figma-blue focus:ring-figma-blue"
                        >
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-heading mb-3">Fasilitas</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($facilities as $key => $label)
                            @php $active = in_array($key, $selectedFacilities); @endphp
                            <label class="cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="{{ $key }}" class="sr-only peer" @checked($active)>
                                <span @class(['facility-pill', 'facility-pill-active' => $active])>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full">Terapkan Filter</button>
            </aside>

            {{-- Results Grid --}}
            <div>
                @if($venues->total() > 0)
                    <p class="text-sm text-body mb-6">{{ $venues->total() }} lapangan ditemukan</p>
                @endif

                <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($venues as $venue)
                        <x-venue-card-search :venue="$venue" :booked-until="$bookedUntil[$venue->id] ?? null" />
                    @empty
                        <div class="col-span-full text-center py-16 rounded-2xl border border-dashed border-gray-200 bg-figma-surface">
                            <i data-lucide="search-x" class="w-12 h-12 mx-auto text-body/40 mb-4"></i>
                            <p class="font-semibold text-heading">Tidak ada lapangan ditemukan</p>
                            <p class="text-sm text-body mt-1">Coba ubah filter atau hapus semua filter.</p>
                            <a href="{{ route('venues.index') }}" class="inline-block mt-4 text-sm font-semibold text-figma-blue hover:underline">Hapus Semua Filter</a>
                        </div>
                    @endforelse
                </div>

                @if($venues->hasPages())
                    <div class="mt-10">{{ $venues->links() }}</div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
