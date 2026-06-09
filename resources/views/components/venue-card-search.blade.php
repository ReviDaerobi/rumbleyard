@props(['venue', 'bookedUntil' => null])
@php
    $image = $venue->images->firstWhere('is_primary', true) ?? $venue->images->first();
    $imgUrl = $image ? $image->url() : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=600&h=400&fit=crop';
    $isBooked = filled($bookedUntil);
    $distance = number_format(1.2 + ($venue->id % 4) * 0.9, 1);
    $tags = collect([
        str_contains(strtolower($venue->description ?? ''), 'rumput') ? 'Rumput Sintetis' : 'Lapangan Keras',
        str_contains(strtolower($venue->description ?? ''), 'indoor') || str_contains(strtolower($venue->description ?? ''), 'dalam ruangan') ? 'Dalam Ruangan' : 'Luar Ruangan',
        'Ruang Ganti',
    ])->unique()->take(3);
@endphp
<article {{ $attributes->merge(['class' => 'rounded-2xl bg-white border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition']) }}>
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $imgUrl }}" alt="{{ $venue->name }}" class="h-full w-full object-cover">
        @if($isBooked)
            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-heading shadow-sm">
                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                Dipesan Hingga {{ $bookedUntil }}
            </span>
        @else
            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-heading shadow-sm">
                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                Tersedia
            </span>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-bold text-heading uppercase tracking-wide">{{ $venue->name }}</h3>
        <p class="mt-1 text-sm text-body">Berjarak {{ $distance }} km</p>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <span class="rounded-md bg-figma-surface px-2.5 py-1 text-[10px] font-semibold uppercase text-body">{{ $tag }}</span>
            @endforeach
        </div>
        <div class="mt-4 flex items-center justify-between gap-3">
            <p class="text-sm text-body">
                Harga <span class="font-bold text-heading">{{ $venue->formattedPrice() }}/jam</span>
            </p>
            @if($isBooked)
                <a href="{{ route('venues.show', $venue) }}#jadwal" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-200 px-3 py-2 text-[10px] font-bold uppercase tracking-wide text-body transition hover:bg-gray-300">
                    Lihat Jadwal
                </a>
            @else
                <a href="{{ route('venues.show', $venue) }}" class="btn-primary shrink-0 !px-3 !py-2 !text-[10px]">
                    Pesan Sekarang
                </a>
            @endif
        </div>
    </div>
</article>
