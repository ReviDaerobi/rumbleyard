<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function index(): View
    {
        $venues = auth()->user()->venues()->with('sport')->latest()->paginate(10);

        return view('owner.venues.index', compact('venues'));
    }

    public function create(): View
    {
        return view('owner.venues.create', [
            'sports' => Sport::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sport_id' => ['required', 'exists:sports,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(5);
        $validated['opening_hours'] = $this->defaultOpeningHours();

        Venue::create($validated);

        return redirect()->route('owner.venues.index')->with('success', 'Venue berhasil ditambahkan.');
    }

    public function edit(Venue $venue): View
    {
        $this->authorize('update', $venue);

        return view('owner.venues.edit', [
            'venue' => $venue,
            'sports' => Sport::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Venue $venue): RedirectResponse
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'sport_id' => ['required', 'exists:sports,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $venue->update($validated);

        return redirect()->route('owner.venues.index')->with('success', 'Venue berhasil diperbarui.');
    }

    protected function defaultOpeningHours(): array
    {
        $default = ['open' => '08:00', 'close' => '22:00'];

        return collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->mapWithKeys(fn ($day) => [$day => $default])
            ->put('default', $default)
            ->all();
    }
}
