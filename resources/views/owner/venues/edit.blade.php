@extends('layouts.dashboard')
@section('header', 'Edit Venue')
@section('content')
<form method="POST" action="{{ route('owner.venues.update', $venue) }}" class="max-w-2xl card-soft p-6 space-y-4">
    @csrf @method('PUT')
    @include('owner.venues._form')
    <div class="flex gap-4">
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked($venue->is_active)> Aktif</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" @checked($venue->is_featured)> Featured</label>
    </div>
    <button class="btn-secondary">Update</button>
</form>
@endsection
