<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Services\ActivityLogService;

class LogPaymentActivity
{
    public function __construct(protected ActivityLogService $activityLog) {}

    public function handle(PaymentCompleted $event): void
    {
        $this->activityLog->log(
            $event->payment->booking->user,
            'payment.completed',
            $event->payment,
            ['amount' => $event->payment->amount]
        );
    }
}
