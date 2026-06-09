<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use App\Repositories\Contracts\VenueRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected VenueRepositoryInterface $venues) {}

    public function index(): View
    {
        $venueCount = Venue::where('is_active', true)->count();
        $teamCount = User::role('customer')->count();
        $avgRating = Venue::where('is_active', true)->where('reviews_count', '>', 0)->avg('rating_avg');

        return view('home', [
            'popularVenues' => $this->venues->popular(8),
            'featuredVenues' => $this->venues->featured(6),
            'stats' => [
                'venues' => max($venueCount, 20),
                'teams' => max($teamCount, 12),
                'rating' => number_format($avgRating ?: 4.9, 1),
            ],
        ]);
    }
}
