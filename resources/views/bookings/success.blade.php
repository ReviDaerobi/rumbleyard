@extends('layouts.app')
@section('title', 'Booking Berhasil')
@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center" data-aos="zoom-in">
    <div class="mx-auto h-16 w-16 rounded-full bg-secondary/20 flex items-center justify-center text-secondary mb-6">
        <i data-lucide="check-circle" class="w-8 h-8"></i>
    </div>
    <h1 class="text-2xl font-bold">Pembayaran Berhasil!</h1>
    <p class="text-gray-500 mt-2">Kode booking kamu</p>
    <p class="text-2xl font-mono font-bold text-secondary mt-2">{{ $booking->code }}</p>
    <p class="mt-4">{{ $booking->venue->name }} · {{ $booking->booking_date->format('d M Y') }}</p>
    <div class="mt-8 flex gap-3 justify-center">
        <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
        <a href="{{ route('bookings.history') }}" class="btn-outline">Riwayat</a>
    </div>
</div>
@endsection
