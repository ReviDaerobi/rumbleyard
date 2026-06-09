<div>
    <label class="text-sm font-medium">Olahraga</label>
    <select name="sport_id" required class="w-full rounded-2xl border-gray-200 mt-1">
        @foreach($sports as $sport)
            <option value="{{ $sport->id }}" @selected(old('sport_id', optional($venue)->sport_id) == $sport->id)>{{ $sport->name }}</option>
        @endforeach
    </select>
</div>
<div><label class="text-sm font-medium">Nama</label><input name="name" value="{{ old('name', optional($venue)->name) }}" required class="w-full rounded-2xl border-gray-200 mt-1"></div>
<div><label class="text-sm font-medium">Deskripsi</label><textarea name="description" class="w-full rounded-2xl border-gray-200 mt-1">{{ old('description', optional($venue)->description) }}</textarea></div>
<div><label class="text-sm font-medium">Alamat</label><input name="address" value="{{ old('address', optional($venue)->address) }}" required class="w-full rounded-2xl border-gray-200 mt-1"></div>
<div><label class="text-sm font-medium">Kota</label><input name="city" value="{{ old('city', optional($venue)->city) }}" required class="w-full rounded-2xl border-gray-200 mt-1"></div>
<div><label class="text-sm font-medium">Harga / jam</label><input type="number" name="price_per_hour" value="{{ old('price_per_hour', optional($venue)->price_per_hour) }}" required class="w-full rounded-2xl border-gray-200 mt-1"></div>
