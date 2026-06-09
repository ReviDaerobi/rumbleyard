@props(['venue'])
@php
    $image = $venue->images->firstWhere('is_primary', true) ?? $venue->images->first();
    $imgUrl = $image ? $image->url() : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=600&h=400&fit=crop';
@endphp
<article {{ $attributes->merge(['class' => 'rounded-2xl bg-white border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition']) }} data-aos="fade-up">
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $imgUrl }}" alt="{{ $venue->name }}" class="h-full w-full object-cover">
        <span class="absolute top-3 left-3 rounded-md bg-amber-400 px-2.5 py-1 text-xs font-bold uppercase text-figma-navy">
            Tersedia
        </span>
    </div>
    <div class="p-4">
        <h3 class="font-bold text-heading uppercase tracking-wide">{{ $venue->name }}</h3>
        <p class="mt-1 text-sm text-body">{{ $venue->city }} · {{ $venue->sport->name }}</p>
        <div class="mt-3 flex flex-wrap gap-2">
            <span class="rounded-md bg-figma-surface px-2.5 py-1 text-[10px] font-semibold uppercase text-body">{{ $venue->sport->name }}</span>
            <span class="rounded-md bg-figma-surface px-2.5 py-1 text-[10px] font-semibold uppercase text-body">Lapangan Keras</span>
            <span class="rounded-md bg-figma-surface px-2.5 py-1 text-[10px] font-semibold uppercase text-body">Ruang Ganti</span>
        </div>
        <div class="mt-4 flex items-center justify-between gap-3">
            <p class="text-sm text-body">
                Harga <span class="font-bold text-heading">{{ $venue->formattedPrice() }}/jam</span>
            </p>
            <a href="{{ route('venues.show', $venue) }}" class="btn-primary shrink-0 !px-3 !py-2 !text-[10px]">
                Pesan Sekarang
            </a>
        </div>
    </div>
</article>
