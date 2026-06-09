@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
<div class="max-w-lg mx-auto px-4 py-12">
    <div class="card-soft p-8">
        <h1 class="text-xl font-bold mb-2">Selesaikan Pembayaran</h1>
        <p class="text-sm text-gray-500 mb-6">Booking {{ $payment->booking->code }}</p>
        <p class="text-3xl font-bold mb-6">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
        <p class="text-sm mb-4">Status: <span class="font-medium">{{ $payment->status->label() }}</span></p>
        @if($payment->status->value === 'pending')
            <a href="{{ route('payments.mock.checkout', $payment) }}" class="btn-secondary w-full text-center block">Lanjut ke Mock Payment</a>
        @endif
    </div>
</div>
@endsection
