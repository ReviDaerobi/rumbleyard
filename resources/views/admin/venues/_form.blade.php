@php $venue ??= null; @endphp

<div>
    <label class="text-sm font-medium text-gray-700">Pemilik Venue</label>
    <select name="user_id" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($owners as $owner)
            <option value="{{ $owner->id }}" @selected(old('user_id', optional($venue)->user_id) == $owner->id)>
                {{ $owner->name }} ({{ $owner->email }})
            </option>
        @endforeach
    </select>
    @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="text-sm font-medium text-gray-700">Olahraga</label>
    <select name="sport_id" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($sports as $sport)
            <option value="{{ $sport->id }}" @selected(old('sport_id', optional($venue)->sport_id) == $sport->id)>
                {{ $sport->name }}
            </option>
        @endforeach
    </select>
    @error('sport_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="text-sm font-medium text-gray-700">Nama</label>
    <input type="text" name="name" value="{{ old('name', optional($venue)->name) }}" required
        class="w-full rounded-2xl border-gray-200 mt-1" placeholder="Contoh: Lapangan Futsal Maju">
    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3"
        class="w-full rounded-2xl border-gray-200 mt-1"
        placeholder="Deskripsi singkat tentang venue...">{{ old('description', optional($venue)->description) }}</textarea>
</div>

<div>
    <label class="text-sm font-medium text-gray-700">Alamat</label>
    <input type="text" name="address" value="{{ old('address', optional($venue)->address) }}" required
        class="w-full rounded-2xl border-gray-200 mt-1" placeholder="Jl. Contoh No. 123">
    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="text-sm font-medium text-gray-700">Kota</label>
    <input type="text" name="city" value="{{ old('city', optional($venue)->city) }}" required
        class="w-full rounded-2xl border-gray-200 mt-1" placeholder="Contoh: Bogor">
    @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="text-sm font-medium text-gray-700">Harga / jam</label>
    <div class="relative mt-1">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
        <input type="number" name="price_per_hour" value="{{ old('price_per_hour', optional($venue)->price_per_hour) }}"
            required min="0" class="w-full rounded-2xl border-gray-200 pl-10" placeholder="50000">
    </div>
    @error('price_per_hour')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-medium text-gray-700">Latitude</label>
        <input type="number" step="any" name="latitude"
            value="{{ old('latitude', optional($venue)->latitude) }}"
            min="-90" max="90"
            class="w-full rounded-2xl border-gray-200 mt-1" placeholder="-6.3615978">
        <p class="text-xs text-gray-400 mt-1">Antara -90 sampai 90</p>
        @error('latitude')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Longitude</label>
        <input type="number" step="any" name="longitude"
            value="{{ old('longitude', optional($venue)->longitude) }}"
            min="-180" max="180"
            class="w-full rounded-2xl border-gray-200 mt-1" placeholder="106.8271668">
        <p class="text-xs text-gray-400 mt-1">Antara -180 sampai 180</p>
        @error('longitude')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="flex flex-wrap gap-6 pt-1">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_active" value="1"
            @checked(old('is_active', optional($venue)->is_active ?? true))
            class="rounded">
        <span class="text-sm text-gray-700">Aktif</span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_featured" value="1"
            @checked(old('is_featured', optional($venue)->is_featured))
            class="rounded">
        <span class="text-sm text-gray-700">Featured</span>
    </label>
</div>