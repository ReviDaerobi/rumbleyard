@php $booking ??= null; @endphp
<div>
    <label class="text-sm font-medium">Pelanggan</label>
    <select name="user_id" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($users as $user)
            <option value="{{ $user->id }}" @selected(old('user_id', optional($booking)->user_id) == $user->id)>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
    @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="text-sm font-medium">Venue</label>
    <select name="venue_id" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($venues as $venue)
            <option value="{{ $venue->id }}" @selected(old('venue_id', optional($booking)->venue_id) == $venue->id)>
                {{ $venue->name }} · {{ $venue->sport->name }} · {{ $venue->formattedPrice() }}/jam
            </option>
        @endforeach
    </select>
    @error('venue_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-medium">Tanggal</label>
        <input type="date" name="booking_date"
               value="{{ old('booking_date', optional($booking)->booking_date?->format('Y-m-d')) }}"
               required class="w-full rounded-2xl border-gray-200 mt-1">
        @error('booking_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium">Jam Mulai</label>
        <input type="time" name="start_time"
               value="{{ old('start_time', $booking ? substr((string) $booking->start_time, 0, 5) : '') }}"
               required class="w-full rounded-2xl border-gray-200 mt-1">
        @error('start_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>
<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-medium">Durasi (jam)</label>
        <input type="number" name="duration_hours" min="1" max="8"
               value="{{ old('duration_hours', optional($booking)->duration_hours ?? 1) }}"
               required class="w-full rounded-2xl border-gray-200 mt-1">
        @error('duration_hours')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium">Jumlah Pemain</label>
        <input type="number" name="players_count" min="1" max="50"
               value="{{ old('players_count', optional($booking)->players_count ?? 1) }}"
               class="w-full rounded-2xl border-gray-200 mt-1">
    </div>
</div>
<div>
    <label class="text-sm font-medium">Status</label>
    <select name="status" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($statuses as $status)
            <option value="{{ $status->value }}" @selected(old('status', optional($booking)->status?->value ?? 'pending') === $status->value)>
                {{ $status->label() }}
            </option>
        @endforeach
    </select>
    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="text-sm font-medium">Catatan</label>
    <textarea name="notes" rows="3" class="w-full rounded-2xl border-gray-200 mt-1">{{ old('notes', optional($booking)->notes) }}</textarea>
</div>
@if(!isset($booking))
    <label class="flex items-center gap-2">
        <input type="checkbox" name="create_payment" value="1" @checked(old('create_payment', true))>
        <span class="text-sm text-gray-600">Buat tagihan pembayaran otomatis</span>
    </label>
@endif
