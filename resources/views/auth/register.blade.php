@extends('layouts.auth')
@section('title', 'Daftar')

@section('visual')
<div class="hidden lg:block lg:w-1/2 relative min-h-screen">
    <img src="https://images.unsplash.com/photo-1676746424139-77f8bd8922a8?q=80&w=736&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
         alt="Stadion malam hari" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-black/25"></div>
     {{-- Border overlay --}}
        <div class="absolute inset-0 pointer-events-none" style="box-shadow: inset 0 0 0 7px rgb(193, 196, 201);"></div>
</div>
@endsection

@section('content')
<h1 class="text-3xl font-bold text-figma-navy text-center lg:text-left">Create an Account</h1>
<p class="mt-2 text-sm text-gray-500 text-center lg:text-left mb-8">Masukkan detail diri anda</p>

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-semibold text-figma-navy mb-2">Nama Pengguna</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="user" class="w-4 h-4"></i>
            </span>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Masukkan nama pengguna"
                   class="auth-input w-full pl-11 pr-4 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
        </div>
        @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-semibold text-figma-navy mb-2">Email</label>
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
        <label for="phone" class="block text-sm font-semibold text-figma-navy mb-2">No. Telepon</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="phone" class="w-4 h-4"></i>
            </span>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                   placeholder="+62 812 3456 7890"
                   class="auth-input w-full pl-11 pr-4 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
        </div>
        @error('phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="password" class="block text-sm font-semibold text-figma-navy mb-2">Password</label>
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
            @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-figma-navy mb-2">Konfirmasi Password</label>
            <div class="relative" x-data="{ show: false }">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </span>
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                       class="auth-input w-full pl-11 pr-11 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
                <button type="button" @click="show = !show"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                    <i data-lucide="eye-off" class="w-4 h-4" x-show="show" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    <button type="submit"
            class="w-full py-3.5 rounded-lg bg-figma-blue text-white text-sm font-bold tracking-wide uppercase hover:bg-[#0f5bb8] transition shadow-sm mt-2">
        DAFTAR
    </button>
</form>

<p class="mt-8 text-center text-sm text-gray-500">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="font-semibold text-figma-blue hover:underline">Masuk</a>
</p>
@endsection
