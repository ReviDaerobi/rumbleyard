<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Notifications\BookingCreatedNotification;

class SendBookingNotification
{
    public function handle(BookingCreated $event): void
    {
        $event->booking->user->notify(new BookingCreatedNotification($event->booking));
    }
}
