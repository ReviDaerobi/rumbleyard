<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Venue;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService,
        protected BookingRepositoryInterface $bookings,
    ) {
        $this->middleware('auth');
    }

    public function create(Venue $venue): View
    {
        $this->authorize('view', $venue);

        return view('bookings.create', compact('venue'));
    }

    public function store(StoreBookingRequest $request, Venue $venue): RedirectResponse
    {
        $this->authorize('create', Booking::class);

        try {
            $booking = $this->bookingService->create($request->user(), $venue, $request->validated());
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['start_time' => $e->getMessage()]);
        }

        $payment = $this->paymentService->createForBooking($booking);

        return redirect()->route('payments.show', $payment);
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);
        $booking->load(['venue.sport', 'payment', 'details']);

        return view('bookings.show', compact('booking'));
    }

    public function history(): View
    {
        return view('bookings.history', [
            'bookings' => $this->bookings->forUser(auth()->id()),
        ]);
    }

    public function success(Booking $booking): View
    {
        $this->authorize('view', $booking);
        $booking->load(['venue', 'payment']);

        return view('bookings.success', compact('booking'));
    }
}
