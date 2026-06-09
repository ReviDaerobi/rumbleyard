@extends('layouts.app')
@section('title', 'Status Pembayaran')

@section('content')
@php
    $booking = $payment->booking;
    $venue = $booking->venue;
    $serviceFee = $booking->total - $booking->subtotal;
    $formattedDate = $booking->booking_date->locale('id')->translatedFormat('l, d M Y');
    $startTime = substr($booking->start_time, 0, 5);
    $endTime = substr($booking->end_time, 0, 5);
    $formatRp = fn ($amount) => 'Rp. '.number_format($amount, 0, ',', '.');
    $isPending = $payment->status->value === 'pending';
    $method = $payment->meta['method'] ?? 'bank';
@endphp

<div class="bg-figma-surface min-h-screen py-12">
    <div class="max-w-lg mx-auto px-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            @if($isPending)
                <div class="text-center mb-6">
                    <div class="mx-auto h-14 w-14 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mb-4">
                        <i data-lucide="clock" class="w-7 h-7"></i>
                    </div>
                    <h1 class="text-xl font-bold text-heading">Menunggu Pembayaran</h1>
                    <p class="text-sm text-body mt-2">Pesanan Anda sudah dibuat. Selesaikan pembayaran untuk mengkonfirmasi booking.</p>
                </div>
            @elseif($payment->isPaid())
                <div class="text-center mb-6">
                    <div class="mx-auto h-14 w-14 rounded-full bg-green-50 flex items-center justify-center text-green-600 mb-4">
                        <i data-lucide="check-circle" class="w-7 h-7"></i>
                    </div>
                    <h1 class="text-xl font-bold text-heading">Pembayaran Berhasil</h1>
                    <p class="text-sm text-body mt-2">Booking Anda sudah dikonfirmasi.</p>
                </div>
            @else
                <div class="text-center mb-6">
                    <div class="mx-auto h-14 w-14 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-4">
                        <i data-lucide="x-circle" class="w-7 h-7"></i>
                    </div>
                    <h1 class="text-xl font-bold text-heading">{{ $payment->status->label() }}</h1>
                    <p class="text-sm text-body mt-2">Silakan coba bayar ulang atau hubungi dukungan.</p>
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-figma-surface p-4 space-y-3 text-sm mb-6">
                <div class="flex justify-between">
                    <span class="text-body">Kode Booking</span>
                    <span class="font-mono font-semibold text-heading">{{ $booking->code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-body">Lapangan</span>
                    <span class="font-medium text-heading">{{ $venue->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-body">Tanggal</span>
                    <span class="font-medium text-heading capitalize">{{ $formattedDate }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-body">Waktu</span>
                    <span class="font-medium text-heading">{{ $startTime }} – {{ $endTime }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3 flex justify-between">
                    <span class="text-body">Subtotal</span>
                    <span>{{ $formatRp($booking->subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-body">Biaya Layanan</span>
                    <span>{{ $formatRp($serviceFee) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3 flex justify-between font-bold">
                    <span class="text-heading">Total</span>
                    <span class="text-figma-blue text-lg">{{ $formatRp($booking->total) }}</span>
                </div>
            </div>

            <div class="space-y-3">
                @if($isPending)
                    <a
                        href="{{ route('payments.mock.checkout', ['payment' => $payment, 'method' => $method]) }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-figma-blue px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Lanjutkan Pembayaran
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                @elseif($payment->isPaid())
                    <a
                        href="{{ route('bookings.success', $booking) }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-figma-blue px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Lihat Konfirmasi
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                @else
                    <a
                        href="{{ route('payments.mock.checkout', ['payment' => $payment, 'method' => $method]) }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-figma-blue px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Coba Bayar Lagi
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                @endif

                <a href="{{ route('bookings.history') }}" class="w-full inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-3.5 text-sm font-semibold text-heading transition hover:border-gray-300">
                    Lihat Pesanan Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
