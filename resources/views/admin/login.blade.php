@extends('layouts.admin-auth')
@section('title', 'Portal Admin')

@section('content')
<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-figma-navy tracking-tight">Rumble Yard</h1>
        <p class="mt-2 text-xl font-medium text-figma-navy">Portal Admin</p>
        <p class="mt-1 text-sm text-figma-navy/80">Kelola operasi fasilitas Anda</p>
    </div>

    <div class="rounded-2xl bg-white shadow-xl overflow-hidden">
        <div class="px-8 py-8">
            @if(session('status'))
                <div class="mb-4 rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-sm text-figma-blue">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-2">
                        Email atau Nama Pengguna
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               placeholder="065124000@unpak.kampus"
                               class="w-full pl-11 pr-4 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
                    </div>
                    @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="text-sm font-medium text-gray-600">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-figma-blue hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                               class="w-full pl-11 pr-11 py-3 rounded-lg border border-[#D9D9D9] bg-[#F8FAFC] text-sm text-figma-navy focus:outline-none focus:ring-2 focus:ring-figma-blue/30 focus:border-figma-blue">
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                            <i data-lucide="eye-off" class="w-4 h-4" x-show="show" x-cloak></i>
                        </button>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-[#D9D9D9] text-figma-blue focus:ring-figma-blue/30">
                    <span class="text-xs text-gray-500">Ingat perangkat ini selama 30 hari</span>
                </label>

                <button type="submit"
                        class="w-full py-3.5 rounded-lg bg-figma-blue text-white text-sm font-bold tracking-wide uppercase hover:bg-[#0f5bb8] transition shadow-sm">
                    MASUK
                </button>
            </form>
        </div>

        <div class="border-t border-[#D9D9D9] px-8 py-4 text-center">
            <a href="{{ route('home') }}" class="text-sm font-medium text-figma-blue hover:underline">
                Kembali ke Situs
            </a>
        </div>
    </div>
</div>
@endsection
