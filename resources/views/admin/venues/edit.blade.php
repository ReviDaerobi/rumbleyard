@extends('layouts.dashboard')
@section('title', 'Edit Venue')
@section('header', 'Edit Venue')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.venues.update', $venue) }}" class="card-soft p-8 space-y-5">
        @csrf @method('PUT')
        @include('admin.venues._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-secondary">Update</button>
            <a href="{{ route('admin.venues.index') }}" class="btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection