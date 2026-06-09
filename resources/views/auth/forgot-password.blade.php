@extends('layouts.auth')
@section('title', 'Lupa Password')

@section('visual')
<div class="hidden lg:block lg:w-1/2 relative min-h-screen">
    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1400&q=80"
         alt="Lapangan olahraga" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-black/30"></div>
</div>
@endsection

@section('content')
<x-auth-logo />
<h1 class="text-2xl font-bold text-figma-navy">Lupa Kata Sandi?</h1>
<p class="mt-1 text-sm text-gray-500 mb-8">Masukkan email untuk menerima link reset password.</p>
<form method="POST" action="{{ route('password.email') }}" class="space-y-5">@csrf
    <div>
        <label class="block text-sm font-semibold text-figma-navy mb-2">Email</label>
        <input type="email" name="email" required placeholder="email@contoh.com"
               class="w-full px-4 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
    </div>
    <button class="w-full py-3.5 rounded-lg bg-figma-blue text-white text-sm font-bold uppercase hover:bg-[#0f5bb8] transition">Kirim Link Reset</button>
</form>
<p class="mt-6 text-center text-sm"><a href="{{ route('login') }}" class="text-figma-blue font-medium">← Kembali ke Login</a></p>
@endsection
