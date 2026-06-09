@extends('layouts.dashboard')
@section('title', $booking->code)
@section('header', 'Detail Booking')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <p class="font-mono text-secondary">{{ $booking->code }}</p>
            <h2 class="text-xl font-bold mt-1">{{ $booking->venue->name }}</h2>
            <p class="text-sm text-gray-500">{{ $booking->venue->sport->name }} · {{ $booking->venue->city }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-secondary">Edit</a>
            <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}"
                  onsubmit="return confirm('Hapus booking ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-outline text-red-500 border-red-200 hover:border-red-300">Hapus</button>
            </form>
        </div>
    </div>

    <div class="card-soft p-6">
        <dl class="grid sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Pelanggan</dt>
                <dd class="font-medium mt-0.5">{{ $booking->user->name }}</dd>
                <dd class="text-gray-500">{{ $booking->user->email }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd class="font-medium mt-0.5">{{ $booking->status->label() }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Tanggal</dt>
                <dd class="font-medium mt-0.5">{{ $booking->booking_date->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Waktu</dt>
                <dd class="font-medium mt-0.5">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Durasi</dt>
                <dd class="font-medium mt-0.5">{{ $booking->duration_hours }} jam</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jumlah Pemain</dt>
                <dd class="font-medium mt-0.5">{{ $booking->players_count }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Subtotal</dt>
                <dd class="font-medium mt-0.5">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Total</dt>
                <dd class="font-bold mt-0.5 text-lg">Rp {{ number_format($booking->total, 0, ',', '.') }}</dd>
            </div>
            @if($booking->notes)
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Catatan</dt>
                    <dd class="mt-0.5">{{ $booking->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>

    @if($booking->details->isNotEmpty())
        <div class="card-soft p-6">
            <h3 class="font-bold mb-3">Detail Slot</h3>
            <div class="space-y-2 text-sm">
                @foreach($booking->details as $detail)
                    <div class="flex justify-between">
                        <span>{{ substr($detail->slot_start, 0, 5) }} - {{ substr($detail->slot_end, 0, 5) }}</span>
                        <span>Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($booking->payment)
        <div class="card-soft p-6">
            <h3 class="font-bold mb-2">Pembayaran</h3>
            <p class="text-sm text-gray-500">Status: <span class="font-medium text-primary">{{ $booking->payment->status->label() }}</span></p>
            <p class="text-sm text-gray-500 mt-1">Jumlah: Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
        </div>
    @endif

    <a href="{{ route('admin.bookings.index') }}" class="text-secondary text-sm font-medium hover:underline">← Kembali ke daftar</a>
</div>
@endsection
