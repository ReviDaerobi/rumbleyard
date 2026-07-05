<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Venue;
use App\Policies\BookingPolicy;
use App\Policies\VenuePolicy;
use App\Repositories\BookingRepository;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\VenueRepositoryInterface;
use App\Repositories\VenueRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Events\BookingCreated;
use App\Events\PaymentCompleted;
use App\Listeners\LogPaymentActivity;
use App\Listeners\SendBookingNotification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(VenueRepositoryInterface::class, VenueRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Venue::class, VenuePolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);

        Event::listen(BookingCreated::class, SendBookingNotification::class);
        Event::listen(PaymentCompleted::class, LogPaymentActivity::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
