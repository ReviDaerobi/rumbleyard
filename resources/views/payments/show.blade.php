@extends('layouts.app')
@section('title', 'Konfirmasi Pemesanan')

@section('content')
@php
    $booking = $payment->booking;
    $venue = $booking->venue;
    $primaryImage = $venue->images->firstWhere('is_primary', true) ?? $venue->images->first();
    $imageUrl = $primaryImage?->url() ?? 'https://picsum.photos/seed/'.$venue->id.'/1200/600';
    $serviceFee = $booking->total - $booking->subtotal;
    $formattedDate = $booking->booking_date->locale('id')->translatedFormat('l, d M Y');
    $startTime = substr($booking->start_time, 0, 5);
    $endTime = substr($booking->end_time, 0, 5);
    $formatRp = fn ($amount) => 'Rp '.number_format($amount, 0, ',', '.');
    $method = $payment->meta['method'] ?? 'bank';
@endphp

<div class="bg-figma-surface py-12 lg:py-16">
    <div class="max-w-xl mx-auto px-4">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 rounded-full bg-figma-blue flex items-center justify-center text-white mb-5">
                <i data-lucide="check" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-heading">Konfirmasi Pemesanan!</h1>
            <p class="mt-3 text-sm text-body max-w-md mx-auto leading-relaxed">
                Lapangan Anda telah berhasil dipesan. Email konfirmasi telah dikirim ke kotak masuk Anda.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <img
                src="{{ $imageUrl }}"
                alt="{{ $venue->name }}"
                class="w-full aspect-[16/9] object-cover"
            >

            <div class="p-6 space-y-6">
                <h2 class="text-lg font-bold text-heading">Detail Pemesanan</h2>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-5">
                        <div class="flex gap-3">
                            <div class="shrink-0 mt-0.5 text-figma-blue">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-body/70">Tanggal</p>
                                <p class="mt-0.5 text-sm font-medium text-heading capitalize">{{ $formattedDate }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="shrink-0 mt-0.5 text-figma-blue">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-body/70">Lokasi</p>
                                <p class="mt-0.5 text-sm font-medium text-heading">{{ $venue->address }}{{ $venue->city ? ', '.$venue->city : '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex gap-3">
                            <div class="shrink-0 mt-0.5 text-figma-blue">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-body/70">Waktu</p>
                                <p class="mt-0.5 text-sm font-medium text-heading">
                                    {{ $startTime }} - {{ $endTime }} ({{ $booking->duration_hours }} jam)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-body/70 mb-4">Detail Pembayaran</p>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between text-body">
                            <span>Tarif per jam ({{ $formatRp($venue->price_per_hour) }} x {{ $booking->duration_hours }})</span>
                            <span>{{ $formatRp($booking->subtotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-body">
                            <span>Biaya Layanan</span>
                            <span>{{ $formatRp($serviceFee) }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 mt-4 pt-4 flex items-center justify-between">
                        <span class="font-bold text-heading">Total Dibayar</span>
                        <span class="font-bold text-figma-blue text-lg">{{ $formatRp($booking->total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 space-y-3">
            @if($payment->status->value === 'pending')
                <a href="{{ route('payments.mock.checkout', ['payment' => $payment, 'method' => $method]) }}" class="btn-secondary w-full text-center block py-3.5 rounded-xl normal-case">
                    Bayar
                </a>
            @endif

            <button
                type="button"
                onclick="window.print()"
                class="w-full inline-flex items-center justify-center rounded-xl bg-gray-100 px-5 py-3.5 text-sm font-semibold uppercase tracking-wide text-figma-blue transition hover:bg-gray-200"
            >
                Download Receipt
            </button>

            <p class="text-center pt-2">
                <a href="#" class="inline-flex items-center gap-1.5 text-xs text-body hover:text-figma-blue transition">
                    <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                    Butuh bantuan dengan reservasi Anda?
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
