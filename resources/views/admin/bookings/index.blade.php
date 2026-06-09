@extends('layouts.dashboard')
@section('title', 'Kelola Bookings')
@section('header', 'Kelola Bookings')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <p class="text-gray-500">Kelola semua reservasi di platform</p>
    <a href="{{ route('admin.bookings.create') }}" class="btn-secondary">+ Tambah Booking</a>
</div>

<form method="GET" class="card-soft p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[200px]">
        <label class="text-xs font-medium text-gray-500">Cari</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Kode, pelanggan, venue..."
               class="w-full rounded-2xl border-gray-200 mt-1">
    </div>
    <div>
        <label class="text-xs font-medium text-gray-500">Status</label>
        <select name="status" class="rounded-2xl border-gray-200 mt-1">
            <option value="">Semua</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-outline">Filter</button>
    @if(request()->hasAny(['q', 'status']))
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
    @endif
</form>

<div class="card-soft overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3 font-medium">Kode</th>
                    <th class="px-5 py-3 font-medium">Venue</th>
                    <th class="px-5 py-3 font-medium">Pelanggan</th>
                    <th class="px-5 py-3 font-medium">Jadwal</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Total</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-4 font-mono text-secondary">{{ $booking->code }}</td>
                        <td class="px-5 py-4">{{ $booking->venue->name }}</td>
                        <td class="px-5 py-4">{{ $booking->user->name }}</td>
                        <td class="px-5 py-4">
                            {{ $booking->booking_date->format('d M Y') }}<br>
                            <span class="text-gray-500">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100">
                                {{ $booking->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-medium">Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-right space-x-3">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-secondary font-medium hover:underline">Detail</a>
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-secondary font-medium hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="inline"
                                  onsubmit="return confirm('Hapus booking ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 font-medium hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-500">Belum ada booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $bookings->links() }}</div>
@endsection
