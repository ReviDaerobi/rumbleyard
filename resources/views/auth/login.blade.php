@extends('layouts.auth')
@section('title', 'Login')

@section('visual')
<div class="hidden lg:flex lg:w-1/2 flex-col min-h-screen">
    {{-- Gambar atas --}}
    <div class="flex-1 relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1567220720374-a67f33b2a6b9?q=80&w=1632&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
             alt="Lapangan tenis" class="absolute inset-0 h-full w-full object-cover">
        {{-- Border overlay --}}
        <div class="absolute inset-0 pointer-events-none" style="box-shadow: inset 0 0 0 5px rgb(193, 196, 201);"></div>
    </div>

    {{-- Gambar bawah --}}
    <div class="flex-1 relative overflow-hidden" style="border-top: 1px solid rgb(156, 163, 175);">
        <img src="https://images.unsplash.com/photo-1556056504-5c7696c4c28d?q=80&w=1952&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
             alt="Lapangan futsal" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        {{-- Border overlay --}}
        <div class="absolute inset-0 pointer-events-none" style="box-shadow: inset 0 0 0 5px rgb(193, 196, 201);"></div>
        <div class="absolute bottom-10 left-10 right-10 text-white">
            <h2 class="text-4xl font-bold leading-tight">Elevate Your Game.</h2>
            <p class="mt-3 text-sm text-white/90 max-w-md leading-relaxed">
                Pemesanan lapangan premium dan manajemen olahraga untuk atlet modern.
            </p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="text-center">
    <x-auth-logo />

    <h1 class="text-2xl font-bold text-figma-navy">Selamat Datang Kembali</h1>
    <p class="mt-1 text-sm text-gray-500 mb-8">Masuk untuk mengelola reservasi dan fasilitas Anda.</p>
</div>

@if(session('status'))
    <div class="mb-4 rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-sm text-figma-blue">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-semibold text-figma-navy mb-2">Email atau Nama Pengguna</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="mail" class="w-4 h-4"></i>
            </span>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   placeholder="065124000@Unpak.kampus"
                   class="auth-input w-full pl-11 pr-4 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
        </div>
        @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="text-sm font-semibold text-figma-navy">Kata Sandi</label>
            <a href="{{ route('password.request') }}" class="text-xs font-medium text-figma-blue hover:underline">Lupa Kata Sandi?</a>
        </div>
        <div class="relative" x-data="{ show: false }">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="lock" class="w-4 h-4"></i>
            </span>
            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                   class="auth-input w-full pl-11 pr-11 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
            <button type="button" @click="show = !show"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                <i data-lucide="eye-off" class="w-4 h-4" x-show="show" x-cloak></i>
            </button>
        </div>
    </div>

    <label class="flex items-center gap-2.5 cursor-pointer">
        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#D9D9D9] text-figma-blue focus:ring-figma-blue/30">
        <span class="text-sm text-gray-600">Ingat saya</span>
    </label>

    <button type="submit"
            class="w-full py-3.5 rounded-lg bg-figma-blue text-white text-sm font-bold tracking-wide uppercase hover:bg-[#0f5bb8] transition shadow-sm">
        MASUK
    </button>
</form>

<div class="relative my-8">
    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#D9D9D9]"></div></div>
    <div class="relative flex justify-center text-xs uppercase tracking-wider">
        <span class="bg-white px-4 text-gray-400 font-medium">Atau lanjutkan dengan</span>
    </div>
</div>

<a href="{{ route('google.redirect') }}"
   class="flex w-full items-center justify-center gap-3 py-3 rounded-lg border border-[#D9D9D9] bg-white text-sm font-medium text-figma-navy hover:bg-[#F8FAFC] transition">
    <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    Masuk dengan Google
</a>

<p class="mt-10 text-center text-sm text-gray-500">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-semibold text-figma-blue hover:underline">Daftar</a>
</p>
@endsection
