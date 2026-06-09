@extends('layouts.dashboard')
@section('title', 'Tambah Venue')
@section('header', 'Tambah Venue')

@section('content')
<form method="POST" action="{{ route('admin.venues.store') }}" class="max-w-2xl card-soft p-6 space-y-4">
    @csrf
    @include('admin.venues._form')
    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-secondary">Simpan</button>
        <a href="{{ route('admin.venues.index') }}" class="btn-outline">Batal</a>
    </div>
</form>
@endsection
