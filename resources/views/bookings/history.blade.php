@extends('layouts.dashboard')
@section('title', 'Riwayat Booking')
@section('header', 'Riwayat Booking')

@section('content')
<div class="space-y-4">
    @forelse($bookings as $booking)
        <div class="card-soft p-5 flex flex-wrap justify-between gap-4">
            <div>
                <p class="font-mono text-sm text-secondary">{{ $booking->code }}</p>
                <p class="font-bold">{{ $booking->venue->name }}</p>
                <p class="text-sm text-gray-500">{{ $booking->booking_date->format('d M Y') }} · {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-medium bg-gray-100">{{ $booking->status->label() }}</span>
                <p class="font-bold mt-2">Rp {{ number_format($booking->total, 0, ',', '.') }}</p>
                <a href="{{ route('bookings.show', $booking) }}" class="text-secondary text-sm">Detail →</a>
            </div>
        </div>
    @empty
        <p class="text-gray-500">Belum ada riwayat booking.</p>
    @endforelse
</div>
{{ $bookings->links() }}
@endsection
