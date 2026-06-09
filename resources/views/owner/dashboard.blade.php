@extends('layouts.dashboard')
@section('title', 'Owner Dashboard')
@section('header', 'Venue Owner Dashboard')

@section('content')
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card-soft p-5"><p class="text-sm text-gray-500">Venue</p><p class="text-2xl font-bold">{{ $stats['venues'] }}</p></div>
    <div class="card-soft p-5"><p class="text-sm text-gray-500">Total Booking</p><p class="text-2xl font-bold">{{ $stats['bookings'] }}</p></div>
    <div class="card-soft p-5"><p class="text-sm text-gray-500">Pending</p><p class="text-2xl font-bold">{{ $stats['pending'] }}</p></div>
    <div class="card-soft p-5"><p class="text-sm text-gray-500">Revenue</p><p class="text-2xl font-bold">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p></div>
</div>
<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <div class="card-soft p-5"><canvas id="ownerDaily" height="200"></canvas></div>
    <div class="card-soft p-5"><canvas id="ownerRevenue" height="200"></canvas></div>
</div>
<h2 class="font-bold mb-4">Booking Terbaru</h2>
<div class="card-soft divide-y">
    @foreach($recentBookings as $booking)
        <div class="p-4 flex justify-between text-sm">
            <span>{{ $booking->user->name }} · {{ $booking->venue->name }}</span>
            <span class="font-mono text-secondary">{{ $booking->code }}</span>
        </div>
    @endforeach
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    new Chart(document.getElementById('ownerDaily'), {
        type: 'line',
        data: { labels: @json($dailyBookings->pluck('date')), datasets: [{ data: @json($dailyBookings->pluck('total')), borderColor: '#22C55E', tension: 0.3 }] },
        options: { plugins: { legend: { display: false } } }
    });
    new Chart(document.getElementById('ownerRevenue'), {
        type: 'bar',
        data: { labels: @json($monthlyRevenue->pluck('month')), datasets: [{ data: @json($monthlyRevenue->pluck('total')), backgroundColor: '#111827' }] },
        options: { plugins: { legend: { display: false } } }
    });
});
</script>
@endpush
@endsection
