@extends('layouts.dashboard')
@section('title', 'Edit Booking')
@section('header', 'Edit Booking')

@section('content')
<form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="max-w-2xl card-soft p-6 space-y-4">
    @csrf @method('PUT')
    @include('admin.bookings._form', ['booking' => $booking])
    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-secondary">Update</button>
        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-outline">Batal</a>
    </div>
</form>
@endsection
