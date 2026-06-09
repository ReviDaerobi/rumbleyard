@extends('layouts.dashboard')
@section('title', 'Admin')
@section('header', 'Admin Dashboard')

@section('content')
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach(['users' => 'Pengguna', 'venues' => 'Venue', 'bookings' => 'Booking', 'revenue' => 'Pendapatan'] as $key => $label)
        <div class="card-soft p-5">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="text-2xl font-bold">
                @if($key === 'revenue') Rp {{ number_format($stats[$key], 0, ',', '.') }}
                @else {{ number_format($stats[$key]) }} @endif
            </p>
        </div>
    @endforeach
</div>
<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <div class="card-soft p-5"><canvas id="dailyChart" height="200"></canvas></div>
    <div class="card-soft p-5"><canvas id="revenueChart" height="200"></canvas></div>
</div>
<h2 class="font-bold mb-4">Activity Log</h2>
<div class="card-soft divide-y">
    @foreach($activityLogs as $log)
        <div class="p-4 text-sm flex justify-between">
            <span>{{ $log->action }} · {{ $log->user?->name ?? 'System' }}</span>
            <span class="text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
        </div>
    @endforeach
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: @json($dailyBookings->pluck('date')),
            datasets: [{ label: 'Booking Harian', data: @json($dailyBookings->pluck('total')), borderColor: '#22C55E', tension: 0.3 }]
        },
        options: { plugins: { legend: { display: false } } }
    });
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyRevenue->pluck('month')),
            datasets: [{ label: 'Revenue', data: @json($monthlyRevenue->pluck('total')), backgroundColor: '#F59E0B' }]
        },
        options: { plugins: { legend: { display: false } } }
    });
});
</script>
@endpush
@endsection
