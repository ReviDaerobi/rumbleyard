@extends('layouts.app')
@section('title', 'Pembayaran')

@section('content')
@php
    $booking = $payment->booking;
    $venue = $booking->venue;
    $method = request('method', $payment->meta['method'] ?? 'bank');
    $methodLabels = [
        'bank' => 'Transfer Bank',
        'ewallet' => 'E-Wallet',
        'card' => 'Kartu Kredit',
    ];
    $formattedDate = $booking->booking_date->locale('id')->translatedFormat('l, d M Y');
    $startTime = substr($booking->start_time, 0, 5);
    $endTime = substr($booking->end_time, 0, 5);
    $formatRp = fn ($amount) => 'Rp. '.number_format($amount, 0, ',', '.');
@endphp

<div class="bg-figma-surface min-h-screen py-12">
    <div class="max-w-lg mx-auto px-4">
        <div class="text-center mb-6">
            <p class="text-sm font-medium text-figma-blue uppercase tracking-wide">Langkah 2 dari 2</p>
            <h1 class="text-2xl font-bold text-heading mt-1">Selesaikan Pembayaran</h1>
            <p class="text-sm text-body mt-2">Pilih aksi di bawah untuk menyelesaikan transaksi Anda.</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <div class="rounded-xl border border-gray-200 bg-figma-surface p-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 h-10 w-10 rounded-lg bg-figma-blue/10 flex items-center justify-center text-figma-blue">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0 text-sm">
                        <p class="font-semibold text-heading">{{ $venue->name }}</p>
                        <p class="text-body capitalize mt-0.5">{{ $formattedDate }}</p>
                        <p class="text-body">{{ $startTime }} – {{ $endTime }} · {{ $booking->duration_hours }} jam</p>
                        <p class="text-xs text-body/70 mt-1 font-mono">{{ $booking->code }}</p>
                    </div>
                    <p class="text-sm font-bold text-figma-blue shrink-0">{{ $formatRp($payment->amount) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl border border-gray-200 p-3.5 mb-6">
                <div class="shrink-0 h-9 w-9 rounded-lg bg-figma-surface flex items-center justify-center text-figma-blue">
                    <i data-lucide="{{ $method === 'ewallet' ? 'smartphone' : ($method === 'card' ? 'credit-card' : 'landmark') }}" class="w-4 h-4"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-heading">{{ $methodLabels[$method] ?? 'Transfer Bank' }}</p>
                    <p class="text-xs text-body">Metode pembayaran yang dipilih</p>
                </div>
            </div>

            <p class="text-xs text-body/70 text-center mb-6 leading-relaxed">
                Simulasi payment gateway untuk development. Siap integrasi Midtrans/Xendit.
            </p>

            <form action="{{ route('payments.mock.pay', $payment) }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="method" value="{{ $method }}">
                <button class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-figma-blue px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Bayar Sekarang
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </form>

            <form action="{{ route('payments.mock.fail', $payment) }}" method="POST">
                @csrf
                <button class="w-full inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-3.5 text-sm font-semibold text-red-600 transition hover:border-red-200 hover:bg-red-50">
                    Simulasikan Gagal
                </button>
            </form>
        </div>

        <p class="text-center mt-5">
            <a href="{{ route('venues.show', $venue) }}" class="text-xs text-figma-blue hover:underline">
                &larr; Kembali ke halaman lapangan
            </a>
        </p>
    </div>
</div>
@endsection
