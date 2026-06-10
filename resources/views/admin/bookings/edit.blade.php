@extends('layouts.dashboard')
@section('title', 'Edit Booking')
@section('header', 'Edit Booking')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="card-soft p-8 space-y-5">
        @csrf @method('PUT')
        @include('admin.bookings._form', ['booking' => $booking])
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-secondary">Update</button>
            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection