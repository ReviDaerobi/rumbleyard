@extends('layouts.dashboard')
@section('header', 'Tambah Venue')
@section('content')
<form method="POST" action="{{ route('owner.venues.store') }}" class="max-w-2xl card-soft p-6 space-y-4">
    @csrf
    @include('owner.venues._form')
    <button class="btn-secondary">Simpan</button>
</form>
@endsection
