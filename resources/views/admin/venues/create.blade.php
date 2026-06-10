@extends('layouts.dashboard')
@section('title', 'Tambah Venue')
@section('header', 'Tambah Venue')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.venues.store') }}" class="card-soft p-8 space-y-5">
        @csrf
        @include('admin.venues._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-secondary">Simpan</button>
            <a href="{{ route('admin.venues.index') }}" class="btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection