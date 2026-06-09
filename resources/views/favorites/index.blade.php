@extends('layouts.dashboard')
@section('title', 'Favorit')
@section('header', 'Venue Favorit')

@section('content')
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($venues as $venue)
        <x-venue-card :venue="$venue" />
    @empty
        <p class="text-gray-500 col-span-full">Belum ada venue favorit.</p>
    @endforelse
</div>
{{ $venues->links() }}
@endsection
