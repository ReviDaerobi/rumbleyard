<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Sport;
use App\Models\Venue;
use App\Repositories\Contracts\VenueRepositoryInterface;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function __construct(
        protected VenueRepositoryInterface $venues,
        protected ScheduleService $schedule,
    ) {}

    public function index(Request $request): View
    {
        $venues = $this->venues->search($request->only([
            'q', 'city', 'sport_id', 'sport_ids', 'min_price', 'max_price', 'min_rating', 'sort', 'facilities',
        ]));

        return view('venues.index', [
            'venues' => $venues,
            'sports' => Sport::where('is_active', true)->orderBy('sort_order')->get(),
            'filters' => $request->all(),
            'facilities' => $this->facilityOptions(),
            'bookedUntil' => $this->bookedUntilMap($venues->getCollection()),
        ]);
    }

    public function show(Venue $venue): View
    {
        $this->authorize('view', $venue);

        $venue->load(['sport', 'images', 'reviews.user']);

        return view('venues.show', [
            'venue' => $venue,
            'isFavorite' => auth()->check()
                ? auth()->user()->favorites()->where('venue_id', $venue->id)->exists()
                : false,
        ]);
    }

    public function slots(Venue $venue, Request $request): JsonResponse
    {
        $request->validate(['date' => ['required', 'date']]);

        return response()->json([
            'slots' => $this->schedule->slotsForDate($venue, $request->date('date')->format('Y-m-d')),
        ]);
    }

    /** @return array<string, string> */
    private function facilityOptions(): array
    {
        return [
            'penerangan' => 'Penerangan',
            'ruang_ganti' => 'Ruang Ganti',
            'rumput_sintetis' => 'Rumput Sintetis',
            'parkir' => 'Parkir',
            'dalam_ruangan' => 'Dalam Ruangan',
        ];
    }

    /** @return array<int, string> */
    private function bookedUntilMap($venues): array
    {
        if ($venues->isEmpty()) {
            return [];
        }

        $now = now()->format('H:i:s');
        $today = now()->toDateString();

        return Booking::query()
            ->whereIn('venue_id', $venues->pluck('id'))
            ->whereDate('booking_date', $today)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::Pending])
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->orderByDesc('end_time')
            ->get()
            ->unique('venue_id')
            ->mapWithKeys(fn (Booking $booking) => [
                $booking->venue_id => substr((string) $booking->end_time, 0, 5),
            ])
            ->all();
    }
}
