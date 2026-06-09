<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Payment\MockPaymentGateway;

class PaymentService
{
    public function __construct(
        protected ActivityLogService $activityLog,
    ) {}

    public function createForBooking(Booking $booking, PaymentGateway $gateway = PaymentGateway::Mock): Payment
    {
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway' => $gateway,
            'amount' => $booking->total,
            'status' => PaymentStatus::Pending,
            'expires_at' => now()->addHours(24),
        ]);

        $this->gateway($gateway)->charge($payment);

        return $payment->fresh();
    }

    public function gateway(PaymentGateway $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            PaymentGateway::Mock => app(MockPaymentGateway::class),
            default => app(MockPaymentGateway::class),
        };
    }
}
