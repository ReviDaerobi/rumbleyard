<div>
    <label class="text-sm font-medium">Pemilik Venue</label>
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
    <label class="text-sm font-medium">Olahraga</label>
    <select name="sport_id" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($sports as $sport)
            <option value="{{ $sport->id }}" @selected(old('sport_id', optional($venue)->sport_id) == $sport->id)>{{ $sport->name }}</option>
        @endforeach
    </select>
    @error('sport_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="text-sm font-medium">Nama</label>
    <input name="name" value="{{ old('name', optional($venue)->name) }}" required class="w-full rounded-2xl border-gray-200 mt-1">
    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="text-sm font-medium">Deskripsi</label>
    <textarea name="description" rows="3" class="w-full rounded-2xl border-gray-200 mt-1">{{ old('description', optional($venue)->description) }}</textarea>
</div>
<div>
    <label class="text-sm font-medium">Alamat</label>
    <input name="address" value="{{ old('address', optional($venue)->address) }}" required class="w-full rounded-2xl border-gray-200 mt-1">
    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="text-sm font-medium">Kota</label>
    <input name="city" value="{{ old('city', optional($venue)->city) }}" required class="w-full rounded-2xl border-gray-200 mt-1">
    @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="text-sm font-medium">Harga / jam</label>
    <input type="number" name="price_per_hour" value="{{ old('price_per_hour', optional($venue)->price_per_hour) }}" required min="0" class="w-full rounded-2xl border-gray-200 mt-1">
    @error('price_per_hour')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-medium">Latitude</label>
        <input type="number" step="any" name="latitude" value="{{ old('latitude', optional($venue)->latitude) }}" class="w-full rounded-2xl border-gray-200 mt-1">
    </div>
    <div>
        <label class="text-sm font-medium">Longitude</label>
        <input type="number" step="any" name="longitude" value="{{ old('longitude', optional($venue)->longitude) }}" class="w-full rounded-2xl border-gray-200 mt-1">
    </div>
</div>
<div class="flex flex-wrap gap-4">
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', optional($venue)->is_active ?? true))> Aktif
    </label>
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', optional($venue)->is_featured))> Featured
    </label>
</div>
