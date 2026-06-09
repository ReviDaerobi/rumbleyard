<?php

namespace App\Contracts;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function charge(Payment $payment): array;

    public function verify(string $transactionId): Payment;
}
