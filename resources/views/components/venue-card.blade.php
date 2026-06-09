@props(['venue'])
@php
    $image = $venue->images->firstWhere('is_primary', true) ?? $venue->images->first();
    $imgUrl = $image ? $image->url() : 'https://picsum.photos/seed/'.$venue->id.'/600/400';
@endphp
<article {{ $attributes->merge(['class' => 'card-soft overflow-hidden group']) }} data-aos="fade-up">
    <a href="{{ route('venues.show', $venue) }}" class="block">
        <div class="aspect-[4/3] overflow-hidden">
            <img src="{{ $imgUrl }}" alt="{{ $venue->name }}" class="h-full w-full object-cover transition group-hover:scale-105">
        </div>
        <div class="p-4">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-medium text-figma-blue">{{ $venue->sport->name }}</p>
                    <h3 class="font-semibold text-primary line-clamp-1">{{ $venue->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $venue->city }}</p>
                </div>
                <span class="flex items-center gap-1 text-sm font-medium text-accent">
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>{{ number_format($venue->rating_avg, 1) }}
                </span>
            </div>
            <p class="mt-3 font-bold text-primary">{{ $venue->formattedPrice() }}<span class="text-sm font-normal text-body">/jam</span></p>
        </div>
    </a>
</article>
