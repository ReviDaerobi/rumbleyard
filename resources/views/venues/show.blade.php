@extends('layouts.app')
@section('title', $venue->name)

@section('content')
@php $serviceFee = 5000; @endphp

<div
    class="bg-figma-surface min-h-screen"
    x-data="venueBooking('{{ $venue->slug }}', {{ $venue->price_per_hour }}, {{ $serviceFee }})"
    x-init="init()"
>
    <div class="max-w-7xl mx-auto px-4 py-8">
        @include('partials.booking.gallery', ['venue' => $venue])

        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ auth()->check() ? route('bookings.store', $venue) : route('login') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_date" :value="date">
            <input type="hidden" name="start_time" :value="startTime">
            <input type="hidden" name="duration_hours" :value="duration">
            <input type="hidden" name="players_count" value="10">
            <input type="hidden" name="payment_method" :value="paymentMethod">

            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    @include('partials.booking.detail-lapangan')

                    {{-- Pilih Slot Waktu --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                            <h2 class="text-lg font-bold text-heading">Pilih Slot Waktu</h2>
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <select
                                        x-model="duration"
                                        class="booking-select appearance-none rounded-lg border border-gray-200 bg-white pl-3 pr-8 py-2 text-sm font-medium text-heading"
                                    >
                                        @for($h = 1; $h <= 4; $h++)
                                            <option value="{{ $h }}">{{ $h }} Jam</option>
                                        @endfor
                                    </select>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-body absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                </div>
                                <div class="relative">
                                    <button
                                        type="button"
                                        @click="$refs.dateInput.showPicker()"
                                        class="booking-select inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-heading capitalize"
                                    >
                                        <span x-text="formatDate(date)"></span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-body"></i>
                                    </button>
                                    <input
                                        x-ref="dateInput"
                                        type="date"
                                        x-model="date"
                                        @change="loadSlots()"
                                        min="{{ date('Y-m-d') }}"
                                        class="sr-only"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="slot in displaySlots" :key="slot.start">
                                <button
                                    type="button"
                                    @click="selectSlot(slot)"
                                    :disabled="slot.status === 'booked'"
                                    class="rounded-xl border px-2 py-3 text-center text-sm font-medium transition"
                                    :class="{
                                        'border-figma-blue bg-figma-blue text-white shadow-sm': startTime === slot.start,
                                        'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed': slot.status === 'booked',
                                        'border-gray-200 bg-white text-heading hover:border-figma-blue/30': slot.status === 'available' && startTime !== slot.start
                                    }"
                                >
                                    <p x-text="formatSlotTime(slot.start)"></p>
                                    <p
                                        class="text-[10px] mt-0.5"
                                        :class="startTime === slot.start ? 'opacity-90' : ''"
                                        x-text="startTime === slot.start ? 'Terpilih' : (slot.status === 'booked' ? 'Penuh' : 'Tersedia')"
                                    ></p>
                                </button>
                            </template>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center gap-4 text-xs text-body">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm bg-gray-200 border border-gray-300"></span>
                                    Penuh
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm bg-figma-blue"></span>
                                    Terpilih
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm bg-white border border-gray-300"></span>
                                    Tersedia
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button
                                    type="button"
                                    @click="prevDate()"
                                    class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white text-body hover:border-figma-blue/30 hover:text-figma-blue transition"
                                >
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </button>
                                <button
                                    type="button"
                                    @click="nextDate()"
                                    class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white text-body hover:border-figma-blue/30 hover:text-figma-blue transition"
                                >
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan & Pembayaran --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 lg:sticky lg:top-24">
                        <h2 class="text-lg font-bold text-heading mb-5">Ringkasan Pemesanan</h2>

                        <div class="rounded-xl border border-gray-200 bg-figma-surface p-4 mb-5">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 h-10 w-10 rounded-lg bg-figma-blue/10 flex items-center justify-center text-figma-blue">
                                    <i data-lucide="calendar" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-heading" x-text="startTime ? '1 Slot Terpilih' : 'Belum ada slot'"></p>
                                    <p class="text-xs text-body mt-0.5">{{ $venue->name }}</p>
                                </div>
                                <p class="text-sm font-bold text-heading shrink-0" x-text="formatRp(subtotal)"></p>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm mb-5">
                            <div class="flex items-center justify-between text-body">
                                <span>Subtotal</span>
                                <span x-text="formatRp(subtotal)"></span>
                            </div>
                            <div class="flex items-center justify-between text-body">
                                <span>Biaya Layanan</span>
                                <span x-text="formatRp(serviceFeeAmount)"></span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 flex items-center justify-between mb-6">
                            <span class="font-bold text-heading">Total</span>
                            <span class="font-bold text-figma-blue text-xl" x-text="formatRp(total)"></span>
                        </div>

                        <p class="text-sm font-bold text-heading mb-3">Pilih Metode Pembayaran</p>

                        <div class="space-y-2 mb-5">
                            <label class="payment-option" :class="paymentMethod === 'bank' && 'payment-option-active'">
                                <input type="radio" value="bank" x-model="paymentMethod" class="text-figma-blue focus:ring-figma-blue">
                                <div class="payment-option-icon">
                                    <i data-lucide="landmark" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-heading">Transfer Bank</p>
                                    <p class="text-xs text-body">Akun Virtual</p>
                                </div>
                            </label>

                            <label class="payment-option" :class="paymentMethod === 'ewallet' && 'payment-option-active'">
                                <input type="radio" value="ewallet" x-model="paymentMethod" class="text-figma-blue focus:ring-figma-blue">
                                <div class="payment-option-icon">
                                    <i data-lucide="smartphone" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-heading">E-Wallet</p>
                                    <p class="text-xs text-body">Gopay, Dana, Shopeepay, OVO</p>
                                </div>
                            </label>

                            <label class="payment-option" :class="paymentMethod === 'card' && 'payment-option-active'">
                                <input type="radio" value="card" x-model="paymentMethod" class="text-figma-blue focus:ring-figma-blue">
                                <div class="payment-option-icon">
                                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-heading">Kartu Kredit</p>
                                    <p class="text-xs text-body">Visa, Mastercard</p>
                                </div>
                            </label>
                        </div>

                        @auth
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-figma-blue px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!startTime || !date"
                            >
                                Konfirmasi &amp; Bayar
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-figma-blue px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                Konfirmasi &amp; Bayar
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @endauth

                        <p class="text-[11px] text-body/70 text-center mt-3 leading-relaxed">
                            Dengan mengklik 'Konfirmasi &amp; Bayar', Anda menyetujui Syarat dan Ketentuan {{ $venue->name }}.
                        </p>
                    </div>

                    @include('partials.booking.help-box')
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function venueBooking(venueSlug, pricePerHour, serviceFee) {
    const slotHours = [9, 10, 11, 12, 13, 14, 15, 16, 17];

    return {
        venueSlug,
        pricePerHour,
        serviceFee,
        date: '',
        startTime: '',
        duration: 1,
        slots: [],
        paymentMethod: 'bank',
        init() {
            this.date = new Date().toISOString().split('T')[0];
            this.loadSlots();
        },
        get displaySlots() {
            return slotHours.map((h) => {
                const start = String(h).padStart(2, '0') + ':00';
                const found = this.slots.find((s) => s.start === start);
                return { start, status: found ? found.status : 'booked' };
            });
        },
        get subtotal() {
            return this.startTime ? this.pricePerHour * this.duration : 0;
        },
        get serviceFeeAmount() {
            return this.startTime ? this.serviceFee : 0;
        },
        get total() {
            return this.subtotal + this.serviceFeeAmount;
        },
        async loadSlots() {
            if (!this.date) return;
            const res = await fetch(`/venues/${this.venueSlug}/slots?date=${this.date}`);
            const data = await res.json();
            this.slots = data.slots;
            if (this.startTime && !this.displaySlots.find((s) => s.start === this.startTime && s.status === 'available')) {
                this.startTime = '';
            }
        },
        selectSlot(slot) {
            if (slot.status !== 'available') return;
            this.startTime = slot.start;
        },
        prevDate() {
            const d = new Date(this.date + 'T00:00:00');
            d.setDate(d.getDate() - 1);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (d < today) return;
            this.date = d.toISOString().split('T')[0];
            this.startTime = '';
            this.loadSlots();
        },
        nextDate() {
            const d = new Date(this.date + 'T00:00:00');
            d.setDate(d.getDate() + 1);
            this.date = d.toISOString().split('T')[0];
            this.startTime = '';
            this.loadSlots();
        },
        formatRp(n) {
            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(n);
        },
        formatSlotTime(time) {
            return time.replace(':', '.');
        },
        formatDate(dateStr) {
            if (!dateStr) return 'Pilih tanggal';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short' });
        },
    };
}
</script>
@endpush
@endsection
