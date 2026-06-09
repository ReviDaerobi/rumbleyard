@extends('layouts.dashboard')
@section('header', 'Venue Saya')
@section('content')
<div class="flex justify-between mb-6">
    <p class="text-gray-500">Kelola lapangan kamu</p>
    <a href="{{ route('owner.venues.create') }}" class="btn-secondary">+ Tambah Venue</a>
</div>
<div class="space-y-3">
    @foreach($venues as $venue)
        <div class="card-soft p-4 flex justify-between items-center">
            <div>
                <p class="font-bold">{{ $venue->name }}</p>
                <p class="text-sm text-gray-500">{{ $venue->sport->name }} · {{ $venue->city }} · {{ $venue->formattedPrice() }}/jam</p>
            </div>
            <a href="{{ route('owner.venues.edit', $venue) }}" class="text-secondary text-sm font-medium">Edit</a>
        </div>
    @endforeach
</div>
{{ $venues->links() }}
@endsection
