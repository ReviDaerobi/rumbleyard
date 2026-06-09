@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('header', 'Halo, '.auth()->user()->name)
@section('subheader', 'Kelola aktivitas dan booking kamu')

@section('content')
<div class="grid md:grid-cols-3 gap-4 mb-8">
    <div class="card-soft p-5"><p class="text-sm text-gray-500">Booking Mendatang</p><p class="text-2xl font-bold">{{ $upcoming->count() }}</p></div>
    <div class="card-soft p-5"><p class="text-sm text-gray-500">Favorit</p><p class="text-2xl font-bold">{{ $favorites->count() }}</p></div>
    <a href="{{ route('venues.index') }}" class="card-soft p-5 flex items-center justify-center text-secondary font-semibold">+ Booking Baru</a>
</div>

<h2 class="font-bold mb-4">Aktivitas Mendatang</h2>
<div class="space-y-3 mb-10">
    @forelse($upcoming as $booking)
        <div class="card-soft p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $booking->venue->name }}</p>
                <p class="text-sm text-gray-500">{{ $booking->booking_date->format('d M Y') }} · {{ substr($booking->start_time, 0, 5) }}</p>
            </div>
            <a href="{{ route('bookings.show', $booking) }}" class="text-secondary text-sm font-medium">Detail</a>
        </div>
    @empty
        <p class="text-gray-500 text-sm">Belum ada booking mendatang.</p>
    @endforelse
</div>

<h2 class="font-bold mb-4">Venue Favorit</h2>
<div class="grid sm:grid-cols-2 gap-4">
    @foreach($favorites as $venue)<x-venue-card :venue="$venue" />@endforeach
</div>
@endsection
