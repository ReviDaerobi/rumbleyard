@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-12 lg:py-20 grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
        <div data-aos="fade-right">
            <h1 class="section-title">
                Pesan Lapangan Profesional
                <span class="section-subtitle block mt-1">Secara Instan</span>
            </h1>
            <p class="mt-5 text-body leading-relaxed max-w-lg">
                Temukan dan pesan lapangan olahraga premium dengan ketersediaan real-time.
                Dari futsal hingga tenis, nikmati fasilitas kelas dunia dengan pemesanan instan
                dan konfirmasi langsung.
            </p>
            <div class="mt-10 grid grid-cols-3 gap-4 sm:gap-8">
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-heading">{{ $stats['venues'] }}+</p>
                    <p class="mt-1 text-xs sm:text-sm text-body leading-snug">Lapangan Terverifikasi</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-heading">{{ $stats['teams'] }}+</p>
                    <p class="mt-1 text-xs sm:text-sm text-body leading-snug">Tim Aktif</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-heading">{{ $stats['rating'] }}/5</p>
                    <p class="mt-1 text-xs sm:text-sm text-body leading-snug">Penilaian Pengguna</p>
                </div>
            </div>
        </div>

        <div class="relative" data-aos="fade-left">
            <img
                src="https://i.pinimg.com/1200x/ef/f3/ee/eff3ee2f0b1a53227187312402baf20b.jpg"
                alt="Lapangan tenis profesional"
                class="w-full rounded-2xl shadow-xl object-cover aspect-[4/3]"
            >
            <div class="absolute -bottom-4 left-4 sm:left-6 bg-white rounded-xl shadow-lg px-4 py-3 flex items-center gap-3 max-w-[260px]">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-figma-blue text-white">
                    <i data-lucide="badge-check" class="w-5 h-5"></i>
                </span>
                <div>
                    <p class="font-bold text-sm text-heading">Pemesanan Instan</p>
                    <p class="text-xs text-body">Tanpa menunggu, langsung bayar</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Courts --}}
<section class="bg-white py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10" data-aos="fade-up">
            <div>
                <h2 class="section-title">Pesan Lapangan Profesional</h2>
                <p class="section-subtitle mt-1">Secara Instan</p>
            </div>
            <a href="{{ route('venues.index') }}" class="text-figma-blue font-semibold text-sm hover:underline shrink-0">
                Jelajahi Semua Lapangan →
            </a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredVenues->take(3) as $venue)
                <x-venue-card-home :venue="$venue" />
            @empty
                @foreach($popularVenues->take(3) as $venue)
                    <x-venue-card-home :venue="$venue" />
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- How it Works --}}
<section class="bg-figma-surface py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1" data-aos="fade-up">
            <h2 class="section-title">Mulai Bermain dalam Hitungan Menit</h2>
            <p class="section-subtitle">
                Tiga langkah mudah untuk memesan tempat latihan atau pertandingan yang sempurna bagi Anda
            </p>
        </div>
        <div class="mt-12 grid md:grid-cols-3 gap-8">
            @foreach([
                ['icon' => 'search', 'title' => 'Cari Lapangan', 'desc' => 'Jelajahi lapangan terdekat berdasarkan olahraga, lokasi, dan harga yang sesuai kebutuhan Anda.'],
                ['icon' => 'calendar', 'title' => 'Pilih Jadwal', 'desc' => 'Lihat ketersediaan real-time dan pilih tanggal serta jam main yang paling pas untuk tim Anda.'],
                ['icon' => 'zap', 'title' => 'Bayar & Main', 'desc' => 'Selesaikan pembayaran secara instan dan dapatkan konfirmasi booking langsung tanpa menunggu.'],
            ] as $i => $step)
                <div class="flex flex-col items-center" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-md text-figma-blue">
                        <i data-lucide="{{ $step['icon'] }}" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-bold text-lg text-heading">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-figma-blue leading-relaxed max-w-xs">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
