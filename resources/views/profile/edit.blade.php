@extends('layouts.dashboard')
@section('title', 'Profil')
@section('header', 'Pengaturan Profil')

@section('content')
<form method="POST" action="{{ route('profile.update') }}" class="max-w-xl card-soft p-6 space-y-4">
    @csrf @method('PATCH')
    <div><label class="text-sm font-medium">Nama</label><input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border-gray-200 mt-1"></div>
    <div><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border-gray-200 mt-1"></div>
    <div><label class="text-sm font-medium">Telepon</label><input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-2xl border-gray-200 mt-1"></div>
    <div><label class="text-sm font-medium">Alamat</label><textarea name="address" class="w-full rounded-2xl border-gray-200 mt-1">{{ old('address', $user->address) }}</textarea></div>
    <hr>
    <div><label class="text-sm font-medium">Password Baru</label><input type="password" name="password" class="w-full rounded-2xl border-gray-200 mt-1"></div>
    <div><label class="text-sm font-medium">Konfirmasi Password</label><input type="password" name="password_confirmation" class="w-full rounded-2xl border-gray-200 mt-1"></div>
    <button class="btn-secondary">Simpan</button>
</form>
@endsection
