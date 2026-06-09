<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $venues = auth()->user()->favorites()->with(['sport', 'images'])->paginate(12);

        return view('favorites.index', compact('venues'));
    }

    public function store(Venue $venue): RedirectResponse
    {
        auth()->user()->favorites()->syncWithoutDetaching([$venue->id]);

        return back()->with('success', 'Venue ditambahkan ke favorit.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        auth()->user()->favorites()->detach($venue->id);

        return back()->with('success', 'Venue dihapus dari favorit.');
    }
}
