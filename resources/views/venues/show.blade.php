@extends('layouts.app')
@section('title', $venue->name)
@section('content')
@php $primary = $venue->images->firstWhere('is_primary', true) ?? $venue->images->first(); @endphp
<div class="max-w-7xl mx-auto px-4 py-8" x-data="venueBooking('{{ $venue->slug }}', {{ $venue->price_per_hour }})">
    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-4 gap-2 rounded-2xl overflow-hidden">
                <img src="{{ $primary?->url() ?? 'https://picsum.photos/seed/'.$venue->id.'/1200/600' }}" class="col-span-4 aspect-video object-cover" alt="">
                @foreach($venue->images->take(3) as $img)
                    <img src="{{ $img->url() }}" class="aspect-video object-cover rounded-xl" alt="">
                @endforeach
            </div>
            <div data-aos="fade-up">
                <p class="text-secondary text-sm font-medium">{{ $venue->sport->name }} · {{ $venue->city }}</p>
                <h1 class="text-3xl font-bold mt-1">{{ $venue->name }}</h1>
                <div class="flex items-center gap-2 mt-2 text-accent"><i data-lucide="star" class="w-4 h-4"></i><span class="font-semibold">{{ number_format($venue->rating_avg, 1) }}</span><span class="text-gray-400 text-sm">({{ $venue->reviews_count }} ulasan)</span></div>
                <p class="mt-4 text-gray-600">{{ $venue->description }}</p>
                <p class="mt-4 flex items-start gap-2 text-sm text-gray-600"><i data-lucide="map-pin" class="w-4 h-4 mt-0.5"></i>{{ $venue->address }}, {{ $venue->city }}</p>
                @if($venue->latitude && $venue->longitude)
                    <iframe class="mt-4 w-full h-48 rounded-2xl border" loading="lazy" src="https://maps.google.com/maps?q={{ $venue->latitude }},{{ $venue->longitude }}&z=15&output=embed"></iframe>
                @endif
            </div>
            <div class="card-soft p-6">
                <h2 class="font-bold mb-4">Ulasan</h2>
                @forelse($venue->reviews as $review)
                    <div class="border-b py-3 last:border-0">
                        <p class="font-medium">{{ $review->user->name }} · {{ $review->rating }}★</p>
                        <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                    </div>
                @empty<p class="text-gray-500 text-sm">Belum ada ulasan.</p>@endforelse
            </div>
        </div>
        <div class="card-soft p-6 h-fit sticky top-24" data-aos="fade-left">
            <p class="text-2xl font-bold">{{ $venue->formattedPrice() }}<span class="text-sm font-normal text-gray-500">/jam</span></p>
            @auth
                <form action="{{ $isFavorite ? route('favorites.destroy', $venue) : route('favorites.store', $venue) }}" method="POST" class="mt-2">
                    @csrf @if($isFavorite) @method('DELETE') @endif
                    <button class="text-sm text-secondary">{{ $isFavorite ? '♥ Hapus Favorit' : '♡ Simpan Favorit' }}</button>
                </form>
            @endauth
            <form action="{{ auth()->check() ? route('bookings.store', $venue) : route('login') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium">Tanggal</label>
                    <input type="date" name="booking_date" x-model="date" @change="loadSlots()" min="{{ date('Y-m-d') }}" required class="w-full rounded-2xl border-gray-200 mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Jam Tersedia</label>
                    <input type="hidden" name="start_time" x-model="startTime">
                    <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto">
                        <template x-for="slot in slots" :key="slot.start">
                            <button type="button" @click="selectSlot(slot)"
                                :disabled="slot.status === 'booked'"
                                :class="{
                                    'slot-available': slot.status === 'available' && startTime !== slot.start,
                                    'slot-booked': slot.status === 'booked',
                                    'slot-selected': startTime === slot.start
                                }"
                                class="rounded-xl border px-2 py-2 text-xs font-medium" x-text="slot.start"></button>
                        </template>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium">Durasi (jam)</label>
                    <select name="duration_hours" x-model="duration" @change="calcTotal()" class="w-full rounded-2xl border-gray-200 mt-1">
                        @for($h = 1; $h <= 4; $h++)<option value="{{ $h }}">{{ $h }} jam</option>@endfor
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Jumlah Pemain</label>
                    <input type="number" name="players_count" value="10" min="1" class="w-full rounded-2xl border-gray-200 mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Catatan</label>
                    <textarea name="notes" rows="2" class="w-full rounded-2xl border-gray-200 mt-1"></textarea>
                </div>
                <p class="font-bold text-lg">Total: <span x-text="formatRp(total)"></span></p>
                @auth
                    <button type="submit" class="btn-secondary w-full" :disabled="!startTime">Go To Payment</button>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary w-full text-center">Login untuk Booking</a>
                @endauth
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
function venueBooking(venueSlug, pricePerHour) {
    return {
        venueSlug, pricePerHour, date: '', startTime: '', duration: 1, slots: [], total: 0,
        async loadSlots() {
            if (!this.date) return;
            const res = await fetch(`/venues/${this.venueSlug}/slots?date=${this.date}`);
            const data = await res.json();
            this.slots = data.slots;
            this.startTime = '';
            this.calcTotal();
        },
        selectSlot(slot) {
            if (slot.status !== 'available') return;
            this.startTime = slot.start;
            this.calcTotal();
        },
        calcTotal() { this.total = this.pricePerHour * this.duration; },
        formatRp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n); }
    }
}
</script>
@endpush
@endsection
