@extends('layouts.dashboard')
@section('title', 'Edit Venue')
@section('header', 'Edit Venue')

@section('content')
<form method="POST" action="{{ route('admin.venues.update', $venue) }}" class="max-w-2xl card-soft p-6 space-y-4">
    @csrf @method('PUT')
    @include('admin.venues._form')
    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-secondary">Update</button>
        <a href="{{ route('admin.venues.index') }}" class="btn-outline">Batal</a>
    </div>
</form>
@endsection
