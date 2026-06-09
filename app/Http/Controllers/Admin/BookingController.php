<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Booking::class);

        $bookings = Booking::query()
            ->with(['venue', 'user', 'payment'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->q;
                $q->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('venue', fn ($v) => $v->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statuses' => BookingStatus::cases(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Booking::class);

        return view('admin.bookings.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Booking::class);

        $validated = $this->validateBooking($request);

        $user = User::findOrFail($validated['user_id']);
        $venue = Venue::findOrFail($validated['venue_id']);

        try {
            $booking = $this->bookingService->create($user, $venue, $validated);
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['start_time' => $e->getMessage()]);
        }

        if ($request->boolean('create_payment')) {
            $this->paymentService->createForBooking($booking);
        }

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil dibuat.');
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);
        $booking->load(['venue.sport', 'user', 'payment', 'details']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $this->authorize('update', $booking);

        return view('admin.bookings.edit', array_merge(
            ['booking' => $booking],
            $this->formData()
        ));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $validated = $this->validateBooking($request, $booking);

        try {
            $this->bookingService->update($booking, $validated);
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['start_time' => $e->getMessage()]);
        }

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }

    protected function formData(): array
    {
        return [
            'users' => User::role('customer')->orderBy('name')->get(),
            'venues' => Venue::with('sport')->orderBy('name')->get(),
            'statuses' => BookingStatus::cases(),
        ];
    }

    protected function validateBooking(Request $request, ?Booking $booking = null): array
    {
        $dateRules = ['required', 'date'];
        if (! $booking) {
            $dateRules[] = 'after_or_equal:today';
        }

        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'venue_id' => ['required', 'exists:venues,id'],
            'booking_date' => $dateRules,
            'start_time' => ['required', 'date_format:H:i'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:8'],
            'players_count' => ['nullable', 'integer', 'min:1', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::enum(BookingStatus::class)],
            'create_payment' => ['boolean'],
        ]);
    }
}
