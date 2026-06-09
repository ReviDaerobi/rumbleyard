@extends('layouts.dashboard')
@section('title', $booking->code)
@section('header', 'Detail Booking')

@section('content')
<div class="max-w-2xl card-soft p-6 space-y-4">
    <p class="font-mono text-secondary">{{ $booking->code }}</p>
    <h2 class="text-xl font-bold">{{ $booking->venue->name }}</h2>
    <dl class="grid grid-cols-2 gap-3 text-sm">
        <dt class="text-gray-500">Tanggal</dt><dd>{{ $booking->booking_date->format('d M Y') }}</dd>
        <dt class="text-gray-500">Waktu</dt><dd>{{ substr($booking->start_time,0,5) }} - {{ substr($booking->end_time,0,5) }}</dd>
        <dt class="text-gray-500">Status</dt><dd>{{ $booking->status->label() }}</dd>
        <dt class="text-gray-500">Total</dt><dd class="font-bold">Rp {{ number_format($booking->total, 0, ',', '.') }}</dd>
    </dl>
    @if($booking->payment && !$booking->payment->isPaid())
        <a href="{{ route('payments.show', $booking->payment) }}" class="btn-secondary inline-flex">Bayar Sekarang</a>
    @endif
</div>
@endsection
