<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentStatus;
use App\Events\PaymentCompleted;
use App\Models\Payment;
use Illuminate\Support\Str;

class MockPaymentGateway implements PaymentGatewayInterface
{
    public function charge(Payment $payment): array
    {
        $transactionId = 'MOCK-'.strtoupper(Str::random(12));

        $payment->update([
            'transaction_id' => $transactionId,
            'status' => PaymentStatus::Pending,
            'expires_at' => now()->addHours(24),
            'meta' => [
                'checkout_url' => route('payments.mock.checkout', $payment),
            ],
        ]);

        return [
            'transaction_id' => $transactionId,
            'checkout_url' => route('payments.mock.checkout', $payment),
        ];
    }

    public function verify(string $transactionId): Payment
    {
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();

        return $payment;
    }

    public function simulateSuccess(Payment $payment): Payment
    {
        $payment->update([
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        $payment->booking->update(['status' => \App\Enums\BookingStatus::Confirmed]);

        event(new PaymentCompleted($payment));

        return $payment->fresh();
    }

    public function simulateFailure(Payment $payment): Payment
    {
        $payment->update(['status' => PaymentStatus::Failed]);

        return $payment->fresh();
    }
}
