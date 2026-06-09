<?php

namespace App\Http\Controllers\Owner;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ownerId = auth()->id();

        $venueIds = Venue::where('user_id', $ownerId)->pluck('id');

        $dailyBookings = Booking::query()
            ->whereIn('venue_id', $venueIds)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyRevenue = Payment::query()
            ->whereHas('booking', fn ($q) => $q->whereIn('venue_id', $venueIds))
            ->where('status', PaymentStatus::Paid)
            ->select(DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"), DB::raw('sum(amount) as total'))
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('owner.dashboard', [
            'stats' => [
                'venues' => $venueIds->count(),
                'bookings' => Booking::whereIn('venue_id', $venueIds)->count(),
                'revenue' => Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $venueIds))
                    ->where('status', PaymentStatus::Paid)->sum('amount'),
                'pending' => Booking::whereIn('venue_id', $venueIds)->where('status', 'pending')->count(),
            ],
            'dailyBookings' => $dailyBookings,
            'monthlyRevenue' => $monthlyRevenue,
            'recentBookings' => Booking::with(['user', 'venue'])
                ->whereIn('venue_id', $venueIds)
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
