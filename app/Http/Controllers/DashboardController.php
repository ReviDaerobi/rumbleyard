<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
   
    }

    public function index(): View
    {
        $user = auth()->user();

        $upcoming = Booking::query()
            ->with(['venue.sport'])
            ->where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->whereDate('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->limit(5)
            ->get();

        $favorites = $user->favorites()->with(['sport', 'images'])->limit(4)->get();

        return view('dashboard', compact('upcoming', 'favorites'));
    }
}
