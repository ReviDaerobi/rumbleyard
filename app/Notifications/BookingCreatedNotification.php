<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Booking Dibuat',
            'message' => "Booking {$this->booking->code} menunggu pembayaran.",
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->code,
        ];
    }
}
