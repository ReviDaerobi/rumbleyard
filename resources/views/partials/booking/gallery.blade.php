@php
    $images = $venue->images;
    $primary = $images->firstWhere('is_primary', true) ?? $images->first();
    $fallback = $primary?->url() ?? 'https://picsum.photos/seed/'.$venue->id.'/800/500';
    $galleryUrls = $images->take(3)->map(fn ($img) => $img->url())->values();
    while ($galleryUrls->count() < 3) {
        $galleryUrls->push($fallback);
    }
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-8">
    @foreach($galleryUrls as $url)
        <img src="{{ $url }}" alt="{{ $venue->name }}" class="aspect-[4/3] w-full object-cover rounded-xl">
    @endforeach
</div>
